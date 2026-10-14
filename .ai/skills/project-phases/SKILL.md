---
name: project-phases
description: Kickoff step 5. Split the whole project into numbered phases and tasks, each with the tests that prove it, in docs/project-phases.md.
disable-model-invocation: true
---

# Project phases

1. Read `docs/project-description.md`, `docs/user-stories.md`, `docs/database-schema.md`, and check the code for anything already built.
2. Write `docs/project-phases.md`:
   - Phases numbered `## Phase 1: Name`, sub-phases `### Phase 1.2: Name`, so they can be referenced exactly.
   - Each task: `- [ ]` checkbox, the user stories it covers (US numbers), and the tests that prove it (feature tests, plus browser tests for user-facing flows, plus a 403 test for each protected action).
   - Mark tasks already done in the code with `- [x]`.
   - Order phases so each one ships something usable: foundations (roles, permissions, base layout) first.
3. Ask the user to review. From here, each phase item becomes an OpenSpec change: `/opsx:propose "Phase 1.1: ..."`.
