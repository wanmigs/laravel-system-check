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

`/system/application`
`/system/release`