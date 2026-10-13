# Scenario roadmap (16 levels)

These are the learning objectives. Do not mark a level complete until its route, expected behavior, reproducible test, and remediation are implemented and verified. Keep the intentionally vulnerable profile isolated and accessible only to authorized learners.

| Level | Topic | Safe test boundary | Fixed-version goal |
|---|---|---|---|
| L01 | Reflected XSS | Dummy query string and browser context | Context-aware output encoding |
| L02 | Error-based SQL injection | Seed-only database and disposable records | PDO prepared statements |
| L03 | Open redirect | Internal allow-listed destinations only | Allow-list redirect targets |
| L04 | Sensitive-file discovery | Synthetic fixture files only | Deny private files; no indexing |
| L05 | Stored XSS | Dummy comments, no real users | Encode at output; sanitize where appropriate |
| L06 | IDOR | Two synthetic users | Object-level authorization |
| L07 | Path traversal | Fixture directory only | Canonical path containment |
| L08 | CSRF | Local forms and dummy sessions | CSRF tokens and SameSite cookies |
| L09 | SQLi filter bypass concepts | Synthetic dataset only | Parameterized SQL, not blacklist filters |
| L10 | Attribute-context XSS | Dummy profile field | Attribute/context encoding |
| L11 | Inconsistent authorization | Synthetic records | Centralized authorization checks |
| L12 | SSRF concepts | Local mock service only; no outbound requests | Destination allow-list and network egress limits |
| L13 | SSTI concepts | Non-executing template simulator first | No user-controlled template evaluation |
| L14 | Blind SQLi concepts | Boolean responses over dummy records | Parameterized SQL and uniform errors |
| L15 | Session/token validation | Dummy tokens only | Signature, expiry, issuer, audience, rotation checks |
| L16 | Multi-step chain | Local-only seeded app | Defense in depth and regression tests |

## Required test notes for every level

1. Scope and setup.
2. Request made against the local lab.
3. Expected vulnerable behavior (only when the vulnerable profile is enabled).
4. Evidence captured without real credentials or personal data.
5. Fixed-version result and remediation.
6. Automated regression test.

The current scaffold displays the roadmap but does not yet implement all 16 exploit behaviors. Add and test levels incrementally; do not expose unfinished or vulnerable routes on a public host.
