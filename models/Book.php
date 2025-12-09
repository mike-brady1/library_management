<?php
require_once __DIR__ . '/../config/db_connection.php';

class Book {
    private $conn;
    private $table = 'books';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Get all books
    public function getAllBooks() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Get book by ID
    public function getBookById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Add new book
    public function addBook($title, $author, $isbn, $year, $quantity) {
        $sql = "INSERT INTO " . $this->table . " (title, author, isbn, publication_year, quantity) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $author, $isbn, $year, $quantity);
        return $stmt->execute();
    }

    // Update book
    public function updateBook($id, $title, $author, $isbn, $year, $quantity) {
        $sql = "UPDATE " . $this->table . " 
                SET title = ?, author = ?, isbn = ?, publication_year = ?, quantity = ? 
                WHERE book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssiii", $title, $author, $isbn, $year, $quantity, $id);
        return $stmt->execute();
    }

    // Delete book
    public function deleteBook($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Check if ISBN exists
    public function isbnExists($isbn, $excludeId = null) {
        $sql = "SELECT book_id FROM " . $this->table . " WHERE isbn = ?";
        if ($excludeId) {
            $sql .= " AND book_id != " . intval($excludeId);
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>

