# n98-magerun command reference

Generated from `n98-magerun list --format=json` of n98-magerun 4.0.0. Do not edit, run `res/claude-skill/generate-reference.php` instead.

## Global options

Available for every command:

| Option | Description |
|---|---|
| `--help, -h` | Display help for the given command. When no command is given display help for the list command |
| `--quiet, -q` | Do not output any message |
| `--verbose, -v\|-vv\|-vvv` | Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug |
| `--version, -V` | Display this application version |
| `--ansi` | Force (or disable --no-ansi) ANSI output |
| `--no-ansi` | Negate the "--ansi" option |
| `--no-interaction, -n` | Do not ask any interactive question |
| `--root-dir` | Force magento root dir. No auto detection |
| `--skip-config` | Do not load any custom config. |
| `--skip-root-check` | Do not check if n98-magerun runs as root |
| `--developer-mode` | Instantiate Magento in Developer Mode |

## Commands without namespace

### install

Install magento

```
n98-magerun install [--magentoVersion [MAGENTOVERSION]] [--magentoVersionByName [MAGENTOVERSIONBYNAME]] [--installationFolder [INSTALLATIONFOLDER]] [--dbHost [DBHOST]] [--dbUser [DBUSER]] [--dbPass [DBPASS]] [--dbName [DBNAME]] [--dbPort [DBPORT]] [--installSampleData [INSTALLSAMPLEDATA]] [--useDefaultConfigParams [USEDEFAULTCONFIGPARAMS]] [--baseUrl [BASEURL]] [--replaceHtaccessFile [REPLACEHTACCESSFILE]] [--noDownload] [--only-download] [--forceUseDb] [--composer-use-same-php-binary]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--magentoVersion` | optional | Magento version |  |
| `--magentoVersionByName` | optional | Magento version name instead of order number |  |
| `--installationFolder` | optional | Installation folder |  |
| `--dbHost` | optional | Database host |  |
| `--dbUser` | optional | Database user |  |
| `--dbPass` | optional | Database password |  |
| `--dbName` | optional | Database name |  |
| `--dbPort` | optional | Database port | 3306 |
| `--installSampleData` | optional | Install sample data |  |
| `--useDefaultConfigParams` | optional | Use default installation parameters defined in the yaml file |  |
| `--baseUrl` | optional | Installation base url |  |
| `--replaceHtaccessFile` | optional | Generate htaccess file (for non vhost environment) |  |
| `--noDownload` | flag | If set skips download step. Used when installationFolder is already a Magento installation that has to be installed on the given database. |  |
| `--only-download` | flag | Downloads (and extracts) source code |  |
| `--forceUseDb` | flag | If --forceUseDb passed, force to use given database if it already exists. |  |
| `--composer-use-same-php-binary` | flag | If --composer-use-same-php-binary passed, will invoke composer with the same PHP binary |  |

<details><summary>Help</summary>

```
* Download Magento by a list of git repos and zip files (mageplus, 
  magelte, official community packages).
* Try to create database if it does not exist.
* Installs Magento sample data if available (since version 1.2.0).
* Starts Magento installer
* Sets rewrite base in .htaccess file

Example of an unattended Magento CE 2.0.0 installation:

   $ n98-magerun2.phar install --dbHost="localhost" --dbUser="mydbuser" \
     --dbPass="mysecret" --dbName="magentodb" --installSampleData=yes \
     --useDefaultConfigParams=yes \
     --magentoVersionByName="magento-ce-2.0.0" \
     --installationFolder="magento" --baseUrl="http://magento.localdomain/"

Additionally, with --noDownload option you can install Magento working 
copy already stored in --installationFolder on the given database.

See it in action: https://youtu.be/WU-CbJ86eQc
```

</details>

### open-browser

Open current project in browser (experimental)

