# Structure audit

Check against the house rules:

- Business logic in controllers instead of Actions (long methods, several model writes in a row, business conditionals). Simple CRUD is fine.
- Controller actions that don't start with `$this->authorize(...)`, or permission logic inside Form Request `authorize()` (it must return `true`).
- Responses not going through an API Resource: raw models, `toArray()`, or hand-built arrays in Inertia props or JSON replies.
- JSON replies built with `response()->json()` directly instead of the `ApiResponse` trait.
- Role, permission, status or type values as string literals or `in:` validation lists instead of Enums.
- Validation inside controllers instead of a Form Request.
- The same logic repeated in three or more places (queries belong in model scopes, logic in Actions).
- `env()` used outside `config/` (returns null once config is cached: a production bug, High).
- Manual `find($id)` / `abort(404)` where route model binding would do.
- Lifecycle side effects (file cleanup, cache clearing) scattered across controllers instead of the model or one Action.
- Over-engineering: interfaces with one implementation, repositories wrapping Eloquent, DTOs that only copy two fields.
- Route files over about 150 lines without grouping.
