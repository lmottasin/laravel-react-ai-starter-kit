---
name: project-describe
description: Kickoff step 1. Turn the user's own description of the project into docs/project-description.md with goals, users, scope and tech stack.
disable-model-invocation: true
---

# Project description

1. Ask the user to paste or write their description of the project (client brief, notes, anything). Don't rewrite their words away; keep the original text in a "Brief" section.
2. Read `composer.json`, `package.json` and `.ai/guidelines/house-rules.md` to fill in the tech stack as it really is.
3. Write `docs/project-description.md` with these sections:
   - Brief (the user's text, unchanged)
   - Goal (two or three sentences)
   - Users and roles
   - In scope / Out of scope (for the first release)
   - Tech stack (Laravel, Inertia React, PostgreSQL + pgvector, key packages)
   - Constraints (deadlines, hosting, compliance, budget) if known
   - Open questions
4. Ask the user to review it. The next step is `/user-stories`.
