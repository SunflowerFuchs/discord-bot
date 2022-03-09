<?php

include __DIR__ . '/../vendor/autoload.php';

use SunflowerFuchs\DiscordBot\Bot;

$options = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'test.ini', false, INI_SCANNER_TYPED);

$bot = new Bot($options);
$bot->run();