---
name: n98-magerun
description: >
  Run and explain n98-magerun, the CLI for Magento 1 / OpenMage LTS shops (this skill covers the PHP 8 fork,
  n98-magerun 4.x for PHP 8.2 - 8.5 and OpenMage 20.10+). Use it whenever a task on a Magento 1 or OpenMage
  installation can be done from the command line: cache, indexes, config values, database dumps and imports,
  admin users, customers, cron jobs, setup/upgrade scripts, modules, rewrites, observers, EAV attributes, themes,
  translations, media, maintenance mode, installing a shop. Also use it when the user mentions "magerun", "n98",
  "n98-magerun.phar" or asks how to do something on an OpenMage shop via CLI, and before writing a PHP script or
  raw SQL for something magerun already does. Not for Magento 2 (that is n98-magerun2).
---

# n98-magerun (Magento 1 / OpenMage LTS)

n98-magerun is a Symfony Console application that bootstraps the Magento installation it runs in and
offers ~100 commands for everyday shop administration and development.

This skill targets the PHP 8 fork: https://github.com/MarcoKriechbaumer/n98-magerun-php8

- n98-magerun 4.x requires PHP 8.2 - 8.5
- supported shops: OpenMage LTS 20.10.0 and newer (PHP 8.5 needs OpenMage 20.16+)
- the full, generated list of commands, arguments and options is in `references/commands.md`.
  Read it before using a command whose exact syntax is not shown below.

## Running magerun

1. Find the executable, first match wins:
   - `n98-magerun.phar` or `n98-magerun` in the `PATH`
   - `./n98-magerun.phar` in the shop root or project root
   - `vendor/bin/n98-magerun` (Composer installation)
   If none exists, tell the user and point to the GitHub releases of the fork instead of downloading anything.
2. Run it from the Magento root (the folder with `app/Mage.php`) or pass `--root-dir=/path/to/magento`.
   magerun searches the current folder and its parents for the Magento root.
3. Always add `--no-interaction` (`-n`) when running commands yourself. Commands with missing arguments
   otherwise wait for input. Pass all required values as arguments instead.
4. Running as the `root` user prints a warning; add `--skip-root-check` only in containers or CI, and prefer
   running as the web server user so created files (cache, logs) get the right owner.
5. Use `--format=json` (or `csv`, `xml`) on list commands whenever the output is parsed. Only commands that
   show an `--format` option in `references/commands.md` support it.
6. Check the version with `n98-magerun --version` if a command from this skill is missing: the fork removed
   commands for Magento Enterprise and Magento Connect (`extension:*`, `giftcard:*`, `index:*:mview`,
   `admin:user:lock*`, `cms:banner:toggle`, `cms:page:publish`, `shell`).

## Safety rules

Read-only commands (`*:list`, `*:info`, `*:view`, `config:get`, `config:search`, `db:info`, `db:status`,
`db:variables`, `sys:check`, `sys:info`, `dev:module:rewrite:conflicts`) can be run freely.

Ask the user before running anything that changes data, and say what it changes:

| Risk | Commands |
|---|---|
| Deletes data, not recoverable without a backup | `db:drop`, `db:import` (overwrites), `uninstall`, `customer:delete`, `admin:user:delete`, `eav:attribute:remove`, `sys:setup:remove`, `config:delete --all` |
| Changes the running shop | `config:set`, `config:delete`, `cache:disable`, `sys:maintenance --on`, `dev:*` toggles, `dev:module:disable`, `sys:setup:run`, `sys:setup:change-version`, `sys:cron:run`, `index:reindex:all` (heavy on big catalogs), `media:cache:image:clear` (images are regenerated on the next request) |
| Creates test data | `customer:create:dummy`, `category:create:dummy`, `eav:attribute:create-dummy-values` |

- Before changes on a shop with real data, create a backup: `n98-magerun db:dump -n --strip="@stripped" backup.sql`.
- Never run the dev toggles (`dev:template-hints`, `dev:translate:shop`, `dev:profiler`, `dev:log:db`) on a
  production store without being asked to; they are visible to visitors or slow the shop down.
