<?php

require __DIR__.'/../../../../../autoload.php';
require_once __DIR__.'/../../../../media-manager/tests/Registry.php';

use CanalTP\MediaManager\Registry;

$folder = __DIR__.'/../Tests/data/registry/';
Registry::addByFile($folder . 'strings.ini');
Registry::addByFile($folder . 'messages.ini');
Registry::add('/', __DIR__ . '/'); 