```
n98-magerun open-browser [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

### script

Runs multiple n98-magerun commands

```
n98-magerun script [-d|--define [DEFINE]] [--stop-on-error] [--] [<filename>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `filename` | no | Script file |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--define, -d` | optional, multiple | Defines a variable |  |
| `--stop-on-error` | flag | Stops execution of script on error |  |

<details><summary>Help</summary>

```
Example:

   # Set multiple config
   config:set "web/cookie/cookie_domain" example.com

   # Set with multiline values with "
"
   config:set "general/store_information/address" "First line
Second line
Third line"

   # This is a comment
   cache:flush


Optionally you can work with unix pipes.

   $ echo "cache:flush" | n98-magerun-dev script

   $ n98-magerun.phar script < filename

It is even possible to create executable scripts:

Create file `test.magerun` and make it executable (`chmod +x test.magerun`):

   #!/usr/bin/env n98-magerun.phar script

   config:set "web/cookie/cookie_domain" example.com
   cache:flush

   # Run a shell script with "!" as first char
   ! ls -l

   # Register your own variable (only key = value currently supported)
   ${my.var}=bar

   # Let magerun ask for variable value - add a question mark
   ${my.var}=?

   ! echo ${my.var}

   # Use resolved variables from n98-magerun in shell commands
   ! ls -l ${magento.root}/code/local

Pre-defined variables:

* ${magento.root}    -> Magento Root-Folder
* ${magento.version} -> Magento Version i.e. 1.7.0.2
* ${magento.edition} -> Magento Edition -> Community or Enterprise
* ${magerun.version} -> Magerun version i.e. 1.66.0
* ${php.version}     -> PHP Version
* ${script.file}     -> Current script file path
* ${script.dir}      -> Current script file dir

Variables can be passed to a script with "--define (-d)" option.

Example:

   $ n98-magerun.phar script -d foo=bar filename

   # This will register the variable ${foo} with value bar.

It's possible to define multiple values by passing more than one option.
```

</details>

### self-update

Updates n98-magerun.phar to the latest version.

Aliases: `selfupdate`

```
n98-magerun self-update [--unstable] [--dry-run]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--unstable` | flag | Not available, there are no unstable builds of this fork |  |
| `--dry-run` | flag | Tests if there is a new version without any update. |  |

<details><summary>Help</summary>

```
The self-update command checks the GitHub releases of
https://github.com/MarcoKriechbaumer/n98-magerun-php8 for newer versions
of n98-magerun and if found, installs the latest.

php n98-magerun.phar self-update
```

</details>

### uninstall

Uninstall magento (drops database and empties current folder or folder set via installationFolder)

```
n98-magerun uninstall [-f|--force] [--installationFolder [INSTALLATIONFOLDER]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--force, -f` | flag | Force |  |
| `--installationFolder` | optional | Folder where Magento is currently installed |  |

<details><summary>Help</summary>

```
**Please be careful: This removes all data from your installation.**
```

</details>

## admin

### admin:notifications

Toggles admin notifications

```
n98-magerun admin:notifications [--on] [--off]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |

### admin:user:change-password

Changes the password of a adminhtml user.

```
n98-magerun admin:user:change-password [<username> [<password>]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `username` | no | Username |  |
| `password` | no | Password |  |

### admin:user:change-status

Set active status of an adminhtml user. If no option is set the status will be toggled.

```
n98-magerun admin:user:change-status [--activate] [--deactivate] [--] [<id>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `id` | no | Username or Email |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--activate` | flag | Activate user |  |
| `--deactivate` | flag | Deactivate user |  |

### admin:user:create

Create admin user.

```
n98-magerun admin:user:create [<username> [<email> [<password> [<firstname> [<lastname> [<role>]]]]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `username` | no | Username |  |
| `email` | no | Email, empty string = generate |  |
| `password` | no | Password |  |
| `firstname` | no | Firstname |  |
| `lastname` | no | Lastname |  |
| `role` | no | Role |  |

### admin:user:delete

Delete the account of a adminhtml user.

```
n98-magerun admin:user:delete [-f|--force] [--] [<id>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `id` | no | Username or Email |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--force, -f` | flag | Force |  |

### admin:user:list

List admin users.

```
n98-magerun admin:user:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

## cache

### cache:clean

Clean magento cache

```
n98-magerun cache:clean [--reinit] [--no-reinit] [--] [<type>...]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `type...` | no | Cache type code like "config" |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--reinit` | flag | Reinitialise the config cache after cleaning |  |
| `--no-reinit` | flag | Don't reinitialise the config cache after flushing |  |

<details><summary>Help</summary>

```
Cleans expired cache entries.

If you would like to clean only one cache type use like:

   $ n98-magerun.phar cache:clean full_page

If you would like to clean multiple cache types at once use like:

   $ n98-magerun.phar cache:clean full_page block_html

If you would like to remove all cache entries use `cache:flush`

Options:
    --reinit Reinitialise the config cache after cleaning (Default)
    --no-reinit Don't reinitialise the config cache after cleaning
```

</details>

### cache:dir:flush

Flush (empty) Magento cache directory

```
n98-magerun cache:dir:flush
```

<details><summary>Help</summary>

```
The default cache backend is the files cache in Magento. The default
directory of that default cache backend is the directory "var/cache"
within the Magento web-root directory (should be blocked from external
access).

The cache:dir:flush Magerun command will remove all files within that
directory. This is currently the most purist form to reset default
caching configuration in Magento.

Flushing the cache directory can help to re-initialize the whole Magento
application after it got stuck in cached configuration like a half-done
cache initialization, old config data within the files cache and similar.
```

</details>

### cache:disable

Disables magento caches

```
n98-magerun cache:disable [<code>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `code` | no | Code of cache (Multiple codes sperated by comma) |  |

### cache:enable

Enables magento caches

```
n98-magerun cache:enable [<code>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `code` | no | Code of cache (Multiple codes sperated by comma) |  |

### cache:flush

Flush magento cache storage

```
n98-magerun cache:flush [--reinit] [--no-reinit]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--reinit` | flag | Reinitialise the config cache after flushing |  |
| `--no-reinit` | flag | Don't reinitialise the config cache after flushing |  |

<details><summary>Help</summary>

```
Flush the entire cache.

   $ n98-magerun.phar cache:flush [--reinit --no-reinit]

Options:
    --reinit Reinitialise the config cache after flushing (Default)
    --no-reinit Don't reinitialise the config cache after flushing
```

</details>

### cache:list

Lists all magento caches

```
n98-magerun cache:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### cache:report

View inside the cache

```
n98-magerun cache:report [-t|--tags] [-m|--mtime] [--filter-id [FILTER-ID]] [--filter-tag [FILTER-TAG]] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--tags, -t` | flag | Output tags |  |
| `--mtime, -m` | flag | Output last modification time |  |
| `--filter-id` | optional | Filter output by ID (substring) |  |
| `--filter-tag` | optional | Filter output by TAG (separate multiple tags by comma) |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### cache:view

Prints a cache entry

```
n98-magerun cache:view [--unserialize] [--] <id>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `id` | yes | Cache-ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--unserialize` | flag | Unserialize output |  |

## category

### category:create:dummy

Create a dummy category

```
n98-magerun category:create:dummy [<store-id> [<category-number> [<children-categories-number> [<category-name-prefix>]]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store-id` | no | Id of Store to create categories (default: 1) |  |
| `category-number` | no | Number of categories to create (default: 1) |  |
| `children-categories-number` | no | Number of children for each category created (default: 0 - use '-1' for random from 0 to 5) |  |
| `category-name-prefix` | no | Category Name Prefix (default: 'My Awesome Category') |  |

## cms

### cms:block:list

List all cms blocks

```
n98-magerun cms:block:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### cms:block:toggle

Toggle a cms block

```
n98-magerun cms:block:toggle <block_id>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `block_id` | yes | Block ID or Identifier |  |

## config

### config:delete

Deletes a store config item

```
n98-magerun config:delete [--scope [SCOPE]] [--scope-id [SCOPE-ID]] [--force] [--all] [--] <path>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `path` | yes | The config path |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--scope` | optional | The config value's scope (default, websites, stores) | default |
| `--scope-id` | optional | The config value's scope ID | 0 |
| `--force` | flag | Allow deletion of non-standard scope-id's for websites and stores |  |
| `--all` | flag | Delete all entries by path |  |

<details><summary>Help</summary>

```
To delete all entries of a path you can set the option --all.
```

</details>

### config:dump

Dump merged xml config

```
n98-magerun config:dump [<xpath>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `xpath` | no | XPath to filter XML output |  |

<details><summary>Help</summary>

```
Dumps merged XML configuration to stdout. Useful to see all the XML.
You can filter the XML with first argument.

Examples:

  Config of catalog module

   $ n98-magerun.phar config:dump global/catalog

   See module order in XML

   $ n98-magerun.phar config:dump modules

   Write output to file

   $ n98-magerun.phar config:dump > extern_file.xml
```

</details>

### config:get

Get a core config item

```
n98-magerun config:get [--scope SCOPE] [--scope-id SCOPE-ID] [--decrypt] [--update-script] [--magerun-script] [--format [FORMAT]] [--] [<path>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `path` | no | The config path |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--scope` | required | The config value's scope (default, websites, stores) |  |
| `--scope-id` | required | The config value's scope ID |  |
| `--decrypt` | flag | Decrypt the config value using local.xml's crypt key |  |
| `--update-script` | flag | Output as update script lines |  |
| `--magerun-script` | flag | Output for usage with config:set |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
If path is not set, all available config items will be listed.
The path may contain wildcards (*).
If path ends with a trailing slash, all child items will be listed. E.g.

    config:get web/
is the same as
    config:get web/*
```

</details>

### config:search

Search system configuration descriptions.

```
n98-magerun config:search <text>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `text` | yes | The text to search for |  |

<details><summary>Help</summary>

```
Searches the merged system.xml configuration tree <labels/> and <comments/> for the indicated text.
```

</details>

### config:set

Set a core config item

```
n98-magerun config:set [--scope [SCOPE]] [--scope-id [SCOPE-ID]] [--encrypt] [--force] [--no-null] [--] <path> <value>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `path` | yes | The config path |  |
| `value` | yes | The config value |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--scope` | optional | The config value's scope (default, websites, stores) | default |
| `--scope-id` | optional | The config value's scope ID | 0 |
| `--encrypt` | flag | The config value should be encrypted using local.xml's crypt key |  |
| `--force` | flag | Allow creation of non-standard scope-id's for websites and stores |  |
| `--no-null` | flag | Do not treat value NULL as NULL (NULL/"unknown" value) value |  |

<details><summary>Help</summary>

```
Set a store config value by path.
To set a value of a specify store view you must set the "scope" and "scope-id" option.
```

</details>

## customer

### customer:change-password

Changes the password of a customer.

```
n98-magerun customer:change-password [<email> [<password> [<website>]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `email` | no | Email |  |
| `password` | no | Password |  |
| `website` | no | Website of the customer |  |

<details><summary>Help</summary>

```
Website parameter must only be given if more than one websites are available.
```

</details>

### customer:create

Creates a new customer/user for shop frontend.

```
n98-magerun customer:create [--format [FORMAT]] [--] [<email> [<password> [<firstname> [<lastname> [<website>]]]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `email` | no | Email |  |
| `password` | no | Password |  |
| `firstname` | no | Firstname |  |
| `lastname` | no | Lastname |  |
| `website` | no | Website |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### customer:create:dummy

Generate dummy customers. You can specify a count and a locale.

```
n98-magerun customer:create:dummy [--with-addresses] [--format [FORMAT]] [--] <count> <locale> [<website>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `count` | yes | Count |  |
| `locale` | yes | Locale |  |
| `website` | no | Website |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--with-addresses` | flag | Create dummy billing/shipping addresses for each customers |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
Supported Locales:

- cs_CZ
- ru_RU
- bg_BG
- en_US
- it_IT
- sr_RS
- sr_Cyrl_RS
- sr_Latn_RS
- pl_PL
- en_GB
- de_DE
- sk_SK
- fr_FR
- es_AR
- de_AT
```

</details>

### customer:delete

Delete Customer/s

```
n98-magerun customer:delete [-a|--all] [-f|--force] [-r|--range] [--] [<id>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `id` | no | Customer Id or email |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--all, -a` | flag | Delete all customers |  |
| `--force, -f` | flag | Force delete |  |
| `--range, -r` | flag | Delete a range of customers by Id |  |

<details><summary>Help</summary>

```
This will delete a customer by a given Id/Email, delete all customers or delete all customers in a range of Ids.

Example Usage:

n98-magerun customer:delete 1                   # Will delete customer with Id 1
n98-magerun customer:delete mike@example.com    # Will delete customer with that email
n98-magerun customer:delete --all               # Will delete all customers
n98-magerun customer:delete --range             # Will prompt for start and end Ids for batch deletion
```

</details>

### customer:info

Loads basic customer info by email address.

```
n98-magerun customer:info [<email> [<website>]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `email` | no | Email |  |
| `website` | no | Website of the customer |  |

### customer:list

Lists customers

```
n98-magerun customer:list [--format [FORMAT]] [--] [<search>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `search` | no | Search query |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
List customers. The output is limited to 1000 (can be changed by overriding config).
If search parameter is given the customers are filtered (searchs in firstname, lastname and email).
```

</details>

## db

### db:console

Opens mysql client by database config from local.xml

Aliases: `mysql-client`

```
n98-magerun db:console [--use-mycli-instead-of-mysql] [--no-auto-rehash]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--use-mycli-instead-of-mysql` | flag | Use `mycli` as the MySQL client instead of `mysql` |  |
| `--no-auto-rehash` | flag | Same as `-A` option to MySQL client to turn off auto-complete (avoids long initial connection time). |  |

### db:create

Create currently configured database

```
n98-magerun db:create
```

<details><summary>Help</summary>

```
The command tries to create the configured database according to your
settings in app/etc/local.xml.
The configured user must have "CREATE DATABASE" privileges on MySQL Server.
```

</details>

### db:drop

Drop current database

```
n98-magerun db:drop [-t|--tables] [-f|--force]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--tables, -t` | flag | Drop all tables instead of dropping the database |  |
| `--force, -f` | flag | Force |  |

<details><summary>Help</summary>

```
The command prompts before dropping the database. If --force option is specified it
directly drops the database.
The configured user in app/etc/local.xml must have "DROP" privileges.
```

</details>

### db:dump

Dumps database with mysqldump cli client

```
n98-magerun db:dump [-t|--add-time [ADD-TIME]] [-c|--compression COMPRESSION] [--dump-option DUMP-OPTION] [--xml] [--hex-blob] [--only-command] [--print-only-filename] [--dry-run] [--no-single-transaction] [--human-readable] [--add-routines] [--no-tablespaces] [--stdout] [-s|--strip [STRIP]] [-e|--exclude [EXCLUDE]] [-i|--include [INCLUDE]] [-f|--force] [-con|--connection [CONNECTION]] [--] [<filename>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `filename` | no | Dump filename |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--add-time, -t` | optional | Append or prepend a timestamp to filename if a filename is provided. Possible values are "suffix", "prefix" or "no". |  |
| `--compression, -c` | required | Compress the dump file using one of the supported algorithms |  |
| `--dump-option` | required, multiple | Option(s) to pass to mysqldump command. E.g. --dump-option="--set-gtid-purged=off" |  |
| `--xml` | flag | Dump database in xml format |  |
| `--hex-blob` | flag | Dump binary columns using hexadecimal notation (for example, "abc" becomes 0x616263) |  |
| `--only-command` | flag | Print only mysqldump command. Do not execute |  |
| `--print-only-filename` | flag | Execute and prints no output except the dump filename |  |
| `--dry-run` | flag | do everything but the dump |  |
| `--no-single-transaction` | flag | Do not use single-transaction (not recommended, this is blocking) |  |
| `--human-readable` | flag | Use a single insert with column names per row. Useful to track database differences. Use db:import --optimize for speeding up the import. |  |
| `--add-routines` | flag | Include stored routines in dump (procedures & functions) |  |
| `--no-tablespaces` | flag | Use this option if you want to create a dump without having the PROCESS privilege |  |
| `--stdout` | flag | Dump to stdout |  |
| `--strip, -s` | optional | Tables to strip (dump only structure of those tables) |  |
| `--exclude, -e` | optional | Tables to exclude from the dump |  |
| `--include, -i` | optional | Tables to include in the dump |  |
| `--force, -f` | flag | Do not prompt if all options are defined |  |
| `--connection, -con` | optional | Specify local.xml connection node, default to default_setup |  |

<details><summary>Help</summary>

```
Dumps configured magento database with `mysqldump`. You must have installed
the MySQL client tools.

On debian systems run `apt-get install mysql-client` to do that.

The command reads app/etc/local.xml to find the correct settings.

See it in action: https://youtu.be/ttjZHY6vThs

- If you like to prepend a timestamp to the dump name the --add-time option
  can be used.

- The command comes with a compression function. Add i.e. `--compression=gz`
  to dump directly in gzip compressed file.


Compression option
 Supported compression: gzip
 The gzip cli tool has to be installed.
 Additionally, for data-to-csv option tar cli tool has to be installed too.

Strip option
 If you like to skip data of some tables you can use the --strip option.
 The strip option creates only the structure of the defined tables and
 forces `mysqldump` to skip the data.

 Separate each table to strip by a space.
 You can use wildcards like * and ? in the table names to strip multiple
 tables. In addition you can specify pre-defined table groups, that start
 with an

 Example: "dataflow_batch_export unimportant_module_* @log

    $ n98-magerun.phar db:dump --strip="@stripped"

Available Table Groups
 @admin             Admin tables.
 @log               Log tables.
 @dataflowtemp      Temporary tables of the dataflow import/export tool.
 @importexporttemp  Temporary tables of the Import/Export module.
 @sessions          Database session tables.
 @stripped          Standard definition for a stripped dump (logs, sessions
                    and dataflow).
 @sales             Sales data (orders, invoices, creditmemos etc).
 @customers         Customer data - Should not be used without @sales.
 @emails            Email queue tables.
 @newsletter        Newsletter subscriber data.
 @trade             Current trade data (customers and orders). You usally do
                    not want those in developer systems..
 @development       Removes logs and trade data so developers do not have to
                    work with real customer data.
 @ee_changelog      Changelog tables of new indexer since EE 1.13.
 @search            Search related tables.
 @idx               Tables with _idx suffix and index event tables.

Extended: https://github.com/netz98/n98-magerun/wiki/Stripped-Database-Dumps
```

</details>

### db:import

Imports database with mysql cli client according to database defined in local.xml

```
n98-magerun db:import [-c|--compression COMPRESSION] [--only-command] [--only-if-empty] [--optimize] [--drop] [--stdin] [--drop-tables] [--] [<filename>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `filename` | no | Dump filename |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--compression, -c` | required | The compression of the specified file |  |
| `--only-command` | flag | Print only mysql command. Do not execute |  |
| `--only-if-empty` | flag | Imports only if database is empty |  |
| `--optimize` | flag | Convert verbose INSERTs to short ones before import (not working with compression) |  |
| `--drop` | flag | Drop and recreate database before import |  |
| `--stdin` | flag | Import data from STDIN rather than file |  |
| `--drop-tables` | flag | Drop tables before import |  |

<details><summary>Help</summary>

```
Imports an SQL file with mysql cli client into current configured database.

You need to have MySQL client tools installed on your system.

Compression option
 Supported compression: gzip
 The gzip cli tool has to be installed.
 Additionally, for data-to-csv option tar cli tool has to be installed too.
```

</details>

### db:info

Dumps database information

```
n98-magerun db:info [--format [FORMAT]] [--] [<setting>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `setting` | no | Only output value of named setting |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
This command is useful to print all information about the current configured database in app/etc/local.xml.
It can print connection string for JDBC, PDO connections.
```

</details>

### db:maintain:check-tables

Check database tables

```
n98-magerun db:maintain:check-tables [--type [TYPE]] [--repair] [--table [TABLE]] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--type` | optional | Check type (one of QUICK, FAST, MEDIUM, EXTENDED, CHANGED) | MEDIUM |
| `--repair` | flag | Repair tables (only MyISAM) |  |
| `--table` | optional | Process only given table (wildcards are supported) |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
TYPE OPTIONS

QUICK
            Do not scan the rows to check for incorrect links.
            Applies to InnoDB and MyISAM tables and views.
FAST
            Check only tables that have not been closed properly.
            Applies only to MyISAM tables and views; ignored for InnoDB.
CHANGED
            Check only tables that have been changed since the last check or that
            have not been closed properly. Applies only to MyISAM tables and views;
            ignored for InnoDB.
MEDIUM
            Scan rows to verify that deleted links are valid.
            This also calculates a key checksum for the rows and verifies this with a
            calculated checksum for the keys. Applies only to MyISAM tables and views;
            ignored for InnoDB.
EXTENDED
            Do a full key lookup for all keys for each row. This ensures that the table
            is 100% consistent, but takes a long time.
            Applies only to MyISAM tables and views; ignored for InnoDB.

InnoDB
            InnoDB tables will be optimized with the ALTER TABLE ... ENGINE=InnoDB statement.
            The options above do not apply to them.
```

</details>

### db:query

Executes an SQL query on the database defined in local.xml

```
n98-magerun db:query [--only-command] [--] [<query>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `query` | no | SQL query |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--only-command` | flag | Print only mysql command. Do not execute |  |

<details><summary>Help</summary>

```
Executes an SQL query on the current configured database. Wrap your SQL in
single or double quotes.

If your query produces a result (e.g. a SELECT statement), the output of the
mysql cli tool will be returned.

* Requires MySQL CLI tools installed on your system.
```

</details>

### db:status

Shows important server status information or custom selected status values

```
n98-magerun db:status [--format [FORMAT]] [--rounding [ROUNDING]] [--no-description] [--] [<search>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `search` | no | Only output variables of specified name. The wildcard % is supported! |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |
| `--rounding` | optional | Amount of decimals to display. If -1 then disabled | 0 |
| `--no-description` | flag | Disable description |  |

<details><summary>Help</summary>

```
This command is useful to print important server status information about the current database.
```

</details>

### db:variables

Shows important variables or custom selected

```
n98-magerun db:variables [--format [FORMAT]] [--rounding [ROUNDING]] [--no-description] [--] [<search>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `search` | no | Only output variables of specified name. The wildcard % is supported! |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |
| `--rounding` | optional | Amount of decimals to display. If -1 then disabled | 0 |
| `--no-description` | flag | Disable description |  |

<details><summary>Help</summary>

```
This command is useful to print all important variables about the current database.
```

</details>

## design

### design:demo-notice

Toggles demo store notice for a store view

```
n98-magerun design:demo-notice [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

## dev

### dev:class:lookup

Resolves a grouped class name

```
n98-magerun dev:class:lookup <type> <name>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `type` | yes | The type of the class (helper\|block\|model) |  |
| `name` | yes | The grouped class name |  |

### dev:code:model:method

Code annotations: Reads the columns from a table and writes the getter and setter methods into the class file for @methods.

```
n98-magerun dev:code:model:method [<modelName>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `modelName` | no | Model Name namespace/modelName |  |

### dev:console

Opens PHP interactive shell with initialized Mage::app() (Experimental)

```
n98-magerun dev:console
```

### dev:email-template:usage

Display database transactional email template usage

```
n98-magerun dev:email-template:usage [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:ide:phpstorm:meta

Generates meta data file for PhpStorm auto completion (default version : 2019.1+)

```
n98-magerun dev:ide:phpstorm:meta [--meta-version META-VERSION] [--stdout]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--meta-version` | required | PhpStorm Meta version (old, 2016.2+, 2019.1+) | 2019.1+ |
| `--stdout` | flag | Print to stdout instead of file .phpstorm.meta.php |  |

### dev:log

Toggle development log (system.log, exception.log)

```
n98-magerun dev:log [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

### dev:log:db

Turn on/off database query logging

```
n98-magerun dev:log:db [--on] [--off]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Force logging |  |
| `--off` | flag | Disable logging |  |

### dev:log:size

Get size of log file

```
n98-magerun dev:log:size [--human] [--] [<log_filename>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `log_filename` | no | Name of log file. |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--human` | flag | Human readable output |  |

### dev:merge-css

Toggles CSS Merging

```
n98-magerun dev:merge-css [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

### dev:merge-js

Toggles JS Merging

```
n98-magerun dev:merge-js [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

### dev:module:create

Create and register a new magento module.

```
n98-magerun dev:module:create [--add-controllers] [--add-blocks] [--add-helpers] [--add-models] [--add-setup] [--add-all] [--modman] [--add-readme] [--add-composer] [--author-name [AUTHOR-NAME]] [--author-email [AUTHOR-EMAIL]] [--description [DESCRIPTION]] [--] <vendorNamespace> <moduleName> [<codePool>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `vendorNamespace` | yes | Namespace (your company prefix) |  |
| `moduleName` | yes | Name of your module. |  |
| `codePool` | no | Codepool (local, community) | local |

| Option | Value | Description | Default |
|---|---|---|---|
| `--add-controllers` | flag | Adds controllers |  |
| `--add-blocks` | flag | Adds blocks |  |
| `--add-helpers` | flag | Adds helpers |  |
| `--add-models` | flag | Adds models |  |
| `--add-setup` | flag | Adds SQL setup |  |
| `--add-all` | flag | Adds blocks, helpers and models |  |
| `--modman` | flag | Create all files in folder with a modman file. |  |
| `--add-readme` | flag | Adds a readme.md file to generated module |  |
| `--add-composer` | flag | Adds a composer.json file to generated module |  |
| `--author-name` | optional | Author for readme.md or composer.json |  |
| `--author-email` | optional | Author for readme.md or composer.json |  |
| `--description` | optional | Description for readme.md or composer.json |  |

### dev:module:dependencies:from

Show list of modules which depend on %s module

```
n98-magerun dev:module:dependencies:from [-a|--all] [--format [FORMAT]] [--] <moduleName>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `moduleName` | yes | Module to show dependencies |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--all, -a` | flag | Show all dependencies (dependencies of dependencies) |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:module:dependencies:on

Show list of modules which given module depends on

```
n98-magerun dev:module:dependencies:on [-a|--all] [--format [FORMAT]] [--] <moduleName>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `moduleName` | yes | Module to show dependencies |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--all, -a` | flag | Show all dependencies (dependencies of dependencies) |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:module:disable

Disable a module or all modules in codePool

```
n98-magerun dev:module:disable [--codepool [CODEPOOL]] [--] [<moduleName>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `moduleName` | no | Name of module to disable |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--codepool` | optional | Name of codePool to disable |  |

### dev:module:enable

Enable a module or all modules in codePool

```
n98-magerun dev:module:enable [--codepool [CODEPOOL]] [--] [<moduleName>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `moduleName` | no | Name of module to enable |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--codepool` | optional | Name of codePool to enable |  |

### dev:module:list

List all installed modules

Aliases: `sys:modules:list`

```
n98-magerun dev:module:list [--codepool [CODEPOOL]] [--status [STATUS]] [--vendor [VENDOR]] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--codepool` | optional | Show modules in a specific codepool |  |
| `--status` | optional | Show modules with a specific status |  |
| `--vendor` | optional | Show modules of a specified vendor |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:module:observer:list

Lists all registered observers

```
n98-magerun dev:module:observer:list [--format [FORMAT]] [--sort] [--] [<type>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `type` | no | Observer type (global, admin, frontend, crontab) |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |
| `--sort` | flag | Sort by event name ascending |  |

### dev:module:rewrite:conflicts

Lists all magento rewrite conflicts

```
n98-magerun dev:module:rewrite:conflicts [--log-junit LOG-JUNIT]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--log-junit` | required | Log conflicts in JUnit XML format to defined file. |  |

<details><summary>Help</summary>

```
Lists all duplicated rewrites and tells you which class is loaded by Magento.
The command checks class inheritance in order of your module dependencies.

* If a filename with `--log-junit` option is set the tool generates an XML file and no output to *stdout*.

Exit status is 0 if no conflicts were found, 1 if conflicts were found and 2 if there was a problem to
initialize Magento.
```

</details>

### dev:module:rewrite:list

Lists all magento rewrites

```
n98-magerun dev:module:rewrite:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:module:update

Update a Magento module.

```
n98-magerun dev:module:update [--set-version] [--add-blocks] [--add-helpers] [--add-models] [--add-all] [--add-resource-model] [--add-routers] [--add-events] [--add-layout-updates] [--add-translate] [--add-default] [--] <vendorNamespace> <moduleName>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `vendorNamespace` | yes | Namespace (your company prefix) |  |
| `moduleName` | yes | Name of your module. |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--set-version` | flag | Set module version in config.xml |  |
| `--add-blocks` | flag | Adds blocks class to config.xml |  |
| `--add-helpers` | flag | Adds helpers class to config.xml |  |
| `--add-models` | flag | Adds models class to config.xml |  |
| `--add-all` | flag | Adds blocks, helpers and models classes to config.xml |  |
| `--add-resource-model` | flag | Adds resource model class and entities to config.xml |  |
| `--add-routers` | flag | Adds routers for frontend or admin areas to config.xml |  |
| `--add-events` | flag | Adds events observer to global, frontend or adminhtml areas to config.xml |  |
| `--add-layout-updates` | flag | Adds layout updates to frontend or adminhtml areas to config.xml |  |
| `--add-translate` | flag | Adds translate configuration to frontend or adminhtml areas to config.xml |  |
| `--add-default` | flag | Adds default value (related to system.xml groups/fields) |  |

### dev:profiler

Toggles profiler for debugging

```
n98-magerun dev:profiler [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

### dev:report:count

Get count of report files

```
n98-magerun dev:report:count
```

### dev:setup:script:attribute

Creates attribute script for a given attribute code

```
n98-magerun dev:setup:script:attribute <entityType> <attributeCode>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `entityType` | yes | Entity Type Code like catalog_product |  |
| `attributeCode` | yes | Attribute Code |  |

### dev:symlinks

Toggle allow symlinks setting

```
n98-magerun dev:symlinks [--on] [--off] [--global] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |
| `--global` | flag | Set value on default scope |  |

### dev:template-hints

Toggles template hints

```
n98-magerun dev:template-hints [--on] [--off] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |

### dev:template-hints-blocks

Toggles template hints block names

```
n98-magerun dev:template-hints-blocks [--on] [--off] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |

### dev:theme:duplicates

Find duplicate files (templates, layout, locale, etc.) between two themes.

```
n98-magerun dev:theme:duplicates [--log-junit LOG-JUNIT] [--] <theme> [<originalTheme>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `theme` | yes | Your theme |  |
| `originalTheme` | no | Original theme to comapre. Default is "base/default" | base/default |

| Option | Value | Description | Default |
|---|---|---|---|
| `--log-junit` | required | Log duplicates in JUnit XML format to defined file. |  |

<details><summary>Help</summary>

```
* If a filename with `--log-junit` option is set the tool generates an XML file and no output to *stdout*.
```

</details>

### dev:theme:info

Displays settings of current design on particular store view

```
n98-magerun dev:theme:info
```

### dev:theme:list

Lists all available themes

```
n98-magerun dev:theme:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### dev:translate:admin

Toggle inline translation tool for admin

```
n98-magerun dev:translate:admin [--on] [--off]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |

### dev:translate:export

Export inline translations

```
n98-magerun dev:translate:export [--store [STORE]] [--] [<locale> [<filename>]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `locale` | no | Locale |  |
| `filename` | no | Export filename |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--store` | optional | Limit to a special store |  |

### dev:translate:set

Adds a translation to core_translate table. Globally for locale

```
n98-magerun dev:translate:set <string> <translate> [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `string` | yes | String to translate |  |
| `translate` | yes | Translated string |  |
| `store` | no |  |  |

### dev:translate:shop

Toggle inline translation tool for shop

```
n98-magerun dev:translate:shop [--on] [--off] [--] [<store>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `store` | no | Store code or ID |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Switch on |  |
| `--off` | flag | Switch off |  |

## eav

### eav:attribute:create-dummy-values

Create a dummy values for dropdown attributes

```
n98-magerun eav:attribute:create-dummy-values [<locale> [<attribute-id> [<values-type> [<values-number>]]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `locale` | no | Locale |  |
| `attribute-id` | no | Attribute ID to add values |  |
| `values-type` | no | Types of Values to create (default int) |  |
| `values-number` | no | Number of Values to create (default 1) |  |

<details><summary>Help</summary>

```
Supported Locales:

- en_US
- en_GB
```

</details>

### eav:attribute:list

Lists all EAV attributes

```
n98-magerun eav:attribute:list [--filter-type [FILTER-TYPE]] [--add-source] [--add-backend] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--filter-type` | optional | Filter attributes by entity type |  |
| `--add-source` | flag | Add source models to list |  |
| `--add-backend` | flag | Add backend type to list |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### eav:attribute:remove

Removes attribute for a given attribute code

```
n98-magerun eav:attribute:remove <entityType> <attributeCode>...
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `entityType` | yes | Entity Type Code like catalog_product |  |
| `attributeCode...` | yes | Attribute Code |  |

### eav:attribute:view

View informations about an EAV attribute

```
n98-magerun eav:attribute:view [--format [FORMAT]] [--] <entityType> <attributeCode>
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `entityType` | yes | Entity Type Code like catalog_product |  |
| `attributeCode` | yes | Attribute Code |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

## index

### index:list

Lists all magento indexes

```
n98-magerun index:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
Lists all Magento indexers of current installation.
```

</details>

### index:reindex

Reindex a magento index by code

```
n98-magerun index:reindex [<index_code>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `index_code` | no | Code of indexer. |  |

<details><summary>Help</summary>

```
Index by indexer code. Code is optional. If you don't specify a code you can pick a indexer from a list.

   $ n98-magerun.phar index:reindex [code]


Since 1.75.0 it's possible to run multiple indexers by separating code with a comma.

i.e.

   $ n98-magerun.phar index:reindex catalog_product_attribute,tag_summary

If no index is provided as argument you can select indexers from menu by "number" like "1,3" for first and third
indexer.
```

</details>

### index:reindex:all

Reindex all magento indexes

```
n98-magerun index:reindex:all
```

<details><summary>Help</summary>

```
Loops all magento indexes and triggers reindex.
```

</details>

## local-config

### local-config:generate

Generates local.xml config

```
n98-magerun local-config:generate [<db-host> [<db-user> [<db-pass> [<db-name> [<session-save> [<admin-frontname> [<encryption-key>]]]]]]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `db-host` | no | Database host |  |
| `db-user` | no | Database user |  |
| `db-pass` | no | Database password |  |
| `db-name` | no | Database name |  |
| `session-save` | no | Session storage adapter |  |
| `admin-frontname` | no | Admin front name |  |
| `encryption-key` | no | Encryption Key |  |

<details><summary>Help</summary>

```
Generates the app/etc/local.xml.

- The file "app/etc/local.xml.template" (bundles with Magento) must exist!
- Currently the command does not validate anything you enter.
- The command will not overwrite existing app/etc/local.xml files.
```

</details>

## media

### media:cache:image:clear

Clears image cache

```
n98-magerun media:cache:image:clear
```

### media:cache:jscss:clear

Clears JS/CSS cache

```
n98-magerun media:cache:jscss:clear
```

### media:dump

Creates an archive with content of media folder.

```
n98-magerun media:dump [--strip] [--] [<filename>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `filename` | no | Dump filename |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--strip` | flag | Excludes image cache |  |

## script

### script:repo:list

Lists all scripts in repository

```
n98-magerun script:repo:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
You can organize your scripts in a repository.
Simply place a script in folder */usr/local/share/n98-magerun/scripts* or in your home dir
in folder *<HOME>/.n98-magerun/scripts*.

Scripts must have the file extension *.magerun*.

After that you can list all scripts with the *script:repo:list* command.
The first line of the script can contain a comment (line prefixed with #) which will be displayed as description.

   $ n98-magerun.phar script:repo:list
```

</details>

### script:repo:run

Run script from repository

```
n98-magerun script:repo:run [-d|--define [DEFINE]] [--stop-on-error] [--] [<script>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `script` | no | Name of script in repository |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--define, -d` | optional, multiple | Defines a variable |  |
| `--stop-on-error` | flag | Stops execution of script on error |  |

<details><summary>Help</summary>

```
Please note that the script repo command runs only scripts which are stored
in a defined script folder.

Script folders can defined by config.

Example:

script:
  folders:
    - /my/script_folder


There are some pre defined script folders:

- /usr/local/share/n98-magerun/scripts
- ~/.n98-magerun/scripts

If you like to run a standalone script you can also use the "script" command.

See: n98-magerun.phar script <filename.magerun>
```

</details>

## sys

### sys:check

Checks Magento System

```
n98-magerun sys:check [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
- Checks missing files and folders
- Security
- PHP Extensions (Required and Bytecode Cache)
- MySQL InnoDB Engine
```

</details>

### sys:cron:history

Last executed cronjobs with status.

```
n98-magerun sys:cron:history [--timezone [TIMEZONE]] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--timezone` | optional | Timezone to show finished at in |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### sys:cron:list

Lists all cronjobs

```
n98-magerun sys:cron:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### sys:cron:run

Runs a cronjob by job code

```
n98-magerun sys:cron:run [-s|--schedule] [--] [<job>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `job` | no | Job code |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--schedule, -s` | flag | Schedule cron instead of run with current user |  |

<details><summary>Help</summary>

```
If no `job` argument is passed you can select a job from a list.
See it in action: https://www.youtube.com/watch?v=QkzkLgrfNaM
If option schedule is present, cron is not launched, but just scheduled immediately in magento crontab.
```

</details>

### sys:info

Prints infos about the current magento system.

```
n98-magerun sys:info [--format [FORMAT]] [--] [<key>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `key` | no | Only output value of named param like "version". Key is case insensitive. |  |

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### sys:maintenance

Toggles maintenance mode.

```
n98-magerun sys:maintenance [--on] [--off]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--on` | flag | Enable maintenance mode |  |
| `--off` | flag | Disable maintenance mode |  |

### sys:setup:change-version

Change module setup resource version

```
n98-magerun sys:setup:change-version <module> <version> [<setup>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `module` | yes | Module name |  |
| `version` | yes | New version value |  |
| `setup` | no | Setup code to update | all |

### sys:setup:compare-versions

Compare module version with core_resource table.

```
n98-magerun sys:setup:compare-versions [--ignore-data] [--log-junit LOG-JUNIT] [--errors-only] [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--ignore-data` | flag | Ignore data updates |  |
| `--log-junit` | required | Log output to a JUnit xml file. |  |
| `--errors-only` | flag | Only display Setup resources where Status equals Error. |  |
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

<details><summary>Help</summary>

```
Compares module version with saved setup version in `core_resource` table and displays version mismatch.
```

</details>

### sys:setup:incremental

List new setup scripts to run, then runs one script

```
n98-magerun sys:setup:incremental [--stop-on-error]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--stop-on-error` | flag | Stops execution of script on error |  |

<details><summary>Help</summary>

```
Examines an un-cached configuration tree and determines which
structure and data setup resource scripts need to run, and then runs them.
```

</details>

### sys:setup:remove

Remove module setup resource entry

```
n98-magerun sys:setup:remove <module> [<setup>]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `module` | yes | Module name |  |
| `setup` | no | Setup code to remove | all |

### sys:setup:run

Runs all new setup scripts.

```
n98-magerun sys:setup:run [--no-implicit-cache-flush]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--no-implicit-cache-flush` | flag | Do not flush the cache |  |

<details><summary>Help</summary>

```
Runs all setup scripts (no need to call frontend).
This command is useful if you update your system with enabled maintenance mode.
```

</details>

### sys:store:config:base-url:list

Lists all base urls

```
n98-magerun sys:store:config:base-url:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### sys:store:list

Lists all installed store-views

```
n98-magerun sys:store:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

### sys:url:list

Get all urls.

```
n98-magerun sys:url:list [--add-categories] [--add-products] [--add-cmspages] [--add-all] [--] [<stores> [<linetemplate>]]
```

| Argument | Required | Description | Default |
|---|---|---|---|
| `stores` | no | Stores (comma-separated list of store ids) |  |
| `linetemplate` | no | Line template | {url} |

| Option | Value | Description | Default |
|---|---|---|---|
| `--add-categories` | flag | Adds categories |  |
| `--add-products` | flag | Adds products |  |
| `--add-cmspages` | flag | Adds cms pages |  |
| `--add-all` | flag | Adds categories, products and cms pages |  |

<details><summary>Help</summary>

```
Examples:

- Create a list of product urls only:

   $ n98-magerun.phar sys:url:list --add-products 4

- Create a list of all products, categories and cms pages of store 4 
  and 5 separating host and path (e.g. to feed a jmeter csv sampler):

   $ n98-magerun.phar sys:url:list --add-all 4,5 '{host},{path}' > urls.csv

- The "linetemplate" can contain all parts "parse_url" return wrapped 
  in '{}'. '{url}' always maps the complete url and is set by default
```

</details>

### sys:website:list

Lists all websites

```
n98-magerun sys:website:list [--format [FORMAT]]
```

| Option | Value | Description | Default |
|---|---|---|---|
| `--format` | optional | Output Format. One of [csv,json,text,xml] |  |

