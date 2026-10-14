---
name: ralph
description: Autopilot. Work through an approved OpenSpec change's task list unattended, starting a fresh Claude session for each task, until every task is ticked and composer test passes. Use only when tests are solid.
argument-hint: "<openspec-change-name>"
disable-model-invocation: true
---

# Ralph loop

Each round, a fresh Claude reads the change's `tasks.md` and `progress.md`, does the next unticked task, records it, and exits. Fresh context every round keeps quality from degrading on long task lists.

## Before starting (check all, or stop and tell the user)

- The change `$ARGUMENTS` exists in `openspec/changes/` and the user approved its proposal.
- You are in a dedicated worktree or feature branch, not `main` (use `/worktree` first).
- `composer test` passes right now.
- The working tree is clean (`git status`).

## Start

```bash
tmux new -s ralph-$ARGUMENTS "${CLAUDE_SKILL_DIR}/scripts/ralph.sh $ARGUMENTS"
```

Tell the user: watch with `tmux attach -t ralph-$ARGUMENTS`; stop with Ctrl-C inside it. The loop stops by itself when all tasks are ticked and `composer test` passes, after 25 rounds, or when the same task fails 3 times. If a round needs a decision the spec doesn't answer, it writes the question at the top of `progress.md` and the loop pauses until the user answers there.

## Afterwards

Review the commits, run `/review-pr`, and the user merges. Ralph never pushes.
