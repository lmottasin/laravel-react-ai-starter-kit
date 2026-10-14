# Getting started

The full flow, using a **todo app** (task lists, shareable as viewer or editor) as the example.

## 1. Create

Either:

```bash
laravel new todo-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
```

or **Use this template** on GitHub, then `git clone` your new repo.

## 2. Set up

```bash
# databases: todo_app and testing (pgvector installed on the server, see postgresql-pgvector.md)
cp -n .env.example .env        # set DB_DATABASE=todo_app and credentials
composer setup
npx playwright install chromium
composer test                  # must end with Total: 100.0 %
composer dev
```

In Claude Code (`claude`): approve project settings and the `laravel-boost` MCP. Add Context7 once:

```bash
claude mcp add --scope local context7 -- npx -y @upstash/context7-mcp --api-key YOUR_KEY
```

## 3. Kickoff (once per project)

| Command             | Output                        | Todo example                                                                                                                                                                               |
| ------------------- | ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `/project-describe` | `docs/project-description.md` | Paste: "Task lists with tasks (title, notes, due date, done). Share a list as viewer or editor. Admin sees all users."                                                                     |
| `/user-stories`     | `docs/user-stories.md`        | Asks first, e.g. "Delete tasks with their list? 1. Yes, after confirmation (Recommended) 2. Move to Inbox". Writes stories like `US-2.1 Add a task` with criteria incl. "viewer gets 403". |
| `/choose-packages`  | shortlist                     | May suggest nothing for a todo app.                                                                                                                                                        |
| `/db-schema`        | `docs/database-schema.md`     | `task_lists`, `tasks` (status cast to `TaskStatus` Enum), `task_list_user` (access cast to `ListAccess` Enum).                                                                             |
| `/project-phases`   | `docs/project-phases.md`      | Phase 1.1 roles and permissions, Phase 2.1 create and complete tasks, Phase 2.2 share a list. Each task lists its tests.                                                                   |

Review each file before the next command.

## 4. Feature: Phase 2.1, create and complete tasks

```bash
git switch -c feature/tasks
```

| Step    | Command                                                | What happens                                                                                                                  |
| ------- | ------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------------------------- |
| Clarify | `/grill-me Phase 2.1`                                  | Multiple-choice questions on what the docs don't settle, e.g. "Can editors delete tasks? 1. Yes (Recommended) 2. Owner only". |
| Explore | `/explore-modules add tasks to lists`                  | Parallel read-only agents report where tasks plug in and which tests cover the area.                                          |
| Spec    | `/opsx:propose "Phase 2.1: create and complete tasks"` | Writes `openspec/changes/<name>/` (proposal with a Don't touch list, specs, `tasks.md`). **You review and approve.**          |
| Build   | `/opsx:apply`                                          | Works through `tasks.md`: migration, models, Enum, Policy, Actions, Form Requests, controller, Resource, React page, tests.   |
| Verify  | `/verify-feature <name>`                               | Playwright CLI checks it as owner, editor and viewer (viewer gets 403), saves screenshots, writes `tests/Browser` tests.      |
| Tidy    | "run the laravel-simplifier agent"                     | Cleans up the new code, behaviour unchanged.                                                                                  |
| Review  | "run the code-reviewer agent"                          | Fresh-context review against the spec and house rules. Add `security-reviewer` for auth, permissions, payments, uploads, MCP. |
| Close   | `/opsx:archive`                                        | Merges into `openspec/specs/`, ticks the phase item.                                                                          |
| Check   | `composer test`                                        | Full gate.                                                                                                                    |

The controller follows the house rules:

```php
public function store(CreateTaskRequest $request, TaskList $list, CreateTask $action): RedirectResponse
{
    $this->authorize('create', [Task::class, $list]);

    $action->handle($list, $request->validated());

    return back();
}
```

## 5. Ship

```bash
git add -A && git commit -m "Add tasks"
git push -u origin feature/tasks
```

Open a pull request on GitHub; CI runs `composer test` on PostgreSQL. Before merging, in Claude Code: `/review-pr <number>` (a commander picks only the reviewers the diff needs and returns one ranked report). Merge, then `git switch main && git pull`.

Claude asks before every commit and push.

## Small fixes

Skip `/grill-me` and OpenSpec. Describe the fix, ask for a test first, then commit and open a PR.

## Optional

- `/worktree <slug>`: separate copy with its own database, for parallel work on unrelated features.
- `/ralph <change>`: autopilot through an approved change, one fresh session per task. Only once tests are solid.
- `/audit <area|all>`: health check per phase and before release. `tech-debt` agent per milestone.
