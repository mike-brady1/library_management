<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Book.php';

try {
    $book = new Book();
    $result = $book->getAllBooks();

    $books = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $books,
        'count' => count($books)
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>

