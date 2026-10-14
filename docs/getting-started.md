# Getting started

The workflow is strict and agentic: three commands, and Claude runs every step in between. Hooks and rules stop it from skipping steps. You only make decisions (approve each kickoff document, approve each spec, confirm commits and pushes, merge).

Example throughout: a **todo app** with task lists that can be shared as viewer or editor.

## 1. Create the project

```bash
laravel new todo-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
cd todo-app && claude
```

(Or **Use this template** on GitHub and clone it.) Approve the project settings and the `laravel-boost` MCP when Claude Code asks.

## 2. `/setup-project`

Claude creates the databases (`todo_app`, `testing`), checks pgvector ([details](postgresql-pgvector.md)), writes `.env`, runs `composer setup`, installs the test browser and runs `composer test` until it's green. You confirm the database names.

Optional: ask Claude to add Context7 (up-to-date library docs) with your API key, at local scope so the key never enters git.

## 3. `/kickoff`

Claude runs all five planning steps and stops after each one for your approval:

| Step                | You decide                                            | Todo example                                                                                             |
| ------------------- | ----------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| Project description | Paste your idea, approve the write-up                 | "Task lists with tasks (title, notes, due date, done). Share as viewer or editor. Admin sees all users." |
| User stories        | Answer multiple-choice questions, approve the stories | "Delete tasks with their list? 1. Yes, after confirmation (Recommended) 2. Move to Inbox"                |
| Packages            | Pick from a vetted shortlist                          | Possibly none for a todo app                                                                             |
| Database schema     | Approve the DBML                                      | `task_lists`, `tasks` (`TaskStatus` Enum), `task_list_user` (`ListAccess` Enum)                          |
| Phases              | Approve the plan                                      | 1.1 Roles and permissions, 2.1 Create and complete tasks, 2.2 Share a list                               |

Output lands in `docs/`. Claude commits it once you confirm.

## 4. `/feature "Phase 2.1: create and complete tasks"`

One command, and Claude runs the whole pipeline:

| Stage     | Done by                                                                                                  | Your part                                                                                             |
| --------- | -------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| Branch    | Claude                                                                                                   |                                                                                                       |
| Clarify   | Claude asks only what `docs/` doesn't settle                                                             | Answer multiple-choice questions, e.g. "Can editors delete tasks? 1. Yes (Recommended) 2. Owner only" |
| Explore   | Parallel read-only agents                                                                                |                                                                                                       |
| Spec      | OpenSpec proposal, specs and tasks with a Don't touch list                                               | **Approve the spec**                                                                                  |
| Build     | Claude, tests first, house rules                                                                         |                                                                                                       |
| Verify    | Playwright CLI as owner, editor and viewer, screenshots, browser tests                                   |                                                                                                       |
| Simplify  | `laravel-simplifier` agent                                                                               |                                                                                                       |
| Review    | `code-reviewer` and, when needed, `security-reviewer` in parallel; Claude fixes High and Medium findings |                                                                                                       |
| Gate      | `composer test` until green (asks you after 3 failed attempts)                                           |                                                                                                       |
| Archive   | OpenSpec change recorded, phase item ticked                                                              |                                                                                                       |
| Ship      | Commit, push, pull request, waits for CI                                                                 | **Confirm commit and push**                                                                           |
| PR review | Commander review of the PR, fixes High findings                                                          |                                                                                                       |

You get a report with the PR link, CI status and any decisions left. **You merge.**

The code follows the house rules automatically. For example:

```php
public function store(CreateTaskRequest $request, TaskList $list, CreateTask $action): RedirectResponse
{
    $this->authorize('create', [Task::class, $list]);

    $action->handle($list, $request->validated());

    return back();
}
```

Then the next item: `/feature "Phase 2.2: share a list"`.

## Small fixes

One-file fixes skip the spec. Claude still works on a `fix/` branch (editing code on `main` is blocked) and writes a failing test first. Just ask:

> The due date shows in US format. Show it as 14 Oct 2026, test first, then commit and open a PR.

## Optional

| Command                    | Use it for                                                                                        |
| -------------------------- | ------------------------------------------------------------------------------------------------- |
| `/ralph <change>`          | Autopilot over a long, approved task list, one fresh session per task. Only once tests are solid. |
| `/worktree <slug>`         | A separate copy with its own database, to run a second `/feature` in parallel on unrelated files. |
| `/audit all`               | Health check at the end of each phase and before release.                                         |
| `/review-pr <number>`      | Review any pull request, including ones written by people.                                        |
| "Run the tech-debt agent." | Debt report per milestone.                                                                        |
