---
name: choose-packages
description: Kickoff step 3. Suggest Laravel packages that fit the user stories, using web search, and record the choices.
disable-model-invocation: true
---

# Choose packages

1. Read `docs/project-description.md`, `docs/user-stories.md` and `composer.json` (don't suggest what's installed).
2. Search the web for packages that fit specific stories. Each suggestion must:
   - support the project's Laravel version,
   - have a release or commit in the last 12 months,
   - preferably have 100+ GitHub stars,
   - not duplicate something Laravel already does well.
3. Present at most 10, as a table: package, stories it serves (US numbers), why, stars, last release, risk. Then ask which to install (multiple choice, your picks first).
4. After the user chooses: install them, run `php artisan boost:update` so Boost picks up any AI guidance they ship (read that guidance first), and add them to the Tech stack in `docs/project-description.md`.
5. The next step is `/db-schema`.
