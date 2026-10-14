# PostgreSQL and pgvector

pgvector is a PostgreSQL extension that stores AI embeddings and finds similar items. It needs two things:

1. **Installed on your PostgreSQL server** (once per machine). Check by running this in any database:

    ```sql
    SELECT name, default_version FROM pg_available_extensions WHERE name = 'vector';
    ```

    One row means it's installed. No rows means install it:

    | How you run PostgreSQL | Install pgvector                                 |
    | ---------------------- | ------------------------------------------------ |
    | DBngin or Postgres.app | Already included                                 |
    | Homebrew               | `brew install pgvector`, then restart PostgreSQL |
    | Docker                 | Use the `pgvector/pgvector:pg17` image           |
    | Ubuntu or Debian       | `sudo apt install postgresql-17-pgvector`        |

2. **Enabled in each database.** You don't do this by hand: the first migration runs `Schema::ensureVectorExtensionExists()` (`CREATE EXTENSION IF NOT EXISTS vector`). Tests run the same migrations on the test database, so it's enabled there too. The database user needs permission to create extensions; the default `postgres` user has it.
