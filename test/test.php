<?php

include __DIR__ . '/../vendor/autoload.php';

use SunflowerFuchs\DiscordBot\Bot;

$token = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'test.ini', false)['token'];

$options = [
    'token' => $token,
    'prefix' => '!',
];

$bot = new Bot($options);
$bot->run();