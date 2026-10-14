#!/bin/bash
# PostToolUse: format a PHP file right after Claude edits it.
file=$(jq -r '.tool_input.file_path // empty')

case "$file" in
  *.php) [ -f "$file" ] && "$CLAUDE_PROJECT_DIR/vendor/bin/pint" --quiet "$file" ;;
esac

exit 0
