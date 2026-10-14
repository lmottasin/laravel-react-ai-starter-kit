---
name: setup-project
description: Set up a freshly created or cloned project end to end - databases, .env, dependencies, migrations, browser for e2e tests, and a green composer test run.
disable-model-invocation: true
---

# Set up the project

Do every step yourself; only ask when something can't be detected.

1. **Database names.** App database: the folder name in snake_case (e.g. `todo_app`). Test database: whatever `phpunit.xml` sets for `DB_DATABASE`. Confirm both names with the user in one multiple-choice question (your proposal first).
2. **PostgreSQL.** Find `psql` (PATH, DBngin `/Users/Shared/DBngin/postgresql/*/bin`, Postgres.app, Homebrew). Check pgvector is installed: `SELECT 1 FROM pg_available_extensions WHERE name = 'vector'`. If not, stop and point the user to `docs/postgresql-pgvector.md`. Create both databases if they don't exist. If you can't connect, ask the user for host, port and user.
3. **.env.** Copy `.env.example` to `.env` if missing. Set `APP_NAME` (from the folder name), `DB_DATABASE`, and the connection details you used. Never print secrets back.
4. **Install.** `composer setup` (installs packages, app key, migrations including pgvector, frontend build). Then `npx playwright install chromium`.
5. **Verify.** `composer test`. If it fails, fix environment problems (not test expectations) and run it again.
6. **Report** in a few lines: databases, URL from `composer dev`, test result. Next step: `/kickoff`.
