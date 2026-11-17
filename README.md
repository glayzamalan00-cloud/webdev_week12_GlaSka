# Reading List API

A small token-protected REST API built with Laravel, PostgreSQL, and Laravel Sanctum. Each person keeps a list of books they want to read, are reading, or have finished, and can only see their own books.

## Features

- Register, log in, and log out using a Sanctum token.
- List (10 per page), show, create, update, and delete books.
- Each book has a title, author, status (`to-read`, `reading`, `finished`), and an optional rating from 1 to 5. The database enforces the status and rating rules with CHECK constraints.
- A person can only see their own books. Another person's book returns 404, as if it does not exist.
- The client can never choose the owner of a book, and `user_id` is never sent back.
- A read-only token (ability `books:read`) can look at books but cannot change them.
- All successes and errors return JSON, even when the request has no `Accept` header.
- `php artisan migrate:fresh --seed` creates two users with a few books each.

## Endpoints

Base URL: `http://127.0.0.1:8000/api`

| Method | URL | Auth | Description | Success |
|---|---|---|---|---|
| POST | /register | No | Register and get a token | 201 |
| POST | /login | No | Log in and get a token | 200 |
| POST | /logout | Token | Delete the token that was used | 200 |
| POST | /tokens/readonly | Token | Create a read-only token | 201 |
| GET | /books | Token | List my books (10 per page) | 200 |
| GET | /books/{id} | Token | Show one of my books | 200 |
| POST | /books | Token (write) | Create a book | 201 |
| PUT/PATCH | /books/{id} | Token (write) | Update one of my books | 200 |
| DELETE | /books/{id} | Token (write) | Delete one of my books | 200 |

Send `Authorization: Bearer <token>` for every endpoint marked Token.

## Error responses tested

| # | Case | Status |
|---|---|---|
| 1 | Request /api/books with no token | 401 |
| 2 | Log in with a wrong password | 401 |
| 3 | Create a book with an empty title | 422 |
| 4 | Ask for a book that belongs to the other user | 404 |
| 5 | Ask for a book that does not exist | 404 |
| 6 | Use a read-only token to create a book | 403 |
| 7 | Log out, then use the same token again | 401 |
| 8 | Request with no `Accept` header | 401 (still JSON) |

## Questions

**What is the difference between a 401 and a 403?**
A 401 means the server does not know who you are: there is no token, the token is wrong, or it was logged out. A 403 means the server knows who you are, but you are not allowed to do that action, for example a read-only token trying to create a book.

**What does the database store for a token?**
The `personal_access_tokens` table stores a hashed version of the token (not the real token), the owner (`tokenable_type` and `tokenable_id`), the token name, its abilities (for example `["books:read"]`), when it was last used, and when it expires. Because only the hash is stored, the real token cannot be read from the database.

**Why use a Resource instead of returning the model?**
A Resource controls exactly which fields are sent back, so `user_id` and other internal fields are never exposed. It also keeps the JSON shape consistent, even if the table changes later.

## Setup

1. Copy `.env.example` to `.env` and set the PostgreSQL details (`DB_CONNECTION=pgsql`, database, username, password).
2. `composer install`
3. `php artisan key:generate`
4. `php artisan migrate:fresh --seed`
5. `php artisan serve`

The seeded users and their sample books are in `database/seeders/DatabaseSeeder.php`.

## Postman

Import `webdev-week12-collection.json`. Set the collection variables `base_url` (`http://127.0.0.1:8000/api`), `token`, and `readonly_token` after logging in. No real token or password is stored in the collection.