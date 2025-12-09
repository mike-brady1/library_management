document.addEventListener('DOMContentLoaded', function() {
    loadBooks();
});

function loadBooks() {
    fetch('../api/get_books.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.count > 0) {
                displayBooks(data.data);
            } else {
                document.getElementById('books-list').innerHTML = 
                    '<div class="no-books">No books found. <a href="add_book.html">Add one now!</a></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('books-list').innerHTML = 
                '<div class="no-books" style="color: red;">Error loading books</div>';
        });
}

function displayBooks(books) {
    let html = '<table>';
    html += '<thead><tr>';
    html += '<th>Title</th>';
    html += '<th>Author</th>';
    html += '<th>ISBN</th>';
    html += '<th>Year</th>';
    html += '<th>Quantity</th>';
    html += '<th>Actions</th>';
    html += '</tr></thead>';
    html += '<tbody>';

    books.forEach(book => {
        html += '<tr>';
        html += '<td>' + escapeHtml(book.title) + '</td>';
        html += '<td>' + escapeHtml(book.author) + '</td>';
        html += '<td>' + escapeHtml(book.isbn) + '</td>';
        html += '<td>' + book.publication_year + '</td>';
        html += '<td>' + book.quantity + '</td>';
        html += '<td>';
        html += '<button class="btn-edit" onclick="editBook(' + book.book_id + ')">Edit</button>';
        html += '<button class="btn-delete" onclick="deleteBook(' + book.book_id + ')">Delete</button>';
        html += '</td>';
        html += '</tr>';
    });

    html += '</tbody></table>';
    document.getElementById('books-list').innerHTML = html;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function editBook(bookId) {
    window.location.href = 'edit_book.html?id=' + bookId;
}

function deleteBook(bookId) {
    if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        fetch('../api/delete_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ book_id: bookId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Book deleted successfully!');
                loadBooks();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting book');
        });
    }
}

