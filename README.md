# PODS Development Guide

## Requirements

- WSL2 (if developing on Windows)
- Docker

## Setup

Run `./scripts/repods` to get started. This will:

1. Start the PODS `www` and `db` Docker containers.
2. Build the PODS codebase.
3. Install Drupal+farmOS+PODS.
4. Import taxonomies.
5. Enable development modules.

If PODS started successfully the following message will show in the logs:

```
##### PODS IS READY #####
```

Open PODS in a browser at http://localhost:85/

## Stopping/restarting

To stop PODS, open the terminal it is running in and Ctrl+C.

To restart PODS, run `./scripts/podsu`.

## Rebuilding

At any time, PODS can be rebuilt by running `./scripts/repods`.

WARNING: This will completely rebuild the codebase, clear the database, and
revert uncommitted changes to code.

## Developing

PODS is built on [farmOS](https://farmOS.org), which is a Drupal distribution.

It follows standard Drupal development practices.

### Composer

Dependencies are managed via [Composer](https://getcomposer.org/), which is
installed in the PODS Docker container.

Dependencies are managed in `composer.json`, and locked to specific versions
in `composer.lock` (automatically generated/updated by Composer).

A helper script is provided for running Composer commands. For example:

To list Composer commands:

`./scripts/composer list`

To add a new package:

`./scripts/composer require [vendor]/[package]`

To update dependencies:

`./scripts/composer update`

### Drush

[Drush](https://www.drush.org) is a Drupal command-line tool, which is
installed in the PODS Docker container.

A helper script is provided for running Drush commands. For example:

To list Drush commands:

`./scripts/drush list`

To enable a Drupal module (eg: after downloading it via Composer):

`./scripts/drush en [modulename]`

### PHP Codesniffer

[PHP Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) is a linting
tool, which is installed in the PODS Docker container.

A helper script is provided for checking PODS code for coding standards
violations:

`./scripts/codecheck`

If there is no output, then the code is clean.

### XDebug

[XDebug](https://xdebug.org) is a PHP debugger, which is installed in the PODS
Docker container.

The PODS development environment comes with XDebug configuration for
[VSCode](https://code.visualstudio.com/). Install the PHP Debug extension for
VSCode with F1 + `ext install php-debug`.

To start the debugger, click "Run and Debug" in VSCode and then click the green
arrow next to "Listen for XDebug". Add a breakpoint to a line of code (eg:
`web/index.php`), and refresh PODS in the browser. VSCode will intercept the
request and stop execution at the breakpoint. Variables can be examined, and
execution can be resumed or stepped forward one line at a time using the
debugger controls.
