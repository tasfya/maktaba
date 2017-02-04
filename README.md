##With Docker:
```
docker-compose up
```


##Without Docker:
Create your database and import db/exports/maktaba.sql.gz

Run :
```bash
echo "<?php
\$databases = array (
  'default' =>
  array (
    'default' =>
    array (
      'database' => 'DATABASE_NAME',
      'username' => 'DATABASE_USER',
      'password' => 'DATABASE_PASSEWORD',
      'host' => '127.0.0.1',
      'port' => '',
      'driver' => 'mysql',
      'prefix' => '',
    ),
  ),
);
" > sites/default/settings.local.php
```
