# Test plan

Run tests only against the local Compose service with dummy data. The current milestone is a scaffold, so tests below are manual acceptance checks rather than an automated suite.

## Baseline
- With `LAB_ENABLED=false`, requests to `/labs/` return 404.
- With `LAB_ENABLED=true`, lab routes challenge for Basic Auth.
- With `APP_MODE=fixed`, L01 escapes HTML, L02 uses a prepared statement, L03 allows only known internal destinations, and L04 exposes only the explicitly public fixture.
- With `APP_MODE=vulnerable` on an isolated local-only environment, compare L01/L02/L04 behavior using synthetic values.
- Confirm database error details and credentials never appear in HTTP responses.
- Confirm Docker host port remains bound to 127.0.0.1.
