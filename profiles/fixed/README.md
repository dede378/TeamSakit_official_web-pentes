# Fixed profile

This directory documents the intended secure baseline. The current running app uses prepared statements for article lookup, context-aware HTML output encoding, a default-off lab switch, and environment-based secrets.

Before calling the fixed profile complete, add tests for authentication, authorization, CSRF, session settings, redirect allow-lists, and every scenario in `../../docs/SCENARIOS.md`. Do not rely on this note as proof that every security control has been implemented.
