# API Documentation

This document provides instructions for connecting to the API. The API is used to manage users, students, teachers, and coordinators.

## Base URL

The base URL for all API endpoints is your application's public directory. For local development, it is typically `http://localhost/your-project/public/`. For the production environment, it might be something like:

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
            "id_role": 1,
            "rol": "Administrador"
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

### Career Management

*   **`GET /carreras`**: Get a list of all careers.
*   **`POST /carreras`**: Create a new career.
*   **`GET /carreras/{id}`**: Get a specific career by ID.
*   **`PUT /carreras/{id}`**: Update a career's information.
*   **`DELETE /carreras/{id}`**: Delete a career.

### Subject Management

*   **`GET /materias`**: Get a list of all subjects.
*   **`POST /materias`**: Create a new subject.
*   **`GET /materias/{id}`**: Get a specific subject by ID.
*   **`PUT /materias/{id}`**: Update a subject's information.
*   **`DELETE /materias/{id}`**: Delete a subject.

### Coordinator Career Assignment

*   **`GET /carrerasPorCoordinador/{id}`**: Get careers assigned to a coordinator.
*   **`GET /carrerasPorCoordinador`**: Get all career assignments.
*   **`POST /asignarCarreraCoordinador`**: Assign a career to a coordinator.
*   **`PUT /asignarCarreraCoordinador`**: Update a career assignment.
*   **`DELETE /asignarCarreraCoordinador`**: Delete a career assignment.

### Campus Career Assignment

*   **`POST /asignarCarreraPlantel`**: Assign a career to a campus.
*   **`DELETE /eliminarCarreraPlantel`**: Delete a career assignment from a campus.
*   **`GET /carrerasPorPlantel`**: Get all careers for all campuses.
*   **`GET /carrerasPorPlantel/{id}`**: Get all careers for a specific campus.

### Campus Shift Assignment

*   **`POST /plantel-turno`**: Assign a shift to a campus.
*   **`DELETE /plantel-turno/{id}`**: Delete a shift assignment from a campus.
*   **`PUT /plantel-turno/{id}`**: Update a shift assignment for a campus.

### Accounting Supervision Criteria Management

*   **`GET /supervision/contable`**: Get a list of all accounting supervision criteria.
*   **`POST /supervision/contable`**: Create a new accounting supervision criterion.
*   **`GET /supervision/contable/{id}`**: Get a specific accounting supervision criterion by ID.
*   **`PUT /supervision/contable/{id}`**: Update an accounting supervision criterion.
*   **`DELETE /supervision/contable/{id}`**: Delete an accounting supervision criterion.

### Non-Accounting Supervision Criteria Management

*   **`GET /supervision/no-contable`**: Get a list of all non-accounting supervision criteria.
*   **`POST /supervision/no-contable`**: Create a new non-accounting supervision criterion.
*   **`GET /supervision/no-contable/{id}`**: Get a specific non-accounting supervision criterion by ID.
*   **`PUT /supervision/no-contable/{id}`**: Update a non-accounting supervision criterion.
*   **`DELETE /supervision/no-contable/{id}`**: Delete a non-accounting supervision criterion.

### Curriculum Management

*   **`GET /plan-estudio`**: Get all curricula.
*   **`GET /plan-estudio/{id_carrera}`**: Get the curriculum for a specific career.
*   **`POST /plan-estudio`**: Create a new curriculum.
*   **`PUT /plan-estudio`**: Update a curriculum.
*   **`DELETE /plan-estudio`**: Delete a curriculum.

### Teacher Evaluation Criteria Management

*   **`GET /rubros`**: Get a list of all evaluation rubrics.
*   **`GET /criterios-evaluacion`**: Get a list of all evaluation criteria.
*   **`POST /criterios-evaluacion`**: Create a new evaluation criterion.
*   **`GET /criterios-evaluacion/{id}`**: Get a specific evaluation criterion by ID.
*   **`PUT /criterios-evaluacion/{id}`**: Update an evaluation criterion.
*   **`DELETE /criterios-evaluacion/{id}`**: Delete an evaluation criterion.

---

## Endpoints for Coordinator

These endpoints are protected and require an authentication token with the `coordinador` role.

### Student Management

*   **`GET /coordinador-alumnos`**: Get a list of all students.
*   **`POST /coordinador-alumnos`**: Create a new student.
*   **`GET /coordinador-alumnos/{id}`**: Get a specific student by ID.
*   **`PUT /coordinador-alumnos/{id}`**: Update a student's information.

### Teacher Management

*   **`GET /coordinador-docentes`**: Get a list of all teachers.
*   **`POST /coordinador-docentes`**: Create a new teacher.
*   **`GET /coordinador-docentes/{id}`**: Get a specific teacher by ID.
*   **`PUT /coordinador-docentes/{id}`**: Update a teacher's information.

### Campus Management

*   **`GET /coordinador-planteles`**: Get a list of all campuses associated with the coordinator.

---

## Endpoints for Student

These endpoints are protected and require an authentication token with the `alumno` role.

### Teacher Management

*   **`GET /mis-docentes`**: Get a list of the student's teachers.
*   **`POST /evaluar-docente`**: Evaluate a teacher.
*   **`POST /inscribir-grupo`**: Enroll in a group.