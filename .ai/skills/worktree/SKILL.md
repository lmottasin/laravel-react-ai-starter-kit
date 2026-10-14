---
name: worktree
description: Create a separate copy of the project (git worktree) with its own database and port, so another Claude session can build an unrelated feature in parallel without collisions.
argument-hint: "<feature-slug>"
disable-model-invocation: true
---

# Worktree

Set up an isolated workspace for `$ARGUMENTS` (lowercase, dashes). Below, `<project>` is the current folder name and `<db>` is the current `DB_DATABASE`.

1. `git worktree add ../<project>-$ARGUMENTS -b feature/$ARGUMENTS` from an up-to-date `main`.
2. In the new folder:
   - copy `.env` from the main project, then set `DB_DATABASE=<db>_<slug with underscores>` and `APP_URL=http://127.0.0.1:<free port, e.g. 8001>`;
   - create that database with `psql` or the user's database tool (ask if unsure); the migrations enable pgvector;
   - `composer install`, `npm install`, `php artisan migrate`, `npm run build`.
3. Tell the user how to start a session there: `cd ../<project>-$ARGUMENTS && claude`, and how to serve it on its port.
4. Cleaning up later: `git worktree remove ../<project>-$ARGUMENTS` and drop its database.

Only use worktrees for features that touch different files. Work that shares files stays in one session.
