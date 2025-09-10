# API Documentation

This document provides instructions for connecting to the API. The API is used to manage users, students, teachers, and coordinators.

## Base URL

The base URL for all API endpoints is:

```
https://syed-api.joannesystem.com/public/
```

## Authentication

To access the protected endpoints, you need to obtain an authentication token by making a `POST` request to the `/login` endpoint.

### Login

* **Endpoint:** `POST /login`
* **Description:** Authenticates a user and returns an access token.
* **Request Body:**
    * `correo` (string, required): The user's email.
    * `contrasena` (string, required): The user's password.
* **Example Response:**

```json
{
    "status": "éxito",
    "mensaje": "Inicio de sesión exitoso",
    "data": {
        "access_token": "your-jwt-token",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": 1
        }
    }
}
```

Once you have the `access_token`, you must include it in the `Authorization` header for all subsequent requests to protected endpoints:

```
Authorization: Bearer your-jwt-token
```

---

## Endpoints for Administrator

These endpoints are protected and require an authentication token with the `administrador` role.

### User Management

*   **`GET /usuario`**: Get a list of all users.
*   **`POST /usuario`**: Create a new user.
*   **`GET /usuario/{id}`**: Get a specific user by ID.
*   **`PUT /usuario/{id}`**: Update a user's information.
*   **`DELETE /usuario/{id}`**: Delete a user.

### Campus Management

*   **`GET /planteles`**: Get a list of all campuses.
*   **`POST /planteles`**: Create a new campus.
*   **`GET /planteles/{id}`**: Get a specific campus by ID.
*   **`PUT /planteles/{id}`**: Update a campus's information.
*   **`DELETE /planteles/{id}`**: Delete a campus.

### Student Management

*   **`GET /alumnos`**: Get a list of all students.
*   **`POST /alumnos`**: Create a new student.
*   **`GET /alumnos/{id}`**: Get a specific student by ID.
*   **`PUT /alumnos/{id}`**: Update a student's information.

### Teacher Management

*   **`GET /docentes`**: Get a list of all teachers.
*   **`POST /docentes`**: Create a new teacher.
*   **`GET /docentes/{id}`**: Get a specific teacher by ID.
*   **`PUT /docentes/{id}`**: Update a teacher's information.

### Coordinator Management

*   **`GET /coordinadores`**: Get a list of all coordinators.
*   **`POST /coordinadores`**: Create a new coordinator.
*   **`GET /coordinadores/{id}`**: Get a specific coordinator by ID.
*   **`PUT /coordinadores/{id}`**: Update a coordinator's information.

---

## Endpoints for Coordinator

These endpoints are protected and require an authentication token with the `coordinador` role.

### Student Management

*   **`GET /alumnos`**: Get a list of all students.
*   **`POST /alumnos`**: Create a new student.
*   **`GET /alumnos/{id}`**: Get a specific student by ID.
*   **`PUT /alumnos/{id}`**: Update a student's information.

### Teacher Management

*   **`GET /docentes`**: Get a list of all teachers.
*   **`POST /docentes`**: Create a new teacher.
*   **`GET /docentes/{id}`**: Get a specific teacher by ID.
*   **`PUT /docentes/{id}`**: Update a teacher's information.

### Campus Management

*   **`GET /planteles`**: Get a list of all campuses associated with the coordinator.