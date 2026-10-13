# Deployment safety

The Compose file binds the web port to `127.0.0.1` on the host by default. Keep that binding for local practice.

For a normal public website:
1. Set `APP_MODE=fixed`.
2. Set `LAB_ENABLED=false`.
3. Use a managed database with TLS and a least-privilege application account.
4. Set secrets only in the hosting provider's environment-variable settings.
5. Do not publish the vulnerable profile or enable lab routes on an unrestricted public URL.

For private training, use a private network, VPN, IP allow-list, or an authenticated environment with no access to production data. Basic Auth is an additional gate, not a substitute for network isolation. Free hosting may sleep, restart, or lack persistent database storage; verify provider terms before selecting a service.
