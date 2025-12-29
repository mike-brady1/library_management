# Book Inventory Management Module

## Overview
This module handles book inventory operations for the Library Management System:
- Add new books
- Update book details
- Delete books
- View all books in the inventory

## Features

### 1. Add New Book (LIS-8)
- **Access**: Click "Add New Book" button on Books List page
- **URL**: `pages/add_book.html`
- **Required Fields**: Title, Author, ISBN, Quantity
- **Optional Fields**: Publication Year
- **Validation**:
  - All required fields must be filled
  - ISBN must be unique
  - Quantity must be ≥ 0
- **Database**: Inserts record into `books` table

### 2. Update Book Details (LIS-9)
- **Access**: Click "Edit" button on Books List page
- **URL**: `pages/edit_book.html?id=[book_id]`
- **Required Fields**: Title, Author, ISBN, Quantity
- **Optional Fields**: Publication Year
- **Validation**:
  - ISBN uniqueness check (excluding current book)
  - Quantity must be ≥ 0
- **Database**: Updates record in `books` table

### 3. Delete Book (LIS-10)
- **Access**: Click "Delete" button on Books List page
- **Confirmation**: User must confirm deletion
- **Action**: Hard delete from database
- **Database**: Deletes record from `books` table

## File Structure


## Database Schema


## API Endpoints

### GET /api/get_books.php
- **Purpose**: Fetch all books
- **Response**: JSON array of books

### GET /api/get_book.php?id=[book_id]
- **Purpose**: Fetch single book
- **Response**: JSON single book object

### POST /api/add_book.php
- **Purpose**: Add new book
- **Body**: JSON with title, author, isbn, year, quantity
- **Response**: Success/error message

### POST /api/update_book.php
- **Purpose**: Update book
- **Body**: JSON with book_id, title, author, isbn, year, quantity
- **Response**: Success/error message

### POST /api/delete_book.php
- **Purpose**: Delete book
- **Body**: JSON with book_id
- **Response**: Success/error message

## User Workflows

### Adding a Book
1. Navigate to Books List page
2. Click "Add New Book" button
3. Fill in book details (Title, Author, ISBN, Year, Quantity)
4. Click "Add Book"
5. System validates input
6. Book is saved to database
7. Redirected to Books List on success

### Editing a Book
1. Navigate to Books List page
2. Click "Edit" button next to desired book
3. Modify fields as needed
4. Click "Update Book"
5. System validates input
6. Book is updated in database
7. Redirected to Books List on success

### Deleting a Book
1. Navigate to Books List page
2. Click "Delete" button next to desired book
3. Confirm deletion in popup dialog
4. Book is removed from database
5. List refreshes immediately

## Setup Instructions

### 1. Create Database Table
Run the SQL script to create the books table:

### 2. Update Database Connection
Edit `config/db_connection.php` and set your database credentials:
- `$db_host` - Database host (default: localhost)
- `$db_user` - Database username (default: root)
- `$db_pass` - Database password
- `$db_name` - Database name (default: library_management)

### 3. Access Book Inventory
Navigate to `pages/books_list.html` in your browser, or add a link from your dashboard:

## Technical Details

### Book Model (models/Book.php)
The Book class provides:
- `getAllBooks()` - Retrieve all books
- `getBookById($id)` - Get single book
- `addBook($title, $author, $isbn, $year, $quantity)` - Create book
- `updateBook($id, $title, $author, $isbn, $year, $quantity)` - Update book
- `deleteBook($id)` - Delete book
- `isbnExists($isbn, $excludeId)` - Check ISBN uniqueness

### Security Features
- SQL injection prevention using prepared statements
- XSS prevention with HTML escaping
- Input validation (client-side and server-side)
- ISBN uniqueness validation
- Quantity validation (must be >= 0)

## Integration with Other Modules

### For Authentication Module (LIS-6)
To restrict access to librarians only, add authentication check at the top of each page:

### For Borrowing Module (LIS-11, LIS-12)
The borrowing module can use the Book model:

### For Search Module (LIS-13, LIS-14)
Search functionality can extend the Book model with additional methods.

## Notes for Team Members
- **DO NOT** modify files in this module without coordination
- All book-related data operations should use `models/Book.php`
- API endpoints follow REST conventions
- All responses are in JSON format
- Error handling is implemented for all operations

## Completed User Stories
- ✅ **LIS-8**: As a librarian, I want to add new books
- ✅ **LIS-9**: As a librarian, I want to update book details
- ✅ **LIS-10**: As a librarian, I want to delete books

## Testing Checklist
- [ ] Add book with all fields
- [ ] Add book with only required fields
- [ ] Add book with duplicate ISBN (should fail)
- [ ] Add book with negative quantity (should fail)
- [ ] Edit book and change all fields
- [ ] Edit book with duplicate ISBN (should fail)
- [ ] Delete book and confirm removal
- [ ] Cancel delete operation
- [ ] View empty books list
- [ ] View books list with multiple books

## Author
- **Developer**: mike-brady1
- **Module**: Book Inventory Management
- **Date**: December 2025
- **Branch**: feature/book-inventory

## License
This module is part of the Library Management System group project.