- `db:dump --strip="@development"` also strips the **admin** tables: the dump contains no admin users.
- `db:query` runs any SQL. Prefer a magerun command when one exists; for manual SQL, show the statement first.

## Which command for which task

| Task | Command |
|---|---|
| Shop version, paths, counts | `sys:info`, `sys:info version` |
| Health check (PHP, file permissions, security) | `sys:check` |
| Stores / websites / base URLs | `sys:store:list`, `sys:website:list`, `sys:store:config:base-url:list` |
| Clean or flush caches | `cache:clean [type...]`, `cache:flush`, `cache:dir:flush`, `cache:list` |
| Enable / disable a cache type | `cache:enable <code>`, `cache:disable <code>` |
| Inspect cache entries | `cache:report`, `cache:view <id>` |
| Indexes | `index:list`, `index:reindex <code[,code]>`, `index:reindex:all` |
| Read / write / delete config | `config:get <path>`, `config:set <path> <value>`, `config:delete <path>`, `config:search <text>` |
| Merged XML config | `config:dump [xpath]` |
| Maintenance mode | `sys:maintenance --on`, `sys:maintenance --off` |
| Setup / upgrade scripts | `sys:setup:compare-versions`, `sys:setup:run`, `sys:setup:incremental`, `sys:setup:change-version`, `sys:setup:remove` |
| Cron | `sys:cron:list`, `sys:cron:history`, `sys:cron:run <job>` |
| Modules | `dev:module:list`, `dev:module:enable <name>`, `dev:module:disable <name>`, `dev:module:dependencies:on`, `dev:module:dependencies:from` |
| Rewrites and observers | `dev:module:rewrite:list`, `dev:module:rewrite:conflicts`, `dev:module:observer:list <area>` |
| Resolve a class alias | `dev:class:lookup <type> <alias>` (e.g. `model catalog/product`) |
| New module skeleton | `dev:module:create <Vendor> <Module> [codePool]` |
| Themes | `dev:theme:list`, `dev:theme:info`, `dev:theme:duplicates <theme> [originalTheme]` |
| Debug toggles | `dev:template-hints`, `dev:template-hints-blocks`, `dev:translate:shop`, `dev:translate:admin`, `dev:profiler`, `dev:log`, `dev:log:db`, `dev:symlinks`, `dev:merge-css`, `dev:merge-js` |
| Logs and reports | `dev:log:size [file]`, `dev:report:count` |
| PHP shell with Magento loaded | `dev:console` |
| EAV attributes | `eav:attribute:list`, `eav:attribute:view <entityType> <code>` |
| Admin users | `admin:user:list`, `admin:user:create`, `admin:user:change-password`, `admin:user:change-status`, `admin:user:delete` |
| Customers | `customer:list [search]`, `customer:info <email>`, `customer:create`, `customer:change-password`, `customer:delete` |
| CMS blocks | `cms:block:list`, `cms:block:toggle <id>` |
| Database | `db:info`, `db:dump`, `db:import`, `db:query`, `db:console`, `db:status`, `db:variables`, `db:maintain:check-tables`, `db:create`, `db:drop` |
| Media | `media:dump [file]`, `media:cache:image:clear`, `media:cache:jscss:clear` |
| Translations | `dev:translate:set <string> <translation> [store]`, `dev:translate:export <locale> [file]` |
| Batch of commands | `script <file.magerun>` |
| Install / remove a shop | `install`, `uninstall` |
| local.xml | `local-config:generate` |

## Workflows

All commands below exist with exactly this syntax in n98-magerun 4.x.

### After a deployment

```bash
n98-magerun -n sys:maintenance --on
n98-magerun -n sys:setup:run
n98-magerun -n cache:flush
n98-magerun -n index:reindex:all
n98-magerun -n sys:maintenance --off
```

Check pending setup scripts first with `n98-magerun sys:setup:compare-versions --errors-only`.

### Database dump for a staging or developer system

```bash
# without customer, order, admin, log and session data
n98-magerun -n db:dump --strip="@development" --compression=gz dev-dump.sql

# keep admin users, strip only logs, sessions and temporary data
n98-magerun -n db:dump --strip="@stripped" --compression=gz dump.sql

# only print the mysqldump command (e.g. for a cron job)
n98-magerun db:dump --only-command --strip="@stripped" dump.sql
```

