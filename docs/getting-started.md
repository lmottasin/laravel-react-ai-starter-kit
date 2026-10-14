# Getting started

You mostly just ask Claude. This guide shows what to ask at each step, using a **todo app** (task lists, shareable as viewer or editor) as the example.

Claude asks for your confirmation before every commit and push.

## 1. Create the project

Either:

```bash
laravel new todo-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
```

or **Use this template** on GitHub and clone your new repo.

Then open it in Claude Code: `cd todo-app && claude`. Approve the project settings and the `laravel-boost` MCP when asked.

## 2. Set up

Ask Claude:

> Set up this project: create the PostgreSQL databases todo_app and testing, point .env at todo_app, run composer setup, install the Playwright browser and run composer test.

It should end with every test passing and `Total: 100.0 %`. If pgvector is missing on your server, see [postgresql-pgvector.md](postgresql-pgvector.md).

> Start the app.

Optional, once per machine: Context7 for up-to-date library docs. Run this yourself (it holds your API key):

```bash
claude mcp add --scope local context7 -- npx -y @upstash/context7-mcp --api-key YOUR_KEY
```

## 3. Kickoff (once per project)

| Run                 | Output                        | Todo example                                                                                                                                                                               |
| ------------------- | ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `/project-describe` | `docs/project-description.md` | Paste: "Task lists with tasks (title, notes, due date, done). Share a list as viewer or editor. Admin sees all users."                                                                     |
| `/user-stories`     | `docs/user-stories.md`        | Asks first, e.g. "Delete tasks with their list? 1. Yes, after confirmation (Recommended) 2. Move to Inbox". Writes stories like `US-2.1 Add a task` with criteria incl. "viewer gets 403". |
| `/choose-packages`  | shortlist                     | May suggest nothing for a todo app.                                                                                                                                                        |
| `/db-schema`        | `docs/database-schema.md`     | `task_lists`, `tasks` (status as `TaskStatus` Enum), `task_list_user` (access as `ListAccess` Enum).                                                                                       |
| `/project-phases`   | `docs/project-phases.md`      | Phase 1.1 roles and permissions, Phase 2.1 create and complete tasks, Phase 2.2 share a list. Each task lists its tests.                                                                   |

Review each file before running the next command. Ask for changes in plain words ("tasks need a priority too").

## 4. Build a feature

Example: Phase 2.1, create and complete tasks.

| Step    | Ask Claude                                             | What happens                                                                                                                            |
| ------- | ------------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------- |
| Branch  | "Start a branch for Phase 2.1."                        | Creates `feature/...` from main.                                                                                                        |
| Clarify | `/grill-me Phase 2.1`                                  | Multiple-choice questions on what the docs don't settle, e.g. "Can editors delete tasks? 1. Yes (Recommended) 2. Owner only".           |
| Explore | `/explore-modules add tasks to lists`                  | Parallel read-only agents report where tasks plug in and which tests cover the area.                                                    |
| Spec    | `/opsx:propose "Phase 2.1: create and complete tasks"` | Writes `openspec/changes/<name>/`: proposal with a Don't touch list, specs, `tasks.md`. **You review and approve.**                     |
| Build   | `/opsx:apply`                                          | Migration, models, Enum, Policy, Actions, Form Requests, controller, Resource, React page, tests.                                       |
| Verify  | `/verify-feature`                                      | Playwright CLI checks it as owner, editor and viewer (viewer gets 403), saves screenshots, writes browser tests.                        |
| Tidy    | "Run the laravel-simplifier agent."                    | Cleans up the new code, behaviour unchanged.                                                                                            |
| Review  | "Run the code-reviewer agent."                         | Fresh-context review against the spec and house rules. Ask for `security-reviewer` too for auth, permissions, payments, uploads or MCP. |
| Close   | `/opsx:archive`                                        | Records the change in `openspec/specs/` and ticks the phase item.                                                                       |

The controller Claude writes follows the house rules:

```php
public function store(CreateTaskRequest $request, TaskList $list, CreateTask $action): RedirectResponse
{
    $this->authorize('create', [Task::class, $list]);

    $action->handle($list, $request->validated());

    return back();
}
```

## 5. Ship

| Ask Claude                                                          | What happens                                                                                             |
| ------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| "Run composer test, then commit and push, and open a pull request." | Full gate, commit, push and PR (you confirm the commit and push). CI runs the same checks on PostgreSQL. |
| `/review-pr`                                                        | A commander picks only the reviewers the diff needs and returns one ranked report.                       |
| "Fix the high findings and push."                                   | Fixes, re-runs tests, pushes.                                                                            |
| "CI is green, merge it and switch back to main."                    | Merges and updates your local main.                                                                      |

## Small fixes

Skip `/grill-me` and OpenSpec. Ask directly:

> The due date shows in US format. Show it as 14 Oct 2026, test first, then commit and open a PR.

## Optional

| Ask                        | Use it for                                                                                   |
| -------------------------- | -------------------------------------------------------------------------------------------- |
| `/worktree sharing`        | A separate copy with its own database, to build an unrelated feature in parallel.            |
| `/ralph <change>`          | Autopilot through an approved change, one fresh session per task. Only once tests are solid. |
| `/audit all`               | Health check at the end of each phase and before release.                                    |
| "Run the tech-debt agent." | Debt report per milestone.                                                                   |
