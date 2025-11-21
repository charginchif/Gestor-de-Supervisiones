# Syed API

This is the official API for the Syed project, a comprehensive system for managing academic information including students, teachers, coordinators, careers, and more. This API is built with the Lumen framework, a lightweight version of Laravel.

## 🚀 Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/syed-api.git
    cd syed-api
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database and other environment variables in the `.env` file.

4.  **Run Migrations and Seeders:**
    ```bash
    php artisan migrate --seed
    ```

5.  **Run the development server:**
    ```bash
    php -S localhost:8000 -t public
    ```

## 🧪 Running Tests

To run the test suite, use the following command:

```bash
vendor/bin/phpunit
```

## 🔑 Authentication

To access protected endpoints, you need to obtain an authentication token by making a `POST` request to the `/login` endpoint.

- **Endpoint:** `POST /login`
- **Description:** Authenticates a user and returns an access token.
- **Request Body:**
  - `correo` (string, required): The user's email.
  - `contrasena` (string, required): The user's password.

Once you have the `access_token`, you must include it in the `Authorization` header for all subsequent requests to protected endpoints:

```
Authorization: Bearer your-jwt-token
```

## 📖 API Endpoints

The API provides different sets of endpoints based on user roles: Administrator, Coordinator, Student, and Teacher.

### Administrator Endpoints

These endpoints are protected and require an authentication token with the `administrador` role.

