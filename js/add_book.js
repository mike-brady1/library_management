document.getElementById('add-book-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    document.getElementById('error-general').textContent = '';
    document.getElementById('success-message').textContent = '';
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    // Get form data
    const data = {
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
    fetch('../api/add_book.php', {
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
            document.getElementById('add-book-form').reset();
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

