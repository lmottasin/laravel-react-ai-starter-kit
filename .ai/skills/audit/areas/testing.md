# Testing audit

- Critical paths without tests (auth, permissions, payments, uploads, AI features, MCP tools).
- Only the happy path tested: missing validation failures and 403 cases.
- Tests written only to raise coverage (no meaningful assertion).
- Hard-coded data instead of factories and states; repeated setup that belongs in `beforeEach` or a factory state.
- Side effects not faked: mail, notifications, queues, storage, HTTP, AI (`Ai::fake()`).
- Fragile assertions (`assertSee` on text that changes, asserting implementation details).
- User-facing flows without a browser test in `tests/Browser`.
- Leftover `dd()`, `dump()`, `->only()` or skipped tests.
