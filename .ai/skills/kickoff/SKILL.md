---
name: kickoff
description: Plan a whole new project in one run - description, user stories, packages, database schema and phases in docs/ - pausing only for the user's approval of each document.
disable-model-invocation: true
---

# Kickoff

Run the five kickoff skills in order. For each step, read that skill's `SKILL.md` in the sibling folder (`${CLAUDE_SKILL_DIR}/../<name>/SKILL.md`) and follow it.

1. `project-describe` → `docs/project-description.md`
2. `user-stories` → `docs/user-stories.md`
3. `choose-packages` → installs the chosen packages
4. `db-schema` → `docs/database-schema.md`
5. `project-phases` → `docs/project-phases.md`

## Gates

After each document, show a short summary (not the whole file) and ask with AskUserQuestion: **Approve** (Recommended), **Change something** (the user's notes say what), or **Stop here**. Apply requested changes and ask again before moving on. Never skip a gate; every later step builds on the earlier ones.

## Finish

Commit the `docs/` files (the user confirms the commit). Then suggest the first feature: `/feature "Phase 1.1: ..."` with the first unticked item from `docs/project-phases.md`.