The filename is a positional argument (there is no `--filename` option). `--add-time=suffix` or `prefix` adds a
timestamp. Strip groups: `@admin`, `@log`, `@sessions`, `@dataflowtemp`, `@importexporttemp`, `@stripped`,
`@sales`, `@customers`, `@trade`, `@newsletter`, `@emails`, `@search`, `@idx`, `@development`. Table names and
wildcards work too: `--strip="@stripped sales_flat_quote*"`.

### Import a dump

```bash
n98-magerun -n db:import --compression=gz dump.sql.gz
n98-magerun -n db:import --drop-tables dump.sql   # drop all tables first
```

### Point a copied database to a new domain

```bash
n98-magerun -n config:set web/unsecure/base_url "https://dev.example.com/"
n98-magerun -n config:set web/secure/base_url "https://dev.example.com/"
n98-magerun -n cache:flush
```

Scopes: `--scope=default` (default), `--scope=websites --scope-id=<id>`, `--scope=stores --scope-id=<id>`.
`config:get` accepts wildcards, e.g. `config:get "web/*/base_url"`.

### Admin user for local development

```bash
n98-magerun -n admin:user:create <username> <email> <password> <firstname> <lastname> Administrators
n98-magerun -n admin:user:change-password <username> <new-password>
```

All values are positional arguments.

### Find why a class is not used

```bash
n98-magerun dev:class:lookup model catalog/product
n98-magerun dev:module:rewrite:conflicts
n98-magerun dev:module:rewrite:list --format=json
```

### Debug the frontend of one store

```bash
n98-magerun -n dev:template-hints --on <store_code>
n98-magerun -n dev:log --on --global
# ... reproduce, then switch both off again
n98-magerun -n dev:template-hints --off <store_code>
```

### Run one cron job now

```bash
n98-magerun sys:cron:list
n98-magerun -n sys:cron:run <job_code>
```

### Run PHP with Magento loaded

`dev:console` opens an interactive shell (psysh), which you cannot drive yourself. For one-off code, pipe it in
or write a short script:

```bash
echo 'echo Mage::getVersion(), PHP_EOL;' | n98-magerun dev:console
```

### Scripts

A `.magerun` file contains one magerun command per line; lines starting with `!` run shell commands and
`${var}` placeholders are replaced (`${magento.root}`, `${magento.version}`, `${magerun.version}`,
`${php.version}`, `${script.file}`, `${script.dir}`, or own values via `-d name=value`).

```
# deploy.magerun
sys:maintenance --on
sys:setup:run
cache:flush
! echo "deployed on ${magento.root}"
sys:maintenance --off
```

```bash
n98-magerun -n script --stop-on-error deploy.magerun
```

## Configuration

magerun merges YAML config files, later ones win:

- `config.yaml` bundled with magerun
- `~/.n98-magerun.yaml` (user)
- `<magento-root>/app/etc/n98-magerun.yaml` (project)

Typical use: own strip groups for `db:dump`, e.g.

```yaml
commands:
  N98\Magento\Command\Database\DumpCommand:
    table-groups:
      - id: myproject
        description: Import tables of the shop
        tables: "import_* @stripped"
```

Add `--skip-config` to run without user and project config.

## Troubleshooting

| Message | Meaning / fix |
|---|---|
| `Magento folder could not be detected` | Not inside a Magento root. `cd` into it or pass `--root-dir`. |
| `It's not recommended to run n98-magerun as root user` | Run as the web server user, or `--skip-root-check` in containers. |
| `Folder /tmp/magento/var found, but not used in n98-magerun` | Informational, magerun uses the shop's `var` folder. |
| `Cannot initialize Magento ... SQLSTATE` | Database from `app/etc/local.xml` not reachable; check with `db:info`. |
| Command not defined | Check `n98-magerun list`; see the removed commands under "Running magerun". |
| `Your Composer dependencies require a PHP version ">= 8.2.0"` | n98-magerun 4.x needs PHP 8.2+; check `php -v` of the CLI binary used. |

## Updating magerun

`n98-magerun self-update` replaces the phar with the latest release of the fork (`--dry-run` only checks).
