# IDEA API Documentation

## Base URL

```
{{base_url}}  (Default: http://127.0.0.1:Auth::id()00/api/v1)
```

## Authentication

### Log In

**Endpoint:** `POST /login`
**Request Body:**
| Parameter | Type | Description |
|------------|--------|-------------|
| email | string | User email |
| password | string | User password |

### Reset Password

**Endpoint:** `POST /users/reset-password/{id}`

---

## Departments

### Create Department

**Endpoint:** `POST /departments`
**Request Body:**
| Parameter | Type | Description |
|----------------|--------|-------------|
| department_name | string | Department name |
| QACoordinatorID | int | Coordinator ID |

### Get All Departments

**Endpoint:** `GET /departments`

### Get Department

**Endpoint:** `GET /departments/{id}`

### Update Department

**Endpoint:** `PUT /departments/{id}`
**Request Body:**
| Parameter | Type | Description |
|----------------|--------|-------------|
| department_name | string | Department name |
| QACoordinatorID | int | Coordinator ID |

### Delete Department

**Endpoint:** `DELETE /departments/{id}`

### Get Department Users

**Endpoint:** `GET /departments/users/{id}`

---

## Users

### Get All Users

**Endpoint:** `GET /users`

### Create User

**Endpoint:** `POST /users`
**Request Body:**
| Parameter | Type | Description |
|---------------|--------|-------------|
| role_id | string | Comma-separated role IDs |
| permissions_id| string | Comma-separated permission IDs |
| name | string | User name |
| email | string | User email |
| department_id | int | Department ID |
| photo | string | URL of user photo |

### Update User

**Endpoint:** `PUT /users/{id}`
**Request Body:** (Same as Create User)

### Get User

**Endpoint:** `GET /users/{id}`

### Get User Logs

**Endpoint:** `GET /user-log/{id}`

### Get User Ideas

**Endpoint:** `GET /users/getIdeas/{id}`
**Query Parameters:**

-   `systemSettingID` (int) - System setting ID

---

## Ideas

### Get All Ideas

**Endpoint:** `GET /idea`
**Query Parameters:**
| Parameter | Type | Description |
|------------|--------|-------------|
| department | int | Filter by department |
| title | string | Search by title |
| popular | string | Sort by popularity |
| latest | string | Get latest ideas (asc,desc) |

### Create Idea

**Endpoint:** `POST /idea`
**Request Body:**
| Parameter | Type | Description |
|----------------|--------|-------------|
| title | string | Idea title |
| content | string | Idea content |
| is_anonymous | bool | Submit anonymously |
| category | string | Comma-separated category IDs |
| document | json | List of attached files |

### Update Idea

**Endpoint:** `PUT /idea/{id}`
**Request Body:** (Same as Create Idea)

### Submit Idea

**Endpoint:** `PUT /idea/submit/{id}`
**Request Body:**

-   `is_enabled` (bool) - Submit or unsubmit idea

### Delete Idea

**Endpoint:** `DELETE /idea/{id}`

### Get Idea to Submit

**Endpoint:** `GET /idea/to-submit`

### Get Specific Idea

**Endpoint:** `GET /idea/{id}`

### Get Idea's Comments

**Endpoint:** `GET /idea/get-comment/{id}`

---

## Categories

### Create Category

**Endpoint:** `POST /categories`
**Request Body:**
| Parameter | Type | Description |
|-----------|--------|-------------|
| name | string | Category name |

### Get All Categories

**Endpoint:** `GET /categories`

### Get Specific Category

**Endpoint:** `GET /categories/{id}`

### Update Category

**Endpoint:** `PUT /categories/{id}`
**Request Body:**
| Parameter | Type | Description |
|-----------|--------|-------------|
| name | string | Updated category name |

### Delete Category

**Endpoint:** `DELETE /categories/{id}`

---

## System Settings

### Create System Setting

**Endpoint:** `POST /system-setting`
**Request Body:**
| Parameter | Type | Description |
|-------------------|--------|-------------|
| idea_closure_date | date | Idea submission deadline |
| final_closure_date | date | Final closure date |
| academic_year | string | Academic year |

### Get All System Settings

**Endpoint:** `GET /system-setting`

### Update System Setting

**Endpoint:** `PUT /system-setting/{id}`
**Request Body:** (Same as Create System Setting)

### Delete System Setting

**Endpoint:** `DELETE /system-setting/{id}`

### Get CSV Export

**Endpoint:** `GET /system-setting/getCSV/{id}`

---

## Comments

### Create Comment

**Endpoint:** `POST /comments`
**Request Body:**
| Parameter | Type | Description |
|--------------|--------|-------------|
| idea_id | int | Idea ID |
| comment | string | Comment content |
| is_anonymous | bool | Submit anonymously (0 or 1) |

### Get All Comments

**Endpoint:** `GET /comments`

### Update Comment

**Endpoint:** `PUT /comments/{id}`
**Request Body:** (Same as Create Comment)

### Delete Comment

**Endpoint:** `DELETE /comments/{id}`

---

## Votes

### Create Vote

**Endpoint:** `POST /votes`
**Request Body:**
| Parameter | Type | Description |
|-----------|--------|-------------|
| idea_id | int | Idea ID |
| vote_value | enum (-1,1) | Upvote or downvote |

### Delete Vote

**Endpoint:** `DELETE /votes/{id}`

---

## Logs

### Get All Logs

**Endpoint:** `GET /logs`
**Query Parameters:**
| Parameter | Type | Description |
|-----------|--------|-------------|
| user | string | Filter by user |
| type | string | Filter by log type |
| action | string | Filter by action type |

---

## Roles

### Get All Roles

**Endpoint:** `GET /roles`

---

This documentation provides an overview of all API endpoints and expected parameters. For more details, refer to the Postman collection.
