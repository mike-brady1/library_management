const urlParams = new URLSearchParams(window.location.search);
const bookId = urlParams.get('id');

if (!bookId) {
    window.location.href = 'books_list.html';
}

document.addEventListener('DOMContentLoaded', function() {
    loadBookData();
});

function loadBookData() {
    fetch('../api/get_book.php?id=' + bookId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateForm(data.data);
            } else {
                alert('Error: ' + data.error);
                window.location.href = 'books_list.html';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading book data');
            window.location.href = 'books_list.html';
        });
}

function populateForm(book) {
    document.getElementById('book-id').value = book.book_id;
    document.getElementById('title').value = book.title;
    document.getElementById('author').value = book.author;
    document.getElementById('isbn').value = book.isbn;
    document.getElementById('year').value = book.publication_year || '';
    document.getElementById('quantity').value = book.quantity;

    document.getElementById('loading').style.display = 'none';
    document.getElementById('edit-book-form').style.display = 'block';
}

document.getElementById('edit-book-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    document.getElementById('error-general').textContent = '';
    document.getElementById('success-message').textContent = '';
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    // Get form data
    const data = {
        book_id: parseInt(document.getElementById('book-id').value),
        title: document.getElementById('title').value.trim(),
        author: document.getElementById('author').value.trim(),
        isbn: document.getElementById('isbn').value.trim(),
        year: document.getElementById('year').value || null,
        quantity: parseInt(document.getElementById('quantity').value)
    };

    // Validate
    if (!data.title) {
        document.getElementById('error-title').textContent = 'Title is required';
        return;
    }
    if (!data.author) {
        document.getElementById('error-author').textContent = 'Author is required';
        return;
    }
    if (!data.isbn) {
        document.getElementById('error-isbn').textContent = 'ISBN is required';
        return;
    }
    if (isNaN(data.quantity) || data.quantity < 0) {
        document.getElementById('error-quantity').textContent = 'Quantity must be 0 or more';
        return;
    }

    // Send to backend
    fetch('../api/update_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            document.getElementById('success-message').textContent = result.message;
            setTimeout(() => {
                window.location.href = 'books_list.html';
            }, 1500);
        } else {
            document.getElementById('error-general').textContent = 'Error: ' + result.error;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('error-general').textContent = 'Network error. Please try again.';
    });
});

