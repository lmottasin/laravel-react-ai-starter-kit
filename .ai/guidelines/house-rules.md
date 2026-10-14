# House rules

These override general Laravel habits where they differ.

## Architecture
- Business logic lives in Actions (`app/Actions`): create with `php artisan make:action "Name" --no-interaction`; `final readonly`, one `handle()` method, multi-model writes inside `DB::transaction()`.
- Controllers are thin, use CRUD method names, and extend `App\Http\Controllers\Controller`.
- One Form Request per action. Form Requests only validate; their `authorize()` returns `true`.
- Authorization happens in the controller: start every action with `$this->authorize('ability', $model)`. Policies check permissions (`$user->can(Permission::X)`), never role names.
- Every response goes through an API Resource (Inertia props, JSON replies, MCP results). Never return raw models or hand-built arrays.
- JSON replies use the `ApiResponse` trait from the base controller: `success()`, `created()`, `noContent()`, `error()`. Framework errors are already converted to the same shape.
- Roles, permissions, statuses and types are PHP Enums in `app/Enums`, never loose strings.
- Models keep `#[Fillable]`; never unguard models.
- Never chain migration-creating commands with `&&` or `;` (they get identical timestamps).

## Database
- PostgreSQL with pgvector. Vectors: `$table->vector('embedding', dimensions: N)->index()`, search with `whereVectorSimilarTo()`.
- No enum DB columns unless the values can never change: use a string column cast to a PHP Enum, or a lookup table.

## Testing
- `composer test` must pass before you finish: exactly 100% code coverage and 100% type coverage.
- Every test checks real behaviour; never write a test only to raise coverage.
- For each endpoint cover the happy path, validation failures, and a 403 for a user without permission.
- Fake AI calls with `Ai::fake()`; tests never call real AI providers or external services.
- While working, run only the related tests (`php artisan test --compact --filter=...`).

## Docs and comments
- Laravel ecosystem: use Boost `search-docs` first. Other libraries: use Context7.
- No comments or docblocks that restate the code; only explain why.

## Workflow
- New project: `/project-describe`, `/user-stories`, `/choose-packages`, `/db-schema`, `/project-phases` (docs/).
- Each feature: `/grill-me`, `/explore-modules`, `/opsx:propose` (user approves), `/opsx:apply`, `/verify-feature`, laravel-simplifier agent, code-reviewer agent (plus security-reviewer for auth, permissions, payments, uploads, MCP), `/opsx:archive`. One-file fixes skip OpenSpec.
- Only one agent edits code at a time; extra agents only read and review.
