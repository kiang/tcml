<?php

$rootPath = dirname(__DIR__);
$now = date('Y-m-d H:i:s');

exec("cd {$rootPath} && /usr/bin/git pull");

exec("/usr/bin/php -q {$rootPath}/scripts/01_get_list.php");
exec("/usr/bin/php -q {$rootPath}/scripts/02_extract_data.php");

exec("cd {$rootPath} && /usr/bin/git add -A");

exec("cd {$rootPath} && /usr/bin/git commit --author 'auto commit <noreply@localhost>' -m 'auto update @ {$now}'");

exec("cd {$rootPath} && /usr/bin/git push origin master");
