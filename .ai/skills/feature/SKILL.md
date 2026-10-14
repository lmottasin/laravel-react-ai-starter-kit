---
name: feature
description: Build one feature end to end, agentically - clarify, explore, spec, build, verify in the browser, simplify, review, fix, archive, and open a pull request - stopping only for spec approval and commit/push confirmation.
argument-hint: "\"<phase item, e.g. Phase 2.1: create and complete tasks>\""
disable-model-invocation: true
---

# Feature: $ARGUMENTS

Run the whole pipeline yourself. You are the only agent that edits code; subagents only read and review. Keep the user informed with one short line per stage.

## Stages

1. **Branch.** From an up-to-date `main`, create `feature/<short-slug>`. If the working tree isn't clean, ask first.
2. **Clarify.** Follow `${CLAUDE_SKILL_DIR}/../grill-me/SKILL.md`. Read `docs/` and `openspec/specs/` first; ask only what they don't settle.
3. **Explore.** Follow `${CLAUDE_SKILL_DIR}/../explore-modules/SKILL.md` (parallel read-only Explore agents). Note the existing tests that cover the area.
4. **Spec.** Use the openspec-propose skill to create the change for "$ARGUMENTS", including the Grill-me decisions and a Don't touch list. Validate it with `openspec validate`.

   **Gate:** summarise the proposal and tasks, then ask with AskUserQuestion: **Approve and build** (Recommended), **Change the spec** (notes say what), **Stop**. Don't write code before approval.
5. **Build.** Use the openspec-apply-change skill to work through `tasks.md`. Tests first for bugs. Run only the related tests while working.
6. **Verify.** For user-facing changes, follow `${CLAUDE_SKILL_DIR}/../verify-feature/SKILL.md` (Playwright CLI per role, screenshots, browser tests). Fix anything that fails and verify again.
7. **Simplify.** Run the `laravel-simplifier` agent on the changed files if it's available; then re-run the related tests.
8. **Review.** In one message, launch the `code-reviewer` agent and, when the change touches auth, permissions, policies, payments, uploads or MCP tools, the `security-reviewer` agent. Fix every High and Medium finding that holds up when you check it against the code. List Low findings in the report instead of fixing them.
9. **Gate check.** Run `composer test`. If it fails, fix and re-run. After 3 failed attempts on the same problem, stop and ask the user.
10. **Archive.** Use the openspec-archive-change skill; tick the item in `docs/project-phases.md`.
11. **Ship.** Commit with a clear message, push the branch, and open a pull request (the user confirms the commit and push). Put the proposal summary and the test result in the PR description. Wait for CI and report its result.
12. **PR review.** Follow `${CLAUDE_SKILL_DIR}/../review-pr/SKILL.md` on the new pull request. Fix High findings, push again, re-check CI.

## Report

End with: what was built, the PR link, CI status, review findings fixed and left, screenshots folder, and anything the user must decide. Don't merge; suggest merging once the user is happy.