| Method | Endpoint                               | Description                                  |
| :----- | :------------------------------------- | :------------------------------------------- |
| GET    | `/usuario`                             | Get a list of all users.                     |
| POST   | `/usuario`                             | Create a new user.                           |
| GET    | `/usuario/{id}`                        | Get a specific user by ID.                   |
| PUT    | `/usuario/{id}`                        | Update a user's information.                 |
| DELETE | `/usuario/{id}`                        | Delete a user.                               |
| GET    | `/planteles`                           | Get a list of all campuses.                  |
| POST   | `/planteles`                           | Create a new campus.                         |
| GET    | `/planteles/{id}`                      | Get a specific campus by ID.                 |
| PUT    | `/planteles/{id}`                      | Update a campus's information.               |
| DELETE | `/planteles/{id}`                      | Delete a campus.                             |
| GET    | `/alumnos`                             | Get a list of all students.                  |
| POST   | `/alumnos`                             | Create a new student.                        |
| GET    | `/alumnos/{id}`                        | Get a specific student by ID.                |
| PUT    | `/alumnos/{id}`                        | Update a student's information.              |
| GET    | `/docentes`                            | Get a list of all teachers.                  |
| POST   | `/docentes`                            | Create a new teacher.                        |
| GET    | `/docentes/{id}`                       | Get a specific teacher by ID.                |
| PUT    | `/docentes/{id}`                       | Update a teacher's information.              |
| GET    | `/coordinadores`                       | Get a list of all coordinators.              |
| POST   | `/coordinadores`                       | Create a new coordinator.                    |
| GET    | `/coordinadores/{id}`                  | Get a specific coordinator by ID.            |
| PUT    | `/coordinadores/{id}`                  | Update a coordinator's information.          |
| GET    | `/carreras`                            | Get a list of all careers.                   |
| POST   | `/carreras`                            | Create a new career.                         |
| GET    | `/carreras/{id}`                       | Get a specific career by ID.                 |
| PUT    | `/carreras/{id}`                       | Update a career's information.               |
| DELETE | `/carreras/{id}`                       | Delete a career.                             |
| GET    | `/carrera-modalidad`                   | Get career-modality assignments.             |
| POST   | `/carrera-modalidad`                   | Create a career-modality assignment.         |
| DELETE | `/carrera-modalidad`                   | Delete a career-modality assignment.         |
| GET    | `/materias`                            | Get a list of all subjects.                  |
| POST   | `/materias`                            | Create a new subject.                        |
| GET    | `/materias/{id}`                       | Get a specific subject by ID.                |
| PUT    | `/materias/{id}`                       | Update a subject's information.              |
| DELETE | `/materias/{id}`                       | Delete a subject.                            |
| GET    | `/carrerasPorCoordinador/{id}`         | Get careers assigned to a coordinator.       |
| GET    | `/carrerasPorCoordinador`              | Get all career assignments.                  |
| POST   | `/asignarCarreraCoordinador`           | Assign a career to a coordinator.            |
| PUT    | `/asignarCarreraCoordinador`           | Update a career assignment.                  |
| DELETE | `/asignarCarreraCoordinador`           | Delete a career assignment.                  |
| POST   | `/asignarCarreraPlantel`               | Assign a career to a campus.                 |
| DELETE | `/eliminarCarreraPlantel`              | Delete a career assignment from a campus.    |
| GET    | `/carrerasPorPlantel`                  | Get all careers for all campuses.            |
| GET    | `/carrerasPorPlantel/{id}`             | Get all careers for a specific campus.       |
| POST   | `/plantel-turno`                       | Assign a shift to a campus.                  |
| DELETE | `/plantel-turno/{id}`                  | Delete a shift assignment from a campus.     |
| PUT    | `/plantel-turno/{id}`                  | Update a shift assignment for a campus.      |
| GET    | `/supervision/contable`                | Get accounting supervision criteria.         |
| POST   | `/supervision/contable/insertar`           | Create an accounting supervision criterion.  |
| GET    | `/supervision/contable/buscar`         | Search for accounting supervision criteria.  |
| GET    | `/supervision/contable/{id}`           | Get an accounting supervision criterion.     |
| PUT    | `/supervision/contable/{id}`           | Update an accounting supervision criterion.  |
| DELETE | `/supervision/contable/{id}`           | Delete an accounting supervision criterion.  |
| GET    | `/supervision/no-contable`             | Get non-accounting supervision criteria.     |
| POST   | `/supervision/no-contable/insertar`             | Create a non-accounting supervision criterion.|
| GET    | `/supervision/no-contable/{id}`        | Get a non-accounting supervision criterion.  |
| PUT    | `/supervision/no-contable/{id}`        | Update a non-accounting supervision criterion.|
| DELETE | `/supervision/no-contable/{id}`        | Delete a non-accounting supervision criterion.|
| GET    | `/supervision/rubros`                  | Get all supervision rubros.                  |
| GET    | `/supervision/rubros/contable`         | Get accounting supervision rubros.           |
| POST   | `/supervision/rubros/contable/insertar`         | Create an accounting supervision rubro.      |
| GET    | `/supervision/rubros/contable/{id}`    | Get an accounting supervision rubro.         |
| PUT    | `/supervision/rubros/contable/{id}`    | Update an accounting supervision rubro.      |
| DELETE | `/supervision/rubros/contable/{id}`    | Delete an accounting supervision rubro.      |
| GET    | `/supervision/rubros/no-contable`      | Get non-accounting supervision rubros.       |
| POST   | `/supervision/rubros/no-contable/insertar`      | Create a non-accounting supervision rubro.   |
| GET    | `/supervision/rubros/no-contable/{id}` | Get a non-accounting supervision rubro.      |
| PUT    | `/supervision/rubros/no-contable/{id}` | Update a non-accounting supervision rubro.   |
| DELETE | `/supervision/rubros/no-contable/{id}` | Delete a non-accounting supervision rubro.   |
| GET    | `/plan-estudio`                        | Get all curricula.                           |
| GET    | `/plan-estudio/{id_carrera}`           | Get the curriculum for a specific career.    |
| POST   | `/plan-estudio`                        | Create a new curriculum.                     |
| PUT    | `/plan-estudio/{id_plan_estudio}`      | Update a curriculum.                         |
| DELETE | `/plan-estudio/materia`                | Delete a subject from a curriculum.          |
| DELETE | `/plan-estudio/{id_plan_estudio}`      | Delete a curriculum.                         |
| GET    | `/evaluacion-docente/rubros`           | Get all evaluation rubrics.                  |
| POST   | `/evaluacion-docente/rubros/insertar`           | Create a new evaluation rubric.              |
| GET    | `/evaluacion-docente/rubros/{id}`      | Get a specific evaluation rubric by ID.      |
| PUT    | `/evaluacion-docente/rubros/{id}`      | Update an evaluation rubric.                 |
| DELETE | `/evaluacion-docente/rubros/{id}`      | Delete an evaluation rubric.                 |
| GET    | `/evaluacion-docente/criterios`        | Get all evaluation criteria.                 |
| POST   | `/evaluacion-docente/criterios/insertar`        | Create a new evaluation criterion.           |
| GET    | `/evaluacion-docente/criterios/{id}`   | Get a specific evaluation criterion by ID.   |
| PUT    | `/evaluacion-docente/criterios/{id}`   | Update an evaluation criterion.              |
| DELETE | `/evaluacion-docente/criterios/{id}`   | Delete an evaluation criterion.              |
| GET    | `/modalidades`                         | Get all modalities.                          |
| POST   | `/modalidades`                         | Create a new modality.                       |
| GET    | `/modalidades/{id}`                    | Get a specific modality by ID.               |
| PUT    | `/modalidades/{id}`                    | Update a modality.                           |
| DELETE | `/modalidades/{id}`                    | Delete a modality.                           |
| POST   | `/modalidades/bulk-upsert`             | Bulk upsert modalities.                      |
| GET    | `/grupos`                              | Get all groups.                              |
| POST   | `/grupos`                              | Create a new group.                          |
| GET    | `/grupos/{id}`                         | Get a specific group by ID.                  |
| PUT    | `/grupos/{id}`                         | Update a group.                              |
| DELETE | `/grupos/{id}`                         | Delete a group.                              |
| POST   | `/grupos/asignar-plan`                 | Assign a plan to a group.                    |
| DELETE | `/grupos/{id_grupo}/quitar-plan`       | Remove a plan from a group.                  |
| GET    | `/horarios`                            | Get all schedules.                           |
| POST   | `/horarios`                            | Create a new schedule.                       |
| PUT    | `/horarios/{id}`                       | Update a schedule.                           |
| DELETE | `/horarios/{id}`                       | Delete a schedule.                           |
| GET    | /resultados/admin-supervision        | Get supervision results.                     |
| GET    | /resultados/admin-evaluacion         | Get evaluation results.                      |

