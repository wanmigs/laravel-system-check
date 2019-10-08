# Overview
- implement a HTTP health check API that can be hit to perform basic application diagnostics
- application instance health check
- application release health check

## Add Health Check Route to routes/web.php

```
use Fligno\System\SystemCheck;

SystemCheck::routes();
```

## Routes URL

```
/system/application
/system/release
```

That's it for laravel setup. Below is the manual usage of the functions.

# Manual Usage

## Setup Health Check

```
use Fligno\System\SystemCheck;

$system = new SystemCheck();
```

## Health Check API

### `$system->getStatus()`

results :
```
{
    'status': 'OK'
    'timestamp': '2019-06-14T04:01:03Z00:00'
    'instance-id': '<hostname>'
}
```

### `$system->getPhpInfo()`

Details :
- php version
- php component checks

results :
```
{
    'timestamp': '2019-06-14T04:01:03Z00:00'
    'instance-id': '<hostname>'
    'php-version': 'php7.2'
    'php-modules': [
        'mbstring',
        . . .
    ]
}
```

### `$system->getReleaseInfo()`

Details :
- information stored from `build.json` generated from CI build

## Defining `build.json` path

### Option 1
### env `GIT_BUILD_FILE_PATH`

### Option 2 
### `$system->setBuildPath($path_to_build_json);`

#### file resource :

`build.json`

```
{
    'start': '2019-06-14T04:01:03Z00:00'
    'end': '2019-06-14T04:01:03Z00:00',
    'status': 'SUCCESS',
    'branch': '<git-branch>',
    'release': '<git-relesae-tag>',
    'commit': '<git-commit-id>',
    'build': '<CI-build-id>'
    'config': '<configuration-id-from-CD>'
}

```
