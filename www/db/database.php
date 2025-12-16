<?php
class DatabaseHelper {
    private $db;

    /**
     * Constructor to initialize database connection.
     * 
     * @param string $servername The database server name
     * @param string $username The database username
     * @param string $password The database password
     * @param string $dbname The database name
     * @param int $port The database port
     */
    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
        // use utf8mb4
        $this->db->set_charset('utf8mb4');
    }

    // Return raw mysqli connection when needed
    public function getConnection() {
        return $this->db;
    }

    // Prepare and execute an INSERT for a new user using prepared statements
    public function createUser( $email, $passwordHash, $firstName, $lastName, $phoneNumber, $isAdmin = false ) {
        $sql = "INSERT INTO users (email, password, first_name, last_name, admin, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return [ 'success' => false, 'error' => $this->db->error ];
        }

        // admin is stored as BOOLEAN; use 1/0
        $adminInt = $isAdmin ? 1 : 0;
        $stmt->bind_param('ssssis', $email, $passwordHash, $firstName, $lastName, $adminInt, $phoneNumber);
        $executed = $stmt->execute();
        if (!$executed) {
            $err = $stmt->error;
            $stmt->close();
            return [ 'success' => false, 'error' => $err ];
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        return [ 'success' => true, 'insert_id' => $insertId ];
    }

    // Helper to check if an email already exists
    public function emailExists( $email ) {
        $sql = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Fetch all categories from the database.
     * 
     * @return array Returns an array of categories
     */
    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Check user login credentials.
     * 
     * @param string $email The user's email
     * @param string $password The user's password
     * @return array Returns an array with user details if credentials are valid, empty array otherwise
     */
    public function checkLogin($email, $password){
        $query = "SELECT user_id, email, first_name, last_name, admin, phone_number 
            FROM users WHERE email = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss',$email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Fetch all dishes for a given category from the database.
     * 
     * @param int $categoryId The ID of the category
     * @return array Returns an array of dishes
     */
    public function getAllDishes($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM dishes WHERE category_id = ?");
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Fetch dietary tags for a given dish from the database.
     * 
     * @param int $dishId The ID of the dish
     * @return array Returns an array of dietary tags
     */
    public function getDietaryTagsForDish($dishId) {
        $stmt = $this->db->prepare("SELECT ds.dietary_spec_name 
            FROM dietary_specifications ds
            JOIN dish_specifications dsp ON ds.dietary_spec_id = dsp.dietary_spec_id
            WHERE dsp.dish_id = ?");
        $stmt->bind_param('i', $dishId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }



    // Ritorna tutte le specifiche (id + nome) così hai value=ID e label=nome
    public function getDietarySpecifications() {
        $sql = "SELECT dietary_spec_id, dietary_spec_name FROM dietary_specifications ORDER BY dietary_spec_name";
        $result = $this->db->query($sql);

        $specs = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $specs[] = $row; // ['dietary_spec_id' => ..., 'dietary_spec_name' => ...]
            }
            $result->free();
        }
        return $specs;
    }

    // Ritorna gli ID delle preferenze selezionate dall'utente
    public function getUserDietarySpecIds($userId) {
        $sql = "SELECT dietary_spec_id FROM user_specifications WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();

        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['dietary_spec_id'];
        }
        $stmt->close();

        return $ids;
    }

    // Salva preferenze "a rimpiazzo": delete tutte, poi insert delle nuove
    public function saveUserDietarySpecs($userId, $specIds) {
        // normalizza: solo interi, unici
        $specIds = array_values(array_unique(array_map('intval', $specIds)));

        $this->db->begin_transaction();

        try {
            // 1) cancella vecchie
            $del = $this->db->prepare("DELETE FROM user_specifications WHERE user_id = ?");
            if (!$del) throw new Exception($this->db->error);
            $del->bind_param("i", $userId);
            if (!$del->execute()) throw new Exception($del->error);
            $del->close();

            // 2) inserisci nuove (se ce ne sono)
            if (count($specIds) > 0) {
                $ins = $this->db->prepare("INSERT INTO user_specifications (user_id, dietary_spec_id) VALUES (?, ?)");
                if (!$ins) throw new Exception($this->db->error);

                foreach ($specIds as $specId) {
                    $ins->bind_param("ii", $userId, $specId);
                    if (!$ins->execute()) throw new Exception($ins->error);
                }
                $ins->close();
            }

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>