# Permissions audit

- Actions hidden in the UI but not blocked on the server.
- Controller actions without `$this->authorize()`, or authorization done in Form Requests.
- Checks on role names (`hasRole('admin')`) instead of permissions through Policies.
- Permission or role names as strings instead of `app/Enums` cases.
- Queries that return other users' or other teams' records (missing scoping by owner or team).
- Routes missing `auth` / `verified` middleware.
- New users not given a default role.
- Missing tests that a user without the permission gets a 403 for each protected action.
- Over-complex permission setups for simple needs, or direct permissions on users instead of on roles.
