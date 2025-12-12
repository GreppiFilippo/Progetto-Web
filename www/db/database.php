<?php
class DatabaseHelper {
    private $db;

    public function __construct( $servername, $username, $password, $dbname, $port ) {
        $this->db = new mysqli( $servername, $username, $password, $dbname, $port );
        if ( $this->db->connect_error ) {
            die( "Connection failed: " . $this->db->connect_error );
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
}
?>