---
name: db-schema
description: Kickoff step 4. Design the database for the user stories as DBML in docs/database-schema.md, following Laravel and PostgreSQL conventions.
disable-model-invocation: true
---

# Database schema

1. Read `docs/project-description.md`, `docs/user-stories.md` and the existing migrations.
2. Write `docs/database-schema.md` containing one ```dbml block with every table, column, index and relationship, followed by short notes explaining non-obvious choices.
3. Rules:
   - Follow Laravel naming: plural snake_case tables, `id`, `*_id` foreign keys, `timestamps`, pivot tables in alphabetical order.
   - Don't redesign tables that already exist (users, cache, jobs, sessions, passkeys, permission tables, activity_log, agent conversations). Extend them only if a story needs it.
   - No enum columns unless the values can never change. Use a string column cast to a PHP Enum, or a lookup table if users manage the values.
   - Roles and permissions come from spatie/laravel-permission; don't add role columns.
   - Files and images: the media library package if chosen, otherwise a dedicated table.
   - Embeddings: `vector(N)` columns with an index, only where similarity search is a real story.
   - Add indexes for foreign keys and columns used in filters or sorting.
4. Ask the user to review. The next step is `/project-phases`.
