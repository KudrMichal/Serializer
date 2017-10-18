<?php

/**
 * Test inicialization
 *
 * @author Michal
 */

include __DIR__ . "/../vendor/autoload.php";


//create temp path constant
define('TEMP_DIR', __DIR__ . '/tmp');
//create temp dir fi not exists
Tester\Helpers::purge(TEMP_DIR);

//setup environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');