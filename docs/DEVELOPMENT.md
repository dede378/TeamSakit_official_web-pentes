# Development checklist

- [ ] Copy `.env.example` to `.env` and replace all example passwords.
- [ ] Confirm `APP_MODE=fixed` and `LAB_ENABLED=false` for normal use.
- [ ] Run locally with Docker Compose; service binding is loopback-only by default.
- [ ] Verify the homepage and database seed content load.
- [x] Add starter handlers for L01-L04 with safe fixture boundaries (L03 is internal-only simulation).
- [ ] Add automated regression tests for L01-L04.
- [ ] Implement L05 onward one level at a time in the isolated training profile.
- [ ] Add a repeatable test and remediation notes for every level.
- [ ] Verify lab requests return 404 when disabled and require Basic Auth when enabled.
- [ ] Keep vulnerable services off public hosting; never use production or personal data.
- [ ] Do not commit `.env`, access tokens, database dumps, or scan output.
