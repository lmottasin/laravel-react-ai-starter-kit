#!/bin/bash
# Stop: block finishing while changed PHP fails Pint or PHPStan.
# The full suite (composer test) runs in CI or via /goal.
input=$(cat)

# Already continuing because of this hook: let Claude stop and report instead of looping.
[ "$(jq -r '.stop_hook_active' <<<"$input")" = "true" ] && exit 0

cd "$CLAUDE_PROJECT_DIR" || exit 0

# Nothing changed in PHP: nothing to check.
git status --porcelain -- '*.php' 2>/dev/null | grep -q . || exit 0

out=$( { vendor/bin/pint --test --dirty && vendor/bin/phpstan analyse --no-progress --error-format=raw; } 2>&1 ) && exit 0

printf 'Fast checks failed. Fix these before finishing:\n%s\n' "$(tail -60 <<<"$out")" >&2
exit 2
