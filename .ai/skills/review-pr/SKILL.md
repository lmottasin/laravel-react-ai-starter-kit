---
name: review-pr
description: Review a pull request or the current branch with a commander that picks only the specialist reviewers the change needs, runs them in parallel, verifies their findings and returns one ranked report.
argument-hint: "[pr-number]"
disable-model-invocation: true
---

# Review a pull request (commander)

## 1. Collect

- With a PR number: `gh pr view $ARGUMENTS --json title,body,files` and `gh pr diff $ARGUMENTS`.
- Without one: review the current branch against `main` (`git diff main...HEAD`, `git log main..HEAD --oneline`).
- Find the linked OpenSpec change (`openspec/changes/<name>/`) from the PR body or branch name.

## 2. Route (you are the commander)

Look at the changed files and pick only the specialists needed:

| Specialist | Called when the change touches | Checklist |
|---|---|---|
| Spec match | always | the OpenSpec change: every acceptance criterion met, nothing outside scope, "Don't touch" respected |
| Tests | always | `../audit/areas/testing.md` |
| Structure | controllers, actions, requests, routes | `../audit/areas/structure.md` |
| Database | migrations, models, queries | `../audit/areas/eloquent.md` |
| Security | auth, policies, permissions, payments, uploads, MCP tools | the `security-reviewer` agent plus `../audit/areas/permissions.md` |
| React | `resources/js` | inertia-react-development and wayfinder-development skills |
| Queues | jobs, listeners, notifications | `../audit/areas/queues.md` |
| API / MCP | JSON endpoints, `routes/ai.php` | `../audit/areas/api.md` |

## 3. Review in parallel

Launch the chosen specialists as read-only subagents in a single message. Give each only its slice of the diff, its checklist, and this output format: severity (High, Medium, Low), file, line, issue, concrete fix.

## 4. Verify and merge

Drop duplicates. Re-check every finding against the actual code and discard ones that don't hold up (AI reviewers produce false alarms). Rank what's left by severity.

## 5. Report

Show one report: verdict (ready, ready after fixes, not ready), the ranked findings, which specialists ran and which were skipped and why. Only post it as a PR comment (`gh pr comment`) if the user asks.
