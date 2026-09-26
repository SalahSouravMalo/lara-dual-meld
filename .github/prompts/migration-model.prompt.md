---
description: Create or update Laravel migration + model
---

You are helping me create or update a Laravel migration and its related Eloquent model.

### Migration rules

- Follow Laravel latest conventions.
- If a column definition has 3 or more method chains, put each method on a new line.
- Do NOT put an empty line after each column definition.
- Do NOT use `->onDelete('cascade')`, `->onDelete('set null')`, or any onDelete / onUpdate constraints. I handle referential integrity in the application layer.
- Keep the migration clean and minimal.

### Model rules

- Always use the latest Laravel PHP attribute syntax for fillable:
  `#[Fillable(['column1', 'column2', ...])]`
- Create, update, or remove columns from the `#[Fillable]` list as needed.
- Adjust relationships (hasMany, belongsTo, etc.) correctly.
- Add or update `$casts` when necessary.
- Do not add unnecessary code or comments.
