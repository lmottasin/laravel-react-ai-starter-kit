# Laravel React AI Starter Kit

Laravel 13 + Inertia React starter kit set up for AI-assisted development. AI agents follow your house rules, prove their work with tests, and a human stays in charge of every merge.

Built on Laravel's official [React starter kit](https://laravel.com/docs/starter-kits), with strict tooling from [Nuno Maduro's starter kit](https://github.com/nunomaduro/laravel-starter-kit-inertia-react) and an AI workflow for Claude Code, Codex and Cursor.

## Quick start

Full flow with a todo app example: **[Getting started](docs/getting-started.md)**.

### Step 1: Create your project (pick one)

**Option A, Laravel installer** (recommended):

```bash
laravel new my-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
cd my-app
```

**Option B, GitHub template**: click **Use this template** at the top of this page, then `git clone` your new repository and `cd` into it.

Both options give you the same project. Everything below is the same for both.

### Step 2: Create the databases

Create two empty PostgreSQL databases: `my_app` (the app) and `testing` (for tests). pgvector must be installed on your PostgreSQL server; you don't enable it yourself (see [PostgreSQL and pgvector](docs/postgresql-pgvector.md)).

### Step 3: Point the app at your database

```bash
cp -n .env.example .env
```

Open `.env` and check `DB_DATABASE=my_app`, `DB_USERNAME` and `DB_PASSWORD`.

### Step 4: Install and set up

```bash
composer setup
```

This installs PHP and npm packages, generates the app key, runs the migrations (which also enable pgvector) and builds the frontend.

### Step 5: Check everything works

```bash
npx playwright install chromium
composer test
```

It should end with every test passing and `Total: 100.0 %`.

### Step 6: Start building with Claude Code

```bash
claude
```

Inside Claude Code, type `/project-describe` and paste a description of your project. Claude then guides you through the rest of the kickoff (see [The AI workflow](#the-ai-workflow)).

### Requirements

- PHP 8.5 with `pdo_pgsql` and `pcov` (coverage)
- PostgreSQL 17 with the pgvector extension
- Node 24 (LTS) and npm
- [Playwright CLI](https://github.com/microsoft/playwright-cli) (`npm i -g @playwright/cli`) and [OpenSpec](https://github.com/Fission-AI/OpenSpec) (`npm i -g @fission-ai/openspec`)

## What's included

| Area             | What you get                                                                                                                           |
| ---------------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| Stack            | Laravel 13, Inertia 3, React 19, TypeScript, Tailwind 4, shadcn/ui, Fortify (2FA, passkeys), Wayfinder                                 |
| Frontend tooling | Vite+ (`vp dev`, `vp build`, `vp check` for lint and format)                                                                           |
| Database         | PostgreSQL + pgvector (enabled by a migration), tests run on PostgreSQL                                                                |
| Strictness       | nunomaduro/essentials, Pint, Rector, PHPStan at max level, architecture tests                                                          |
| Tests            | Pest with exactly 100% code coverage and 100% type coverage, plus browser (end-to-end) tests                                           |
| Packages         | spatie/laravel-permission, spatie/laravel-activitylog, Laravel AI SDK, Laravel MCP                                                     |
| Base controller  | `$this->authorize()` and an `ApiResponse` trait (`success`, `created`, `noContent`, `error`); framework errors use the same JSON shape |
| AI knowledge     | Laravel Boost (guidelines, skills, MCP), house rules in `.ai/guidelines`, Context7 recommended                                         |
| AI workflow      | Skills and agents listed below, OpenSpec for specs                                                                                     |
| Safety           | Claude Code permissions (no `.env` reads, no database wipes, asks before commit and push), auto-format and fast-check hooks            |
| CI               | GitHub Actions with PostgreSQL + pgvector and Playwright, Dependabot                                                                   |

One command checks everything:

```bash
composer test   # Pint, Rector, vp check, PHPStan, tsc, type coverage, tests at 100% coverage
```

## The AI workflow

**Once per project (kickoff):**

| Command             | Produces                                                 |
| ------------------- | -------------------------------------------------------- |
| `/project-describe` | `docs/project-description.md`                            |
| `/user-stories`     | `docs/user-stories.md` (asks clarifying questions first) |
| `/choose-packages`  | A vetted package shortlist                               |
| `/db-schema`        | `docs/database-schema.md` (DBML)                         |
| `/project-phases`   | `docs/project-phases.md` with the tests per task         |

**For every feature:**

1. `/grill-me`: Claude interviews you with multiple-choice questions, its recommendation first.
2. `/explore-modules`: parallel read-only agents study the code.
3. `/opsx:propose`: an OpenSpec change you approve.
4. `/opsx:apply`: one agent builds it, tests first.
5. `/verify-feature`: Playwright CLI walks the feature as each role; end-to-end tests are written.
6. `laravel-simplifier` agent tidies the code (Laravel's official plugin, enabled in `.claude/settings.json`).
7. `code-reviewer` agent, plus `security-reviewer` for auth, permissions, payments, uploads and MCP.
8. `/opsx:archive`, then you commit. CI runs `composer test`.

**Other tools:** `/audit <area|all>` health checks, `/review-pr [number]` commander-style PR review, `/worktree <slug>` for parallel work, `/ralph <change>` autopilot loop (only once tests are solid), `tech-debt` agent.

Rule of thumb: only one agent edits code at a time; extra agents read and review.

## Customising

- House rules: `.ai/guidelines/house-rules.md`, then `php artisan boost:update`.
- Your own skills: `.ai/skills/<name>/SKILL.md`, then `php artisan boost:update`.
- OpenSpec rules: `openspec/config.yaml`.
- Read any third-party skill before installing it.

## Known workaround

`routes/settings.php` uses a GET route instead of `Route::redirect()` because of [laravel/wayfinder#326](https://github.com/laravel/wayfinder/issues/326). Revert once that's fixed.

## License

MIT. Includes the Playwright CLI skill from [microsoft/playwright-cli](https://github.com/microsoft/playwright-cli) (Apache-2.0) in `.ai/skills/playwright-cli`, and skills generated by Laravel Boost and spatie/laravel-permission (MIT).
