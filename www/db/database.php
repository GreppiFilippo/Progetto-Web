<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}

class DatabaseHelper {
    /** @var mysqli */
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

    /**
     * Create a new user in the database.
     * 
     * @param string $email The user's email
     * @param string $password The user's password
     * @param string $firstName The user's first name
     * @param string $lastName The user's last name
     * @param bool $isAdmin Whether the user is an admin
     * @return array{success: bool, error?: string, insert_id?: int}
     */
    public function createUser($email, $password, $firstName, $lastName, $isAdmin = false) {
        $sql = "INSERT INTO users (email, password, first_name, last_name, admin) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'error' => (string)$this->db->error];
        }

        // admin is stored as BOOLEAN; use 1/0
        $adminInt = $isAdmin ? 1 : 0;
        $stmt->bind_param('ssssi', $email, $password, $firstName, $lastName, $adminInt);

        $executed = $stmt->execute();
        if (!$executed) {
            $err = (string)$stmt->error;
            $stmt->close();
            return ['success' => false, 'error' => $err];
        }

        $insertId = $stmt->insert_id;
        $stmt->close();
        return ['success' => true, 'insert_id' => $insertId];
    }

    /**
     * Check if an email already exists in the database.
     * @param string $email The email to check
     * @return bool Returns true if the email exists, false otherwise
     */
    public function emailExists($email) {
        $sql = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Fetch all categories from the database.
     * 
     * @return array<int, array<string, mixed>> Returns an array of categories
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Check user login credentials.
     * 
     * @param string $email The user's email
     * @param string $password The user's password
     * @return array<int, array<string, mixed>> Returns an array with user details if credentials are valid, empty array otherwise
     */
    public function checkLogin($email, $password) {
        $query = "SELECT user_id, email, first_name, last_name, admin
                  FROM users
                  WHERE email = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param('ss', $email, $password);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Fetch all dishes for a given category from the database.
     * 
     * @param int $categoryId The ID of the category
     * @return array<int, array<string, mixed>> Returns an array of dishes
     */
    public function getAllDishes($categoryId) {
        $sql = "SELECT * FROM dishes WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param('i', $categoryId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Fetch dietary tags for a given dish from the database.
     * 
     * @param int $dishId The ID of the dish
     * @return array<int, array<string, mixed>> Returns an array of dietary tags
     */
    public function getDietaryTagsForDish($dishId) {
        $sql = "SELECT ds.dietary_spec_name 
                FROM dietary_specifications ds
                JOIN dish_specifications dsp ON ds.dietary_spec_id = dsp.dietary_spec_id
                WHERE dsp.dish_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param('i', $dishId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Fetch all dietary specifications from the database.
     * @return array<int, array<string, mixed>> Returns an array of dietary specifications
     */
    public function getDietarySpecifications() {
        $sql = "SELECT dietary_spec_id, dietary_spec_name
                FROM dietary_specifications
                ORDER BY dietary_spec_name";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $specs = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $specs;
    }

    /**
     * Fetch dietary specification IDs selected by a user.
     * @param int $userId The user ID
     * @return int[] Returns an array of dietary specification IDs
     */
    public function getUserDietarySpecIds($userId): array {
        $sql = "SELECT dietary_spec_id FROM user_specifications WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();

        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['dietary_spec_id'];
        }
        $stmt->close();

        return $ids;
    }

    /**
     * Save dietary specifications selected by a user.
     * @param int $userId The user ID
     * @param array<int> $specIds An array of dietary specification IDs
     * @throws Exception on database errors
     * @return array{success: bool, error?: string}
     */
    public function saveUserDietarySpecs($userId, $specIds): array {
        $specIds = array_values(array_unique(array_map('intval', $specIds)));
        $this->db->begin_transaction();

        try {
            // 1) Remove old specifications
            $del = $this->db->prepare("DELETE FROM user_specifications WHERE user_id = ?");
            if (!$del) throw new Exception($this->db->error);

            $del->bind_param("i", $userId);
            if (!$del->execute()) throw new Exception((string)$del->error);
            $del->close();

            // 2) Insert new specifications (if any)
            if (count($specIds) > 0) {
                $ins = $this->db->prepare("INSERT INTO user_specifications (user_id, dietary_spec_id) VALUES (?, ?)");
                if (!$ins) throw new Exception((string)$this->db->error);

                foreach ($specIds as $specId) {
                    $ins->bind_param("ii", $userId, $specId);
                    if (!$ins->execute()) throw new Exception((string)$ins->error);
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
    
   /**
    * Get user data by user ID (including registration date).
    * 
    * @param int $userId The user ID
    * @return array<string, mixed>|null Returns user data array or null if not found
    */
    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $res = $stmt->get_result();
        $user = $res ? $res->fetch_array(MYSQLI_ASSOC) : null;
        $stmt->close();

        return $user;
    }

    /**
     * Get reservation counts by user (active and completed).
     * 
     * @param int $userId The user ID
     * @return array<string, int> Returns array with 'active_count' and 'completed_count' keys
     */
   public function getReservationCountsByUser($userId): array {
        $sql = "SELECT
                    SUM(CASE WHEN status IN ('Da Visualizzare','In Preparazione','Pronto al ritiro') THEN 1 ELSE 0 END) AS active_count,
                    SUM(CASE WHEN status = 'Completato' THEN 1 ELSE 0 END) AS completed_count
                FROM reservations
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return ['active_count' => 0, 'completed_count' => 0];

        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['active_count' => 0, 'completed_count' => 0];
        }

        $res = $stmt->get_result();
        $row = $res ? ($res->fetch_assoc() ?: null) : null;
        $stmt->close();

        return $row ?: ['active_count' => 0, 'completed_count' => 0];
    }

    /**
     * Get dishes for a reservation with quantities.
     * 
     * @param int $reservationId The reservation ID
     * @return array<int, array<string, mixed>> Returns array of dishes with quantities
     */
    public function getReservationItems($reservationId): array {
        $sql = "SELECT d.dish_id, d.name, rd.quantity
                FROM reservation_dishes rd
                JOIN dishes d ON d.dish_id = rd.dish_id
                WHERE rd.reservation_id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $reservationId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Cancel a reservation by setting its status to 'Annullato'.
     * Only reservations with status 'Da Visualizzare' or 'In Preparazione' can be cancelled.
     * 
     * @param int $reservationId The reservation ID
     * @param int $userId The user ID (to verify ownership)
     * @return array{success: bool, error?: string} Returns success status and optional error message
     */
    public function deleteReservation($reservationId, $userId): array {
        $this->db->begin_transaction();
        try {
            // lock riga, verifica ownership + status
            $chk = $this->db->prepare(
                "SELECT status FROM reservations
                 WHERE reservation_id = ? AND user_id = ?
                 FOR UPDATE"
            );
            if (!$chk) throw new Exception((string)$this->db->error);

            $chk->bind_param("ii", $reservationId, $userId);
            if (!$chk->execute()) throw new Exception($chk->error);

            $res = $chk->get_result();
            $r = $res ? $res->fetch_assoc() : null;
            $chk->close();

            if (!$r) throw new Exception("Prenotazione non trovata.");

            $status = (string)$r["status"];

            // annullabile solo se "Da Visualizzare" o "In Preparazione"
            if (!in_array($status, array("Da Visualizzare", "In Preparazione"), true)) {
                throw new Exception("Non puoi annullare una prenotazione in stato: " . $status);
            }

            // aggiorna solo lo stato, NON cancellare i piatti
            $upd = $this->db->prepare(
                "UPDATE reservations
                 SET status = 'Annullato'
                 WHERE reservation_id = ? AND user_id = ?"
            );
            if (!$upd) throw new Exception((string)$this->db->error);

            $upd->bind_param("ii", $reservationId, $userId);
            if (!$upd->execute()) throw new Exception((string)$upd->error);
            $upd->close();

            $this->db->commit();
            return array("success" => true);

        } catch (Exception $e) {
            $this->db->rollback();
            return array("success" => false, "error" => $e->getMessage());
        }
    }

    /**
     * Get reservations for a user, ordered by date (most recent first).
     * 
     * @param int $userId The user ID
     * @param int|null $limit Optional limit for number of results
     * @return array<int, array<string, mixed>> Returns array of reservations
     */
    public function getReservationsByUser($userId, $limit = null): array {
        $sql = "SELECT reservation_id, total_amount, date_time, status
                FROM reservations
                WHERE user_id = ?
                ORDER BY date_time DESC";

        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        if ($limit !== null) {
            $stmt->bind_param("ii", $userId, $limit);
        } else {
            $stmt->bind_param("i", $userId);
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Get a reservation by ID, verifying it belongs to the user.
     * 
     * @param int $reservationId The reservation ID
     * @param int $userId The user ID (to verify ownership)
     * @return array<string, mixed>|null Returns reservation data or null if not found
     */
    public function getReservationById($reservationId, $userId) {
        $sql = "SELECT reservation_id, total_amount, date_time, notes, status
                FROM reservations
                WHERE reservation_id = ? AND user_id = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("ii", $reservationId, $userId);

        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Get detailed reservation items with quantity, price, and description.
     * 
     * @param int $reservationId The reservation ID
     * @return array<int, array<string, mixed>> Returns array of detailed dish items
     */
    public function getReservationItemsDetailed($reservationId) {
        $sql = "SELECT d.dish_id, d.name, d.description, d.price, rd.quantity
                FROM reservation_dishes rd
                JOIN dishes d ON d.dish_id = rd.dish_id
                WHERE rd.reservation_id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $reservationId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Get dietary tags for all dishes in a reservation (single query).
     * 
     * @param int $reservationId The reservation ID
     * @return array<int, array<int, array<string, mixed>>> Returns array grouped by dish_id with dietary tags
     */
    public function getDietaryTagsForReservation($reservationId) {
        $sql = "SELECT rd.dish_id, ds.dietary_spec_name
                FROM reservation_dishes rd
                JOIN dish_specifications dsp ON dsp.dish_id = rd.dish_id
                JOIN dietary_specifications ds ON ds.dietary_spec_id = dsp.dietary_spec_id
                WHERE rd.reservation_id = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $reservationId);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        // Raggruppa per dish_id: [dish_id => [tag1, tag2...]]
        $map = [];
        foreach ($rows as $r) {
            $dishId = (int)$r["dish_id"];
            $map[$dishId][] = ["dietary_spec_name" => $r["dietary_spec_name"]];
        }
        return $map;
    }

    /**
     * Create a new dish with dietary specifications.
     * 
     * @param string $name The dish name
     * @param string $description The dish description
     * @param float $price The dish price
     * @param int $stock The available stock quantity
     * @param string $imagePath The path to the dish image
     * @param int $calories The calorie count
     * @param int $categoryId The category ID
     * @param array<int> $specIds Array of dietary specification IDs (optional)
     * @return array{success: bool, error?: string, dish_id?: int} Returns success status and dish ID or error message
     */
    public function createDish($name, $description, $price, $stock, $imagePath, $calories, $categoryId, $specIds = []) {
        $this->db->begin_transaction();

        try {
            $sql = "INSERT INTO dishes (name, description, price, stock, image, calories, category_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new Exception($this->db->error);

            $stmt->bind_param("ssdisii", $name, $description, $price, $stock, $imagePath, $calories, $categoryId);
            if (!$stmt->execute()) throw new Exception($stmt->error);

            $dishId = $stmt->insert_id;
            $stmt->close();

            // salva N:M specifiche
            $specIds = array_values(array_unique(array_map("intval", $specIds)));
            if (count($specIds) > 0) {
                $ins = $this->db->prepare("INSERT INTO dish_specifications (dish_id, dietary_spec_id) VALUES (?, ?)");
                if (!$ins) throw new Exception($this->db->error);

                foreach ($specIds as $sid) {
                    $ins->bind_param("ii", $dishId, $sid);
                    if (!$ins->execute()) throw new Exception($ins->error);
                }
                $ins->close();
            }

            $this->db->commit();
            return ["success" => true, "dish_id" => $dishId];

        } catch (Exception $e) {
            $this->db->rollback();
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    
    /**
     * Get all categories ordered by name.
     * 
     * @return array<int, array<string, mixed>> Returns array of categories
     */
    public function getCategories() {
        $sql = "SELECT category_id, category_name FROM categories ORDER BY category_name";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    /**
     * Get time slots for a given date.
     * Filters out slots that are in the past or too close to current time.
     *
     * @param string $slot_date Date in YYYY-MM-DD
     * @param int $minHoursAdvance Minimum hours in advance required (default: 1)
     * @return array List of available time slots
     */
    public function getTimeSlotsByDate($slot_date, $minHoursAdvance = 1) {
        $sql = "SELECT slot_time
                FROM time_slots
                WHERE slot_date = ?
                ORDER BY slot_time ASC";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("s", $slot_date);

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        // If date is not today, return all slots
        $today = date('Y-m-d');
        if ($slot_date !== $today) {
            return $rows;
        }

        // For today, filter slots that are at least minHoursAdvance in the future
        $now = new DateTime();
        $minTime = clone $now;
        $minTime->add(new DateInterval('PT' . $minHoursAdvance . 'H'));

        $filteredRows = [];
        foreach ($rows as $row) {
            $slotDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $slot_date . ' ' . $row['slot_time']);
            if ($slotDateTime && $slotDateTime >= $minTime) {
                $filteredRows[] = $row;
            }
        }

        return $filteredRows;
    }

    /**
     * Create a new reservation with dishes using a transaction.
     * Validates stock availability and calculates total amount.
     *
     * @param int $userId The user ID
     * @param string $dateTime The reservation date/time (YYYY-MM-DD HH:MM:SS)
     * @param array $items Array of items: [['dish_id' => int, 'quantity' => int], ...]
     * @param string|null $notes Optional notes for the reservation
     * @return array{success: bool, error?: string, reservation_id?: int}
     */
    public function setNewReservation($userId, $dateTime, $items, $notes = null) {
        $this->db->begin_transaction();

        try {
            // Validate input
            if (empty($items) || !is_array($items)) {
                throw new Exception("No items provided for reservation.");
            }

            $totalAmount = 0;
            $validatedItems = [];

            // Lock and validate each dish for stock and price
            foreach ($items as $item) {
                $dishId = (int)$item['dish_id'];
                $quantity = (int)$item['quantity'];

                if ($quantity <= 0) {
                    throw new Exception("Invalid quantity for dish ID: " . $dishId);
                }

                // Lock row and fetch dish details
                $stmt = $this->db->prepare(
                    "SELECT dish_id, name, price, stock 
                     FROM dishes 
                     WHERE dish_id = ? 
                     FOR UPDATE"
                );
                if (!$stmt) throw new Exception($this->db->error);

                $stmt->bind_param("i", $dishId);
                if (!$stmt->execute()) throw new Exception($stmt->error);

                $result = $stmt->get_result();
                $dish = $result ? $result->fetch_assoc() : null;
                $stmt->close();

                if (!$dish) {
                    throw new Exception("Dish ID " . $dishId . " not found.");
                }

                // Check stock availability
                if ($dish['stock'] < $quantity) {
                    throw new Exception("Insufficient stock for dish: " . $dish['name'] . ". Available: " . $dish['stock'] . ", requested: " . $quantity);
                }

                // Calculate subtotal
                $subtotal = $dish['price'] * $quantity;
                $totalAmount += $subtotal;

                // Store validated item
                $validatedItems[] = [
                    'dish_id' => $dishId,
                    'quantity' => $quantity,
                    'new_stock' => $dish['stock'] - $quantity
                ];
            }

            // Insert reservation
            $stmt = $this->db->prepare(
                "INSERT INTO reservations (user_id, total_amount, date_time, notes, status) 
                 VALUES (?, ?, ?, ?, 'Da Visualizzare')"
            );
            if (!$stmt) throw new Exception($this->db->error);

            $stmt->bind_param("idss", $userId, $totalAmount, $dateTime, $notes);
            if (!$stmt->execute()) throw new Exception($stmt->error);

            $reservationId = $stmt->insert_id;
            $stmt->close();

            // Insert reservation items and update stock
            $stmtInsert = $this->db->prepare(
                "INSERT INTO reservation_dishes (reservation_id, dish_id, quantity) 
                 VALUES (?, ?, ?)"
            );
            if (!$stmtInsert) throw new Exception($this->db->error);

            $stmtUpdate = $this->db->prepare(
                "UPDATE dishes SET stock = ? WHERE dish_id = ?"
            );
            if (!$stmtUpdate) throw new Exception($this->db->error);

            foreach ($validatedItems as $item) {
                // Insert reservation dish
                $stmtInsert->bind_param("iii", $reservationId, $item['dish_id'], $item['quantity']);
                if (!$stmtInsert->execute()) throw new Exception($stmtInsert->error);

                // Update stock
                $stmtUpdate->bind_param("ii", $item['new_stock'], $item['dish_id']);
                if (!$stmtUpdate->execute()) throw new Exception($stmtUpdate->error);
            }

            $stmtInsert->close();
            $stmtUpdate->close();

            $this->db->commit();
            return ['success' => true, 'reservation_id' => $reservationId];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
