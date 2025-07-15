
# Food Delivery Platform API

This document provides a complete, step-by-step guide to setting up and using the API for this food delivery platform.

---

## Table of Contents

- [Setup Instructions](#setup-instructions)  
- [Understanding the Seeder Data](#understanding-the-seeder-data)  
- [Base URL & Authentication](#base-url--authentication)  
- [API Endpoints & Workflow](#api-endpoints--workflow)  
  - [Authentication](#authentication)  
  - [Restaurant & Zone Management](#restaurant--zone-management)  
  - [Ordering Workflow](#ordering-workflow)  
  - [Delivery & Assignment Workflow](#delivery--assignment-workflow)  
- [Testing the API with Insomnia](#testing-the-api-with-insomnia)
- [Interactive API Test Workflow](#interactive-api-test-workflow)

---

## Setup Instructions

### 1. Installation

```bash
# Clone the repository
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
composer install
```

### 2. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`.

### 3. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

---

## Understanding the Seeder Data

The `db:seed` command creates users:

- **Restaurant Owner 1:** `owner1@example.com` (Restaurants 1 & 2)  
- **Restaurant Owner 2:** `owner2@example.com` (Restaurants 3 & 4)  
- **Delivery Person 1:** `delivery1@example.com`  
- **Delivery Person 2:** `delivery2@example.com`  
- **Customer:** `customer1@example.com`  
- **Admin:** `admin@example.com`  
- **Password for all:** `password`  

---

## Base URL & Authentication

All endpoints are under `/api/v1` and require **Bearer Token**.

```http
Authorization: Bearer <your_auth_token>
```

---

## API Endpoints & Workflow

### Authentication

**Login**

```http
POST /api/v1/auth/login
```

**Body:**

```json
{
  "email": "owner1@example.com",
  "password": "password"
}
```

**Success:**

```json
{
  "message": "Login successful",
  "user": { ... },
  "access_token": "1|abcdef..."
}
```

**Logout**

```http
POST /api/v1/auth/logout
```

**Get Current User**

```http
GET /api/v1/auth/user
```

---

### Restaurant & Zone Management

**Create a Delivery Zone**

```http
POST /api/v1/restaurants/1/zones
```

**Body:**

```json
{
  "name": "Gulshan 3km Radius",
  "type": "radius",
  "value": 3000
}
```

---

### Ordering Workflow

**Place Valid Order**

```http
POST /api/v1/orders
```

**Body:**

```json
{
  "restaurant_id": 1,
  "delivery_address": "House 10, Road 5, Gulshan, Dhaka",
  "delivery_latitude": 23.7930,
  "delivery_longitude": 90.4135,
  "total_amount": 1500.00
}
```

**Place Invalid Order**

```json
{
  "restaurant_id": 1,
  "delivery_address": "Far away, Savar",
  "delivery_latitude": 23.8563,
  "delivery_longitude": 90.2579,
  "total_amount": 800.00
}
```

**Response:** `422 Unprocessable Entity`  
Message: _"This location is outside our delivery area."_

---

### Delivery & Assignment Workflow

**Go Online as Delivery Person**

```http
PUT /api/v1/delivery-person/status
```

**Body:**

```json
{
  "is_available": true,
  "current_latitude": 23.7925,
  "current_longitude": 90.4125
}
```

**Assign the Order**

```http
POST /api/v1/orders/{order_id}/assign
```

**Accept the Assignment**

```http
POST /api/v1/assignments/{assignment_id}/accept
```

**Track the Delivery**

```http
GET /api/v1/orders/{order_id}/delivery-status
```

**Response:**

```json
{
  "current_latitude": "23.79250000",
  "current_longitude": "90.41250000",
  "location_updated_at": "2025-07-14T14:17:00.000000Z"
}
```

---

## Testing the API with Insomnia

To make API testing easier, an **Insomnia** API client export is provided.

### Steps:

1. Open Insomnia.
2. Import the `Insomnia.yaml` file into your workspace or collection.
3. Set the following environment variables:

```json
{
  "BASE_URL": "http://127.0.0.1:8000/api/v1",
  "TKN_RESTAURANT": "<SANCTUM TOKEN OF RESTAURANT OWNER>",
  "TKN_DELIVERY_MAN_1": "<SANCTUM TOKEN OF DELIVERY MAN 1>",
  "TKN_DELIVERY_MAN_2": "<SANCTUM TOKEN OF DELIVERY MAN 2>",
  "TKN_CUSTOMER": "<SANCTUM TOKEN OF CUSTOMER>"
}
```

---

## Interactive API Test Workflow

This project includes an interactive HTML file for guided API testing.

- File: [`backend/api-test-workflow.html`](#interactive-api-test-workflow)
- Just open it in your browser and follow the step-by-step instructions to test the API.

---
