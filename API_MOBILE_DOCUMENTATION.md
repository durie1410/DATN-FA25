# Mobile API Documentation

## Base URL
```
https://your-domain.com/api/mobile
```

## Authentication
All mobile API endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Dashboard
Get user dashboard data including stats, recent borrows, and notifications.

**GET** `/dashboard`

**Response:**
```json
{
  "success": true,
  "data": {
    "reader": {
      "id": 1,
      "name": "Nguyễn Văn A",
      "email": "user@example.com",
      "card_number": "RD123456",
      "status": "Hoat dong",
      "expiry_date": "2024-12-31",
      "is_expired": false
    },
    "stats": {
      "active_borrows": 3,
      "total_borrows": 15,
      "overdue_books": 1,
      "pending_reservations": 2,
      "total_fines": 50000
    },
    "recent_borrows": [...],
    "upcoming_returns": [...],
    "notifications": [...]
  }
}
```

### 2. Search Books
Search for books by title, author, or description.

**GET** `/search-books`

**Parameters:**
- `query` (required): Search term
- `category_id` (optional): Filter by category
- `limit` (optional): Number of results (default: 20, max: 50)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Lập trình Laravel",
      "author": "Taylor Otwell",
      "category": "Công nghệ",
      "description": "Hướng dẫn lập trình Laravel...",
      "image": "https://example.com/image.jpg",
      "rating": 4.5,
      "reviews_count": 25,
      "is_available": true,
      "can_reserve": false,
      "published_year": 2023,
      "format": "Paperback"
    }
  ],
  "query": "laravel",
  "total": 1
}
```

### 3. Book Details
Get detailed information about a specific book.

**GET** `/book/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Lập trình Laravel",
    "author": "Taylor Otwell",
    "category": "Công nghệ",
    "description": "Hướng dẫn lập trình Laravel...",
    "image": "https://example.com/image.jpg",
    "rating": 4.5,
    "reviews_count": 25,
    "published_year": 2023,
    "format": "Paperback",
    "price": 200000,
    "is_available": true,
    "can_reserve": false,
    "total_copies": 5,
    "available_copies": 3,
    "borrowed_copies": 2,
    "reviews": [...]
  }
}
```

### 4. Categories
Get list of all book categories.

**GET** `/categories`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Công nghệ",
      "description": "Sách về công nghệ thông tin",
      "books_count": 150,
      "color": "#007bff",
      "icon": "fas fa-laptop"
    }
  ]
}
```

### 5. Borrow History
Get user's borrow history with pagination.

**GET** `/borrow-history`

**Parameters:**
- `per_page` (optional): Items per page (default: 15)
- `status` (optional): Filter by status (all, active, returned, overdue)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "book_title": "Lập trình Laravel",
      "book_author": "Taylor Otwell",
      "category": "Công nghệ",
      "borrow_date": "2024-01-15",
      "due_date": "2024-01-29",
      "return_date": null,
      "status": "Dang muon",
      "is_overdue": false,
      "days_overdue": 0,
      "can_extend": true,
      "extensions_count": 0
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 45
  }
}
```

### 6. Reservations
Get user's reservations.

**GET** `/reservations`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "book_title": "Lập trình Laravel",
      "book_author": "Taylor Otwell",
      "category": "Công nghệ",
      "status": "pending",
      "reservation_date": "2024-01-20",
      "expiry_date": "2024-01-27",
      "priority": 1,
      "notes": "Cần sách để học",
      "is_expired": false
    }
  ]
}
```

### 7. Fines
Get user's fines.

**GET** `/fines`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "amount": 50000,
      "reason": "Trả sách muộn",
      "status": "pending",
      "created_at": "2024-01-20T10:00:00Z",
      "paid_at": null,
      "book_title": "Lập trình Laravel",
      "borrow_id": 1
    }
  ],
  "total_pending": 50000,
  "total_paid": 100000
}
```

### 8. Extend Borrow
Extend the due date of a borrowed book.

**POST** `/borrow/{id}/extend`

**Parameters:**
- `days` (optional): Number of days to extend (default: 7)

**Response:**
```json
{
  "success": true,
  "message": "Borrow extended successfully",
  "data": {
    "new_due_date": "2024-02-05",
    "extensions_count": 1
  }
}
```

### 9. Create Reservation
Create a new book reservation.

**POST** `/reservations`

**Parameters:**
- `book_id` (required): Book ID
- `notes` (optional): Additional notes

**Response:**
```json
{
  "success": true,
  "message": "Reservation created successfully",
  "data": {
    "id": 1,
    "book_title": "Lập trình Laravel",
    "expiry_date": "2024-01-27"
  }
}
```

### 10. Cancel Reservation
Cancel an existing reservation.

**DELETE** `/reservations/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Reservation cancelled successfully"
}
```

## Error Responses

All endpoints return consistent error responses:

```json
{
  "success": false,
  "message": "Error description"
}
```

## HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Rate Limiting

API endpoints are rate limited to prevent abuse:
- 60 requests per minute for authenticated users
- 10 requests per minute for unauthenticated requests

## Pagination

Endpoints that return lists support pagination:
- `per_page`: Number of items per page (default: 15, max: 100)
- `page`: Page number (default: 1)

## Date Format

All dates are returned in ISO 8601 format: `YYYY-MM-DD` or `YYYY-MM-DDTHH:mm:ssZ`

## Image URLs

Book images are returned as full URLs:
```
https://your-domain.com/storage/books/image.jpg
```

## Example Usage

### JavaScript/Fetch
```javascript
const response = await fetch('/api/mobile/dashboard', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  }
});

const data = await response.json();
```

### cURL
```bash
curl -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     https://your-domain.com/api/mobile/dashboard
```

### React Native/Axios
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'https://your-domain.com/api/mobile',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});

const dashboard = await api.get('/dashboard');
```