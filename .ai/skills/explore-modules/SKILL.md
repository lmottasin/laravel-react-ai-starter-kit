---
name: explore-modules
description: Study the parts of the codebase a task touches before planning it. Use before proposing a non-trivial change, or when asked how something currently works across several areas.
argument-hint: "<task description>"
---

# Explore modules

Understand the code involved in: $ARGUMENTS

1. Split the question into 2 to 4 focused areas (for example: models and relationships, controllers and policies, React pages, migrations and existing tests).
2. Launch one Explore subagent per area in a single message so they run in parallel. They only read. Ask each for file paths and short excerpts, not whole files.
3. Combine the results into:
   - How it works today (with `path:line` references)
   - Where the change plugs in
   - Database changes needed
   - Existing tests that cover the area (so the build step can run exactly those)
   - Recommended approach and open questions for `/grill-me`

Keep the summary short. Do not edit any files.
