<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=localhost;dbname=blog_test';
$db['username'] = 'blog_user';
$db['password'] = '1234';

return $db;
