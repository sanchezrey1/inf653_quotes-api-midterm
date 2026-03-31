# INF653 Quotes API Midterm Project Reyes Sanchez

## Project URL
https://inf653-quotes-api-midterm.onrender.com/api

## Description
This is a RESTful API built using PHP and PostgreSQL that allows users to manage quotes, authors, and categories. The API supports full CRUD operations and filtering capabilities.

## Endpoints

### Quotes
- GET /api/quotes/
- GET /api/quotes/?id=10
- GET /api/quotes/?author_id=5
- GET /api/quotes/?category_id=4
- GET /api/quotes/?author_id=5&category_id=4
- POST /api/quotes/
- PUT /api/quotes/
- DELETE /api/quotes/

### Authors
- GET /api/authors/
- GET /api/authors/?id=5
- POST /api/authors/
- PUT /api/authors/
- DELETE /api/authors/

### Categories
- GET /api/categories/
- GET /api/categories/?id=4
- POST /api/categories/
- PUT /api/categories/
- DELETE /api/categories/

## Technologies Used
- PHP
- PostgreSQL
- Docker
- Apache
- Render (deployment)

## Notes
- All endpoints return JSON responses
- Proper error handling included for missing parameters and invalid IDs