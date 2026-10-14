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

### Step 2: Let Claude set it up

```bash
cd my-app
claude
```

In Claude Code, run `/setup-project`. Claude creates the databases, checks pgvector, writes `.env`, installs everything and runs `composer test` until it's green.

### Step 3: Plan and build

Run `/kickoff` once to plan the app, then `/feature "Phase 1.1: ..."` for each feature (see [The AI workflow](#the-ai-workflow) and the [todo app walkthrough](docs/getting-started.md)).

<details>
<summary>Manual setup without Claude</summary>

```bash
# create two empty PostgreSQL databases: my_app and testing (pgvector must be installed, see docs/postgresql-pgvector.md)
cp -n .env.example .env   # set DB_DATABASE=my_app and credentials
composer setup
npx playwright install chromium
composer test             # ends with Total: 100.0 %
```

</details>

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

Agentic: three commands, and Claude runs every step in between. You only approve and decide.

| Command                     | When                 | What Claude does                                                                                                                                                     | You                                              |
| --------------------------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| `/setup-project`            | Once, after creating | Databases, `.env`, install, migrations, test browser, green `composer test`                                                                                          | Confirm database names                           |
| `/kickoff`                  | Once per project     | Description, user stories, packages, DB schema (DBML), phases with tests, in `docs/`                                                                                 | Approve each document                            |
| `/feature "Phase 2.1: ..."` | Every feature        | Branch, clarify, explore, OpenSpec spec, build, browser check per role, simplify, parallel reviews with fixes, `composer test`, archive, commit, push, PR, PR review | Approve the spec, confirm commit and push, merge |

Small fixes: just describe them; no spec needed.

**Other tools:** `/ralph <change>` autopilot over long task lists (only once tests are solid), `/worktree <slug>` for a parallel `/feature`, `/audit <area|all>` health checks, `/review-pr <number>`, `tech-debt` agent. The individual steps (`/grill-me`, `/explore-modules`, `/opsx:propose`, `/opsx:apply`, `/verify-feature`, `/opsx:archive`) still work on their own.

Only one agent edits code at a time; extra agents read and review.

## Customising

- House rules: `.ai/guidelines/house-rules.md`, then `php artisan boost:update`.
- Your own skills: `.ai/skills/<name>/SKILL.md`, then `php artisan boost:update`.
- OpenSpec rules: `openspec/config.yaml`.
- Read any third-party skill before installing it.

## Known workaround

`routes/settings.php` uses a GET route instead of `Route::redirect()` because of [laravel/wayfinder#326](https://github.com/laravel/wayfinder/issues/326). Revert once that's fixed.

## License

MIT. Includes the Playwright CLI skill from [microsoft/playwright-cli](https://github.com/microsoft/playwright-cli) (Apache-2.0) in `.ai/skills/playwright-cli`, and skills generated by Laravel Boost and spatie/laravel-permission (MIT).
