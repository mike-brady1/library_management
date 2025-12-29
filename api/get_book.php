<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Book.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception('Invalid book ID');
        }

        $book = new Book();
        $data = $book->getBookById($_GET['id']);

        if ($data) {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } else {
            throw new Exception('Book not found');
        }
    } catch (Exception $e) {
        http_response_code(404);
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

