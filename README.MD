# Modular Discord Bot

A Bot for Discord, written in PHP.

The main goal was modularity, which enables it to easily be split into independently installable plugins.

## Requirements

- PHP >= 8.1
- composer
- [discord developer application](https://discord.com/developers/applications)

## Quickstart

The following snippet is all that is required to run the basic bot with the default plugins.

```php
$bot = new SunflowerFuchs\DiscordBot\Bot([
    'token' => 'XXXXX'
]);
$bot->run();
```

Other features can easily be added to the Bot by just adding more plugins, like for example
the [TikTok Plugin](https://github.com/SunflowerFuchs/discord-tiktok-plugin).

```php
$bot = new SunflowerFuchs\DiscordBot\Bot($options);
$bot->registerPlugin(new TiktokPlugin());
$bot->run();
```

<!--
TODO: 
- add Documentation for remaining Bot options
- add Documentation for developing new plugins
- add Contribution Guidelines
-->