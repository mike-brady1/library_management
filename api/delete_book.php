<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Book.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['book_id']) || !is_numeric($data['book_id'])) {
            throw new Exception('Invalid book ID');
        }

        $book = new Book();
        $result = $book->deleteBook($data['book_id']);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Book deleted successfully!'
            ]);
        } else {
            throw new Exception('Failed to delete book');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>

