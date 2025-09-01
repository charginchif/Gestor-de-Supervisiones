# API Documentation

This document provides instructions for connecting to the API. The API is used to manage users, students, teachers, and coordinators.

## Base URL

The base URL for all API endpoints is:

```
http://your-domain.com/api
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

## User Modification Endpoints

These endpoints are used to update user information. All of these endpoints are protected and require an authentication token.

### Update User

*   **Endpoint:** `PUT /usuario/{id}`
*   **Description:** Update a generic user's information.
*   **Body:** `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `contrasena`, `id_rol` (all optional).

### Update Student

*   **Endpoint:** `PUT /alumnos/{id}`
*   **Description:** Update a student's information.
*   **Body:** `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `matricula`, `id_carrera` (all optional).

### Update Teacher

*   **Endpoint:** `PUT /docentes/{id}`
*   **Description:** Update a teacher's information.
*   **Body:** `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `grado_academico` (all optional).

### Update Coordinator

*   **Endpoint:** `PUT /coordinadores/{id}`
*   **Description:** Update a coordinator's information.
*   **Body:** `nombre`, `apellido_paterno`, `apellido_materno`, `correo` (all optional).
