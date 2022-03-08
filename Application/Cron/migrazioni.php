#!/usr/bin/php
<?php

define('APP_CONSOLE', true);

require_once(dirname(__FILE__) . "/../../index.php");

$res = Migrazioni::up();

echo $res;
