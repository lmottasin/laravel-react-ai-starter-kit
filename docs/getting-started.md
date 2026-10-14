# Getting started: building a todo app

This guide walks through the whole flow with one example: a **todo app** where people keep task lists and can share a list with someone else. Follow it once and you'll know how every project and feature works with this kit.

The parts:

1. [Create the project](#1-create-the-project)
2. [Set it up](#2-set-it-up)
3. [Kickoff: plan the whole app](#3-kickoff-plan-the-whole-app)
4. [Plan a feature](#4-plan-a-feature)
5. [Build, check and review it](#5-build-check-and-review-it)
6. [Pull request and merge](#6-pull-request-and-merge)
7. [Small fixes](#7-small-fixes)
8. [Optional: parallel work and autopilot](#8-optional-parallel-work-and-autopilot)

Text shown as Claude's answers or file contents is an example of what to expect. Your results will differ in detail.

---

## 1. Create the project

Use the GitHub CLI (`gh`). One command creates your repository from the template **and** clones it:

```bash
gh repo create todo-app --template lmottasin/laravel-react-ai-starter-kit --private --clone
cd todo-app
```

Prefer the Laravel installer? This also works (then create the GitHub repo later with `gh repo create todo-app --private --source=. --push`):

```bash
laravel new todo-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
cd todo-app
```

Both give you the same project.

## 2. Set it up

1. Create two empty PostgreSQL databases: `todo_app` and `testing`. pgvector must be installed on your server; the migrations enable it ([details](postgresql-pgvector.md)).
2. Point the app at the database:
    ```bash
    cp -n .env.example .env
    ```
    In `.env` set `APP_NAME="Todo App"`, `DB_DATABASE=todo_app`, and your `DB_USERNAME` / `DB_PASSWORD`.
3. Install and check:
    ```bash
    composer setup
    npx playwright install chromium
    composer test
    ```
    `composer test` should end with every test passing and `Total: 100.0 %`.
4. Run it: `composer dev`, then open http://localhost:8000. Login, registration, 2FA and settings already work.
5. Start Claude Code in the project folder: `claude`. Approve the project settings and the `laravel-boost` MCP server. Add Context7 once with your own key:
    ```bash
    claude mcp add --scope local context7 -- npx -y @upstash/context7-mcp --api-key YOUR_KEY
    ```

## 3. Kickoff: plan the whole app

Do this once per project. Each command writes a file in `docs/`. Read it, ask Claude to change anything, then move on.

### 3.1 `/project-describe`

Paste your own description. For the todo app:

> A todo app. People sign up and keep task lists. Each list has tasks with a title, optional notes, a due date and a done/not done state. Users can share a list with another user as a viewer (can see) or editor (can add and edit tasks). There's a simple admin who can see all users. Later we want to find similar tasks by meaning.

Claude writes `docs/project-description.md` with your text plus goal, roles (owner, editor, viewer, admin), scope and tech stack.

### 3.2 `/user-stories`

Claude asks questions first. Each one is multiple choice, its recommendation first, and you can pick "Other" or add notes:

> **Can a task belong to more than one list?**
>
> 1. No, one list per task (Recommended): simpler, matches how people use todo lists.
> 2. Yes, tasks can be in several lists.
> 3. Other

> **What happens to tasks when a list is deleted?**
>
> 1. Delete them too, after a confirmation (Recommended)
> 2. Move them to an "Inbox" list
> 3. Other

Then it writes `docs/user-stories.md`:

```markdown
### US-2.1: Add a task to a list

**As a** list owner or editor
**I want to** add a task with a title, notes and an optional due date
**So that** I don't forget it

**Acceptance criteria:**

- [ ] Title is required, max 255 characters
- [ ] Due date can't be in the past
- [ ] New tasks start as "not done"
- [ ] A viewer can't add tasks (gets 403)

**Expected result:** The task appears at the top of the list.
```

### 3.3 `/choose-packages`

Claude searches for packages that fit the stories and shows a short table (package, stories it helps, stars, last release). For a todo app it might suggest nothing beyond what's installed, which is a fine answer. You choose; it installs and runs `php artisan boost:update`.

### 3.4 `/db-schema`

Claude writes `docs/database-schema.md` as DBML:

```dbml
Table task_lists {
  id bigint [pk]
  user_id bigint [ref: > users.id, note: 'owner']
  name varchar
  created_at timestamp
  updated_at timestamp
}

Table tasks {
  id bigint [pk]
  task_list_id bigint [ref: > task_lists.id]
  title varchar
  notes text [null]
  due_on date [null]
  status varchar [note: 'cast to App\Enums\TaskStatus: todo, done']
  completed_at timestamp [null]
  created_at timestamp
  updated_at timestamp
}

Table task_list_user {
  task_list_id bigint [ref: > task_lists.id]
  user_id bigint [ref: > users.id]
  access varchar [note: 'cast to App\Enums\ListAccess: viewer, editor']
}
```

### 3.5 `/project-phases`

Claude writes `docs/project-phases.md`, with the tests each task needs:

```markdown
## Phase 1: Foundations

### Phase 1.1: Roles and permissions

- [ ] Role and Permission Enums, seeder, default role on registration (US-1.1)
      Tests: registration assigns "user" role; admin can open /admin, user gets 403

## Phase 2: Lists and tasks

### Phase 2.1: Create and complete tasks

- [ ] Add, edit, complete and delete tasks in your own list (US-2.1 to US-2.4)
      Tests: feature tests per action incl. validation and 403; browser test: add and complete a task

### Phase 2.2: Share a list

- [ ] Share with viewer or editor access (US-3.1, US-3.2)
```

The plan is done. From here on, each phase item is built as one feature.

## 4. Plan a feature

Example: **Phase 2.1, create and complete tasks**.

Start a branch with git, so the work goes into a pull request later:

```bash
git switch -c feature/tasks
```

### 4.1 `/grill-me Phase 2.1: create and complete tasks`

Claude asks only what the docs don't already answer:

> **How should finished tasks be shown?**
>
> 1. Stay in the list, crossed out, moved to the bottom (Recommended)
> 2. Hidden, with a "show completed" toggle
> 3. Other

> **Can an editor delete tasks, or only the owner?**
>
> 1. Editors can delete (Recommended): editors already change tasks; deleting is part of that.
> 2. Only the owner can delete
> 3. Other

### 4.2 `/explore-modules add tasks to task lists`

Read-only agents look at the code in parallel and report: what exists (User model, auth, settings pages), where tasks plug in (new model, policy, controller, React page), database changes, and which existing tests touch this area.

### 4.3 `/opsx:propose "Phase 2.1: create and complete tasks"`

Claude creates `openspec/changes/create-and-complete-tasks/`:

- `proposal.md`: why, what changes, out of scope, and a **Don't touch** list (for example "auth and settings pages", "sharing, that's Phase 2.2")
- `specs/`: acceptance criteria as testable statements, including a 403 for each protected action
- `tasks.md`: the checklist Claude will follow

```markdown
## Tasks

- [ ] 1. Migration, TaskList and Task models, TaskStatus Enum, factories
- [ ] 2. TaskPolicy: owner and editor can create/update/delete; viewer can only view
- [ ] 3. Actions: CreateTask, UpdateTask, CompleteTask, DeleteTask
- [ ] 4. Form Requests and TaskController (each action starts with $this->authorize)
- [ ] 5. TaskResource and the React list page with Wayfinder routes
- [ ] 6. Feature tests: happy path, validation, 403 for viewers and strangers
- [ ] 7. Verify with Playwright CLI as owner, editor and viewer, with screenshots
```

**Read it. This is your decision point.** Ask for changes in plain words ("tasks need a priority too"), then say it's approved.

## 5. Build, check and review it

### 5.1 `/opsx:apply`

Claude works through `tasks.md` and writes the code and tests. Following the house rules, the controller looks like this:

```php
final class TaskController extends Controller
{
    public function store(CreateTaskRequest $request, TaskList $list, CreateTask $action): RedirectResponse
    {
        $this->authorize('create', [Task::class, $list]);

        $action->handle($list, $request->validated());

        return back();
    }
}
```

The Inertia page gets its data through a Resource (`TaskResource::collection($list->tasks)`), never raw models. While working, Claude runs only the related tests. Each PHP file is formatted on save, and Claude can't finish while PHPStan or Pint report problems.

### 5.2 `/verify-feature create-and-complete-tasks`

Claude opens the app in a real browser with Playwright CLI and checks it as each role:

- **Owner:** adds "Buy milk" with tomorrow's date, ticks it done, sees it move to the bottom.
- **Editor:** can add and edit.
- **Viewer:** sees the list but no add or edit buttons; a direct request returns 403.

Screenshots are saved in the change folder. It then writes browser tests such as `tests/Browser/TaskListTest.php` for the same flows.

### 5.3 Tidy and review

Ask in plain words:

> Run the laravel-simplifier agent on these changes.

> Run the code-reviewer agent.

The reviewer reads the change with fresh eyes against the spec and house rules, and lists real problems with fixes. For changes to login, permissions, payments, uploads or MCP, also ask for the `security-reviewer` agent.

### 5.4 `/opsx:archive`

The change merges into `openspec/specs/` (the always-current description of the app) and Phase 2.1 is ticked in `docs/project-phases.md`.

### 5.5 Final check

```bash
composer test
```

## 6. Pull request and merge

Claude asks before every commit and push. Ask it to commit, or do it yourself:

```bash
git add -A
git commit -m "Add tasks: create, edit, complete and delete"
git push -u origin feature/tasks
```

Then use `gh` for the pull request:

```bash
gh pr create --fill          # opens the PR from your commits
gh pr checks --watch         # waits for CI (composer test on PostgreSQL)
```

In Claude Code, review it before merging:

```
/review-pr 1
```

A commander reads the diff and calls only the reviewers this change needs (spec match, tests, structure, database, security for the policy, React for the page). You get one ranked report. Fix what matters, push again, then merge:

```bash
gh pr merge --squash --delete-branch
git switch main && git pull
```

Next feature: Phase 2.2, same steps from [section 4](#4-plan-a-feature).

## 7. Small fixes

For a typo or a one-file bug, skip `/grill-me` and OpenSpec:

> The due date shows in US format. Show it as 14 Oct 2026. Write a test first.

Claude plans, fixes, runs the related tests, and you commit, push and open a PR as above.

## 8. Optional: parallel work and autopilot

- **`/worktree sharing`**: a second copy of the project with its own database, so another Claude session can build Phase 2.2 while you finish 2.1. Only for features that touch different files.
- **`/ralph create-and-complete-tasks`**: autopilot. After you approve a change, a fresh Claude does one task per round, ticks it, commits, and stops when everything passes. Use it only once Phase 1 is done and tests are solid.
- **`/audit all`**: a health check at the end of each phase and before each release.

## Command cheat sheet

| When           | Command                                                                                                                                 |
| -------------- | --------------------------------------------------------------------------------------------------------------------------------------- |
| Create project | `gh repo create <name> --template lmottasin/laravel-react-ai-starter-kit --private --clone`                                             |
| Set up         | `composer setup`, `composer test`, `composer dev`                                                                                       |
| Kickoff (once) | `/project-describe`, `/user-stories`, `/choose-packages`, `/db-schema`, `/project-phases`                                               |
| Plan a feature | `git switch -c feature/x`, `/grill-me`, `/explore-modules`, `/opsx:propose`                                                             |
| Build          | `/opsx:apply`, `/verify-feature`, simplifier and code-reviewer agents, `/opsx:archive`                                                  |
| Ship           | `git push -u origin feature/x`, `gh pr create --fill`, `gh pr checks --watch`, `/review-pr <n>`, `gh pr merge --squash --delete-branch` |
| Health         | `/audit <area or all>`, `tech-debt` agent                                                                                               |
