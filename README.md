# Counter

A lightweight unique page view counter API for internal use.

## How It Works

- Accepts a `GET /?page=<url>` request
- Hashes the visitor's IP + User-Agent to identify unique visitors
- A visitor is counted once per page per day
- Returns a JSON response: `{"value": <count>}`

## Tech Stack

- PHP with [FlightPHP](https://flightphp.com/) routing
- SQLite database (`config/counter.db`, outside repo)
- Shared Composer dependencies at `library/vendor/`

## Project Structure

```
counter/
├── index.php       # Main API route
├── db.php          # DB connection and table creation
├── cleanup.php     # Expire old visitor logs
└── composer.json
```

## Setup

1. Ensure `library/vendor/autoload.php` exists (shared Composer dependencies)
2. The SQLite DB is auto-created at `config/counter.db` on first request
3. Point web server root to `htdocs/counter/`

## Usage

```
GET /counter?page=takdekeje.kuceng.my/some-page
```

Response:
```json
{"value": 42}
```
