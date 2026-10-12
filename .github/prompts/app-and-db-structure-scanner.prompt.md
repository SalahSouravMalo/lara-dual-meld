---
description: Scan Laravel app and database structure
---

### App structure

- List the complete hierarchy of `app/` only.
- Include all files and directories as a hierarchy.
- For every class, list all methods with their complete signatures, including visibility, method name, parameter names and types, and return types.
- Include all methods regardless of visibility (public, protected, private), including methods without parameters or explicit return types.

### Database structure

- Scan the database migrations directory and infer the current database structure.
- Exclude Laravel default tables such as `cache`, `cache_locks`, `jobs`, `failed_jobs`, `sessions`, etc.
- List only table names and column names.

### Strict output format

Use exactly this format for database tables:

table1 (col1, col2, col3)
table2 (col1, col2, col3)

Do not include indexes, foreign keys, constraints, types, relationships, comments, or other details.

Keep the output concise and structured.
Do not modify any files or the database.
