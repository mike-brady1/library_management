<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Book.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        if (empty($data['title']) || empty($data['author']) || empty($data['isbn']) || !isset($data['quantity'])) {
            throw new Exception('Missing required fields');
        }

        if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
            throw new Exception('Quantity must be a positive number');
        }

        $book = new Book();

        // Check if ISBN already exists
        if ($book->isbnExists($data['isbn'])) {
            throw new Exception('ISBN already exists in the system');
        }

        // Add book
        $result = $book->addBook(
            $data['title'],
            $data['author'],
            $data['isbn'],
            isset($data['year']) ? $data['year'] : null,
            $data['quantity']
        );

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Book added successfully!'
            ]);
        } else {
            throw new Exception('Failed to add book');
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

