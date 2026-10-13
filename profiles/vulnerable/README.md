# Vulnerable training profile

This directory is reserved for deliberately vulnerable training handlers. It is not implemented yet. Add each level as an explicit, isolated handler using synthetic data only, and ensure the handlers cannot run when `LAB_ENABLED=false`.

Rules:
- Never route the vulnerable profile on a public production deployment.
- Do not make arbitrary outbound HTTP requests or read arbitrary host files.
- SSRF/path exercises must use local mock endpoints and synthetic fixtures.
- Do not use real credentials, personal data, or production databases.
- Each level must include a reproducible local test and a fixed counterpart before it is considered complete.