### Coordinator Endpoints

These endpoints are protected and require an authentication token with the `coordinador` role.

| Method | Endpoint                                   | Description                                      |
| :----- | :----------------------------------------- | :----------------------------------------------- |
| GET    | `/coordinador-alumnos`                     | Get a list of all students.                      |
| POST   | `/coordinador-alumnos`                     | Create a new student.                            |
| GET    | `/coordinador-alumnos/{id}`                | Get a specific student by ID.                    |
| PUT    | `/coordinador-alumnos/{id}`                | Update a student's information.                  |
| GET    | `/coordinador-docentes`                    | Get a list of all teachers.                      |
| POST   | `/coordinador-docentes`                    | Create a new teacher.                            |
| GET    | `/coordinador-docentes/{id}`               | Get a specific teacher by ID.                    |
| PUT    | `/coordinador-docentes/{id}`               | Update a teacher's information.                  |
| GET    | `/coordinador-planteles`                   | Get campuses associated with the coordinator.    |
| POST   | `/materias/asignar-docente`                | Assign a teacher to a subject.                   |
| GET    | `/coordinador-grupos`                      | Get all groups.                                  |
| POST   | `/coordinador-grupos`                      | Create a new group.                              |
| GET    | `/coordinador-grupos/{id}`                 | Get a specific group by ID.                      |
| PUT    | `/coordinador-grupos/{id}`                 | Update a group.                                  |
| DELETE | `/coordinador-grupos/{id}`                 | Delete a group.                                  |
| POST   | `/coordinador-grupos/asignar-plan`         | Assign a plan to a group.                        |
| DELETE | `/coordinador-grupos/{id_grupo}/quitar-plan` | Remove a plan from a group.                      |
| GET    | `/coordinador-carreras`                    | Get a list of all careers.                       |
| POST   | `/coordinador-carreras`                    | Create a new career.                             |
| GET    | `/coordinador-carreras/{id}`               | Get a specific career by ID.                     |
| PUT    | `/coordinador-carreras/{id}`               | Update a career's information.                   |
| DELETE | `/coordinador-carreras/{id}`               | Delete a career.                                 |
| GET    | `/coordinador-agenda-supervision`                      | Get supervision schedule.                        |
| POST   | `/coordinador-agenda-supervision/insertar`                      | Create a supervision schedule event.             |
| PUT    | `/coordinador-agenda-supervision/{id}`                 | Update a supervision schedule event.             |
| DELETE | `/coordinador-agenda-supervision/{id}`                 | Delete a supervision schedule event.             |
| GET    | `/coordinador-supervision/contable`        | Get accounting supervision criteria.             |
| POST   | `/coordinador-supervision/contable/insertar`        | Create an accounting supervision criterion.      |
| GET    | `/coordinador-supervision/contable/buscar` | Search for accounting supervision criteria.      |
| GET    | `/coordinador-supervision/contable/{id}`   | Get an accounting supervision criterion.         |
| PUT    | `/coordinador-supervision/contable/{id}`   | Update an accounting supervision criterion.      |
| DELETE | `/coordinador-supervision/contable/{id}`   | Delete an accounting supervision criterion.      |
| GET    | `/coordinador-supervision/no-contable`     | Get non-accounting supervision criteria.         |
| POST   | `/coordinador-supervision/no-contable/insertar`     | Create a non-accounting supervision criterion.   |
| GET    | `/coordinador-supervision/no-contable/{id}`| Get a non-accounting supervision criterion.      |
| PUT    | `/coordinador-supervision/no-contable/{id}`| Update a non-accounting supervision criterion.   |
| DELETE | `/coordinador-supervision/no-contable/{id}`| Delete a non-accounting supervision criterion.   |
| GET    | `/coordinador-evaluacion-docente/rubros`   | Get all evaluation rubrics.                      |
| POST   | `/coordinador-evaluacion-docente/rubros/insertar`   | Create a new evaluation rubric.                  |
| GET    | `/coordinador-evaluacion-docente/rubros/{id}`| Get a specific evaluation rubric by ID.          |
| PUT    | `/coordinador-evaluacion-docente/rubros/{id}`| Update an evaluation rubric.                     |
| DELETE | `/coordinador-evaluacion-docente/rubros/{id}`| Delete an evaluation rubric.                     |
| GET    | `/coordinador-evaluacion-docente/criterios`| Get all evaluation criteria.                     |
| POST   | `/coordinador-evaluacion-docente/criterios/insertar`| Create a new evaluation criterion.               |
| GET    | `/coordinador-evaluacion-docente/criterios/{id}`| Get a specific evaluation criterion by ID.       |
| PUT    | `/coordinador-evaluacion-docente/criterios/{id}`| Update an evaluation criterion.                  |
| DELETE | `/coordinador-evaluacion-docente/criterios/{id}`| Delete an evaluation criterion.                  |
| GET    | `/coordinador-solicitud-inscripcion`                   | Get all pending enrollment requests.             |
| POST   | `/coordinador-solicitud-inscripcion/{id}/aprobar`      | Approve an enrollment request.                   |
| DELETE | `/coordinador-solicitud-inscripcion/{id}/rechazar`     | Reject an enrollment request.                    |
| GET    | `/coordinador-solicitud-inscripcion/buscar`            | Search for enrollment requests.                  |
| GET    | `/coordinador-solicitud-inscripcion/todas`             | Get all enrollment requests (approved, rejected, pending). |
| GET    | `/coordinador-solicitud-inscripcion/aprobadas`         | Get all approved enrollment requests.            |
| GET    | `/coordinador-solicitud-inscripcion/rechazadas`        | Get all rejected enrollment requests.            |
| GET    | `/resultados/coordinador-supervision`      | Get supervision results.                         |
| GET    | `/resultados/coordinador-evaluacion`                   | Get evaluation results.                          |

### Student Endpoints

These endpoints are protected and require an authentication token with the `alumno` role.

| Method | Endpoint                               | Description                                  |
| :----- | :------------------------------------- | :------------------------------------------- |
| GET    | `/mis-docentes`                        | Get a list of the student's teachers.        |
| POST   | `/evaluar-docente`                     | Evaluate a teacher.                          |
| POST   | `/inscribir-grupo`                     | Enroll in a group.                           |
| GET    | `/mi-horario`                          | Get the student's schedule.                  |
| POST   | `/solicitud-inscripcion`               | Send an enrollment request to a group.       |
| GET    | `/mis-solicitudes`                     | Get the student's enrollment requests.       |
| DELETE | `/solicitud-inscripcion/{id}/cancelar` | Cancel an enrollment request.                |

### Teacher Endpoints

These endpoints are protected and require an authentication token with the `docente` role.

| Method | Endpoint         | Description                  |
| :----- | :--------------- | :--------------------------- |
| GET    | `/perfil/docente`| Get the teacher's profile.   |