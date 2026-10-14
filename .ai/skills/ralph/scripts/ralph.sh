#!/usr/bin/env bash
# Ralph loop: one fresh Claude session per OpenSpec task until the change is done.
# Usage: ralph.sh <openspec-change-name>   (run from the project root, ideally in tmux)
set -uo pipefail

change="${1:?usage: ralph.sh <openspec-change-name>}"
max_rounds="${RALPH_MAX_ROUNDS:-25}"
dir="openspec/changes/$change"
tasks="$dir/tasks.md"
progress="$dir/progress.md"

[ -f "$tasks" ] || { echo "No $tasks found."; exit 1; }
[ "$(git branch --show-current)" != "main" ] || { echo "Refusing to run on main. Use /worktree or a feature branch."; exit 1; }
touch "$progress"

last_task=""
repeats=0

for round in $(seq 1 "$max_rounds"); do
    if grep -q '^## QUESTION' "$progress"; then
        echo "Paused: a question is waiting at the top of $progress. Answer it there (remove the QUESTION heading) and run again."
        exit 2
    fi

    task=$(grep -m1 -E '^[[:space:]]*- \[ \]' "$tasks" | sed -E 's/^[[:space:]]*- \[ \][[:space:]]*//')

    if [ -z "$task" ]; then
        if composer test > /tmp/ralph-composer-test.log 2>&1; then
            echo "Done: every task is ticked and composer test passes."
            exit 0
        fi
        task="Every task is ticked but composer test fails (see /tmp/ralph-composer-test.log). Make it pass."
    fi

    if [ "$task" = "$last_task" ]; then repeats=$((repeats + 1)); else repeats=0; fi
    if [ "$repeats" -ge 3 ]; then
        echo "Stopped: the same task did not finish in 3 rounds: $task"
        exit 3
    fi
    last_task="$task"

    echo "== Round $round/$max_rounds: $task"

    claude -p "You are one round of an autopilot loop for the OpenSpec change '$change'.
Read $dir/proposal.md, the specs in $dir/specs, $tasks, $progress and .ai/guidelines/house-rules.md.
Do ONLY this task: $task
Write the code and its tests, run the related tests and fix failures.
When the task is done and its tests pass, tick it in $tasks and append a short note to $progress (what you did, files touched, anything the next round must know).
If the task needs a decision the proposal and specs do not answer, do not guess: add a section starting with '## QUESTION' at the top of $progress explaining the choice and your recommendation, then stop.
Do not commit or push." --permission-mode acceptEdits

    if [ -n "$(git status --porcelain)" ]; then
        git add -A
        git commit -q -m "ralph($change): $task"
    fi
done

echo "Stopped: reached $max_rounds rounds."
exit 4
