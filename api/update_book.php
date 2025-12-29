<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Book.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        if (empty($data['book_id']) || !is_numeric($data['book_id'])) {
            throw new Exception('Invalid book ID');
        }
        if (empty($data['title']) || empty($data['author']) || empty($data['isbn']) || !isset($data['quantity'])) {
            throw new Exception('Missing required fields');
        }
        if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
            throw new Exception('Quantity must be a positive number');
        }

        $book = new Book();

        // Check if ISBN already exists (excluding current book)
        if ($book->isbnExists($data['isbn'], $data['book_id'])) {
            throw new Exception('ISBN already exists in the system');
        }

        // Update book
        $result = $book->updateBook(
            $data['book_id'],
            $data['title'],
            $data['author'],
            $data['isbn'],
            isset($data['year']) ? $data['year'] : null,
            $data['quantity']
        );

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Book updated successfully!'
            ]);
        } else {
            throw new Exception('Failed to update book');
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

