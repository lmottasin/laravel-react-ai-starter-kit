#!/bin/bash
# PreToolUse: block code edits on main. Work happens on a feature or fix branch,
# created by /feature (or a fix branch for one-file fixes). Docs and specs may change on main.
file=$(jq -r '.tool_input.file_path // .tool_input.notebook_path // empty')
[ -n "$file" ] || exit 0

cd "$CLAUDE_PROJECT_DIR" || exit 0
rel="${file#"$CLAUDE_PROJECT_DIR"/}"

case "$rel" in
  app/*|bootstrap/*|config/*|database/*|lang/*|public/*|resources/*|routes/*|tests/*|composer.json|package.json|vite.config.ts) ;;
  *) exit 0 ;;
esac

branch=$(git branch --show-current 2>/dev/null)

if [ "$branch" = "main" ] || [ "$branch" = "master" ]; then
  echo "Code can't be edited on $branch. Use /feature \"<phase item>\" (it creates the branch), or for a one-file fix create a fix/<slug> branch first." >&2
  exit 2
fi

exit 0
