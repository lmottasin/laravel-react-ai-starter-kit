---
name: grill-me
description: Interview the user before building anything non-trivial. Use when the user says "grill me", asks to plan or spec a feature, or when a request leaves important decisions open. Asks one multiple-choice question at a time with a recommended answer.
argument-hint: "[feature or idea]"
---

# Grill me

Interview the user about $ARGUMENTS until every decision that affects the build is settled. Walk down the decision tree: settle the questions that other questions depend on first.

## How to ask

- Use the AskUserQuestion tool. Ask 1 to 4 related questions per call, never a wall of text.
- Every question is multiple choice with 2 to 4 options.
- Put your suggestion first and end its label with "(Recommended)". Its description gives the reason in one line.
- The tool always adds an "Other" option for a custom answer, and the user can attach notes to any answer. Read the notes; they often change the next question.
- If the codebase, `docs/` or `openspec/specs/` can answer a question, look it up instead of asking.
- Don't ask about things the house rules already decide (`.ai/guidelines/house-rules.md`).

## What to cover

Goal and users, roles and permissions (who can do what, including who must get a 403), data and relationships, edge cases and limits, what is out of scope, what must not change, and how we'll know it's done.

## Finish

Summarise the decisions as a short list (decision, chosen option, reason, notes). If an OpenSpec change exists or is about to be proposed, put them in its proposal; otherwise show them and offer to start `/opsx:propose`.
