# Laravel React AI Starter Kit

A ready-made starting point for Laravel + React apps where an AI assistant (Claude) does most of the building, and you stay in charge.

Think of Claude as a fast, careful junior developer. This kit gives it a clear process to follow, a rulebook, and safety checks. You make the decisions; Claude does the legwork.

## How it works, in short

You only need three commands. Claude handles everything in between and stops to ask you whenever a decision is needed.

| Step      | You type                    | What happens                                                                                                                                                                       | Your part                                           |
| --------- | --------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------- |
| 1. Set up | `/setup-project`            | Claude creates the databases, installs everything and checks that all tests pass.                                                                                                  | Confirm the database names.                         |
| 2. Plan   | `/kickoff`                  | Claude asks about your idea, then writes a project description, user stories, a database design and a step-by-step build plan.                                                     | Read and approve each document.                     |
| 3. Build  | `/feature "Phase 1.1: ..."` | Claude builds one piece of the plan: asks questions, writes a short spec, builds it with tests, checks it in a real browser, gets it reviewed, opens a pull request and merges it. | Approve the spec, confirm the commit and the merge. |

Then you repeat step 3 for the next piece of the plan.

For a full example, see **[Getting started: building a todo app](docs/getting-started.md)**.

## Getting started

**1. Create your project.** Pick one:

- With the Laravel installer:
    ```bash
    laravel new my-app --using=https://github.com/lmottasin/laravel-react-ai-starter-kit --database=pgsql
    ```
- Or click **Use this template** at the top of this page and download your copy with `git clone`.

**2. Open it in Claude Code:**

```bash
cd my-app
claude
```

Say yes when Claude Code asks you to trust the project's settings.

**3. Type `/setup-project`** and follow along.

That's it. Then `/kickoff` to plan, and `/feature` to build.

### What your computer needs

- **PHP 8.5** (with the PostgreSQL and code-coverage extensions)
- **PostgreSQL 17** with **pgvector** (an add-on for AI search; [how to check you have it](docs/postgresql-pgvector.md))
- **Node 24**
- **Playwright CLI** (lets Claude use a browser): `npm i -g @playwright/cli`
- **OpenSpec** (keeps the specs): `npm i -g @fission-ai/openspec`

## Built-in safety rules

Claude can't skip the process. These are enforced, not just suggested:

- **Nothing is built before you approve the plan** for it.
- **Claude can't change code on the main branch.** Every change happens on its own branch and arrives as a pull request.
- **Claude asks before every commit, push and merge.** It only merges once the automatic checks pass and the review is clean.
- **Every line of code must be covered by tests**, and the tests run automatically on GitHub for every pull request.
- **Claude can't read your secrets** (the `.env` file) or wipe the database.
- **Code is checked for mistakes and tidied automatically** every time Claude edits a file.

## Other helpful commands

| Type             | When to use it                                                                                         |
| ---------------- | ------------------------------------------------------------------------------------------------------ |
| `/audit all`     | A health check of the whole app. Good at the end of each phase and before a release.                   |
| `/review-pr 12`  | A thorough review of pull request #12, by several reviewers who each check one thing.                  |
| `/worktree name` | Work on two unrelated features at the same time.                                                       |
| `/ralph name`    | Autopilot: Claude works through a long, approved task list on its own. Only once your tests are solid. |

For anything else, such as starting the app or updating a package, just ask Claude in plain words.

## What's inside

<details>
<summary>The technical details</summary>

| Area                     | What you get                                                                                  |
| ------------------------ | --------------------------------------------------------------------------------------------- |
| App                      | Laravel 13, React 19 with Inertia, TypeScript, Tailwind, shadcn/ui components                 |
| Accounts                 | Login, registration, password reset, two-factor login and passkeys, ready to use              |
| Database                 | PostgreSQL with pgvector for AI search                                                        |
| Roles and history        | spatie/laravel-permission (who can do what) and spatie/laravel-activitylog (who changed what) |
| AI features for your app | Laravel AI SDK and Laravel MCP                                                                |
| Code quality             | Strict checks (Pint, Rector, PHPStan at the highest level), 100% test coverage, browser tests |
| Teaching Claude          | Laravel Boost (Laravel knowledge for AI), house rules in `.ai/guidelines`, OpenSpec for specs |
| Automation               | GitHub Actions runs every check on each pull request; Dependabot keeps packages up to date    |

All checks can be run at once with `composer test`.

</details>

## Making it your own

Ask Claude to change things in plain words, for example: _"Add a house rule: prices are always stored in cents."_ The rules live in `.ai/guidelines/house-rules.md`.

If you add a skill made by someone else, read it first: skills are instructions Claude will follow.

## License

MIT. Includes the Playwright CLI skill from [microsoft/playwright-cli](https://github.com/microsoft/playwright-cli) (Apache-2.0), and skills generated by Laravel Boost and spatie/laravel-permission (MIT).
