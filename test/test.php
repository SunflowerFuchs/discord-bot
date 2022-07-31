<?php

declare(strict_types=1);

ini_set('date.timezone', 'Europe/Berlin');
date_default_timezone_set('Europe/Berlin');

include __DIR__ . '/../vendor/autoload.php';

use SunflowerFuchs\DiscordBot\Bot;
use SunflowerFuchs\DiscordBot\Plugins\GiveawayPlugin;
use SunflowerFuchs\DiscordBot\Plugins\TiktokPlugin;

$options = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'test.ini', false, INI_SCANNER_TYPED);

$bot = new Bot($options);
$bot->registerPlugin(new TiktokPlugin());
$bot->registerPlugin(new GiveawayPlugin());
$bot->run();