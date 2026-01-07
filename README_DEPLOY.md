Deployment (Docker)
-------------------

Quick steps to run the application locally using Docker Compose:

1. Copy environment example:

```bash
cp .env.example .env
```

2. Build and start services:

```bash
docker-compose up --build
```

3. Open the app in your browser at http://localhost:8000/

Notes:
- The MySQL service initializes using `login/registration_login_db.sql`.
- Database connection for the `login` module reads `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` environment variables.
- If you need to change ports, edit `docker-compose.yml`.
