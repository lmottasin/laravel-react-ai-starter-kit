---
name: audit
description: Health check of the codebase in one area (structure, eloquent, permissions, testing, queues, api, production) or all of them in parallel. Read-only; produces a ranked report of suggestions.
argument-hint: "<structure|eloquent|permissions|testing|queues|api|production|all>"
disable-model-invocation: true
---

# Audit

Audit area: $ARGUMENTS

- One area: read `areas/<area>.md` in this skill's folder and follow it.
- `all`: launch one read-only subagent per area file in a single message (they run in parallel). Give each the full text of its area file and the report format below. Then merge their findings: drop duplicates, re-check each finding against the code to remove false alarms, and sort by severity.

Never edit code during an audit. Scan `app/`, `routes/`, `config/`, `database/`, `resources/js/` and `tests/`; skip `vendor/`, `node_modules/`, `storage/` and generated Wayfinder files.

## Ground rules

- Findings are suggestions. Structure is partly team preference; our house rules (`.ai/guidelines/house-rules.md`) are the reference, not generic advice.
- Read enough of each file to understand it before flagging. Don't flag simple CRUD or short methods.
- Respect consistent project patterns (for example, Actions everywhere is intentional here).

## Report format

1. **Summary**: number of findings and the two or three most important.
2. **Findings**: one table per area with columns Severity, File, Lines, Issue, Suggestion.
   - High: real bugs or security risks.
   - Medium: problems that hurt clarity, reuse or safety.
   - Low: local cleanups.
3. **Done well**: patterns the project already gets right.

Big findings can become OpenSpec changes so the fix goes through the normal flow.
