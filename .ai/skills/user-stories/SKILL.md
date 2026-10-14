---
name: user-stories
description: Kickoff step 2. Turn docs/project-description.md into numbered user stories with acceptance criteria in docs/user-stories.md, after asking clarifying questions.
disable-model-invocation: true
---

# User stories

1. Read `docs/project-description.md`.
2. Ask clarifying questions first, the `/grill-me` way: multiple choice, your recommendation first, notes allowed. Focus on roles, permissions, data, limits and what's out of scope. This step often uncovers decisions that would otherwise surface mid-build.
3. Write `docs/user-stories.md`:
   - An overview listing roles and feature areas.
   - Areas numbered `## 1. Area name`, stories numbered `### US-1.1: Title`.
   - Each story: **As a** role, **I want to** action, **So that** benefit; an **Acceptance criteria** checklist (`- [ ]`, testable, one behaviour each, including who must be refused); an **Expected result** line.
   - Keep the scope to what the description asks for. Mark nice-to-haves as "Later".
4. Ask the user to review. The next step is `/choose-packages`.
