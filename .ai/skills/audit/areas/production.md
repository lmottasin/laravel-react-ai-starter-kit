# Production readiness audit

Run before every release.

- Leftover debug code: `dd()`, `dump()`, `ray()`, `console.log`, `var_dump`.
- `env()` outside `config/`.
- Secrets or API keys in code, config defaults or the repository.
- `APP_DEBUG`, mail, queue, cache and session settings that only make sense locally.
- Seeders or factories that would run or create demo data in production.
- Missing `php artisan config:cache`, `route:cache`, `view:cache` in the deploy steps; closures that block route caching.
- Scheduled tasks (for example `activitylog:clean`) defined but no scheduler running.
- Missing database indexes for the main pages; queries without pagination.
- Error tracking and logging configured.
