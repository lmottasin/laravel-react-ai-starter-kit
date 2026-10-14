---
name: verify-feature
description: Check a finished feature in a real browser with Playwright CLI as each role, then write end-to-end Pest browser tests for the same flows. Use after implementing a user-facing feature and before review.
argument-hint: "[openspec change name]"
---

# Verify feature

Run this after the code for $ARGUMENTS is written and its feature tests pass.

## 1. Walk through it in a real browser

- Build assets (`npm run build`) and make sure the app is served (`composer dev`, or the project's local URL).
- Seed or create one user per relevant role (for example admin and viewer) with the factory and the role Enums.
- With the playwright-cli skill, for each role: log in, go through every acceptance criterion of the change, and confirm forbidden actions are hidden in the UI and return 403 when called directly.
- Check for JavaScript errors and broken layouts.
- Save screenshots to `openspec/changes/<change>/screenshots/` with names like `admin-create-story.png`.

## 2. Turn the flows into end-to-end tests

- Add Pest browser tests in `tests/Browser/` that repeat the main flows: the happy path per role and at least one permission failure.
- Use `data-test` attributes for buttons and `->assertNoJavaScriptErrors()`.
- Run `php artisan test --compact tests/Browser`.

## 3. Report

List each criterion with pass or fail and its screenshot. If anything failed, fix it and verify again before moving on to review.
