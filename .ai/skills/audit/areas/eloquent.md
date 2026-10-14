# Eloquent audit

- N+1 queries: relationships used in loops, resources or React props without `with()` / `load()`. (Essentials enables strict mode, so lazy loading throws locally; check code paths tests don't cover.)
- Filtering, counting or summing in PHP collections that should happen in SQL.
- Loading large tables into memory (`all()`, `get()` without limits) instead of `chunk`, `lazy` or pagination.
- Missing casts for dates, booleans, JSON and Enums.
- Mass assignment: missing `#[Fillable]`, or `$request->all()` passed to `create()` / `update()` instead of `$request->validated()`.
- Multi-step writes without `DB::transaction()`.
- Query logic repeated across files instead of a scope.
- Sensitive attributes (tokens, secrets, internal flags) reachable from resources or props.
- PostgreSQL specifics: missing indexes on foreign keys and filter columns; vector columns without an index; `whereVectorSimilarTo` without a limit.
- Old rows that should be pruned (`Prunable`) but grow forever.
