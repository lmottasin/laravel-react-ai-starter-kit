# Queues audit

- Slow work done during the request (emails, AI calls, embeddings, file processing, external APIs) instead of a queued job.
- Jobs that break or duplicate work when retried (not idempotent); missing `ShouldBeUnique` where duplicates matter.
- Missing `tries`, `backoff`, `failed()` handling or timeouts on important jobs.
- Jobs doing too much; split into a chain or batch.
- Whole models or large data serialised into the payload instead of IDs.
- Jobs dispatched inside a transaction without `afterCommit`.
- Queued work with no test (`Queue::fake()` plus a test of the job itself).
