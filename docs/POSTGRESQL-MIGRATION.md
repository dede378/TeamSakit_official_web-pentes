# MySQL to PostgreSQL / Supabase notes

Supabase is PostgreSQL, not MySQL. A migration needs code and schema changes, not just new environment-variable values.

- Enable PHP's `pdo_pgsql` extension instead of `pdo_mysql`.
- Replace the DSN with `pgsql:host=...;port=5432;dbname=...;sslmode=require` as appropriate for the provider.
- Review MySQL `AUTO_INCREMENT`, `UNSIGNED`, `ENUM`, and timestamp definitions; use PostgreSQL identity columns, suitable numeric types, and check constraints/enums.
- Replace MySQL-specific constructs such as `ON DUPLICATE KEY UPDATE` / `INSERT IGNORE` with PostgreSQL equivalents such as `ON CONFLICT`.
- Review boolean handling, identifier quoting, collation/case sensitivity, JSON types, and SQL syntax.
- Keep credentials in environment variables and require TLS for hosted databases.
- Re-run every integration test and all lab/fixed comparisons after migration. Vulnerability behavior may differ by database engine.
