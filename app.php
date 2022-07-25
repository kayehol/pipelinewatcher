<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\WebSockets\Event;

$env = parse_ini_file('.env');
$token = $env['token'];

$discord = new Discord([
    'token' => $token
]);

$discord->on('ready', function(Discord $discord){
    echo "Watching...\n";
    $successGifs = ['success1', 'success2', 'success3'];
    $successMsgs = ['Aí sim', 'Boa', 'Show'];
    $failGifs = ['fail1','fail2','fail3','fail4','fail5'];
    $failMsgs = ['Vish...','Xiii', 'Ihh...','Lascou'];

    $discord->on(Event::MESSAGE_CREATE, function(Message $message, Discord $discord) use ($successGifs, $successMsgs, $failGifs, $failMsgs){
        if ($message->author->id === $discord->id) return;
        if (!$message->embeds) return;

        $content = $message->embeds[0]->description;

        if (str_contains($content, 'has failed')) {
            $message->channel->sendMessage(MessageBuilder::new()
                ->setContent($failMsgs[array_rand($failMsgs)])
                ->addFile('./'.$failGifs[array_rand($failGifs)].'.gif')
            );
        } elseif (str_contains($content, 'has passed')) {
            $message->channel->sendMessage(MessageBuilder::new()
                ->setContent($successMsgs[array_rand($successMsgs)])
                ->addFile('./'.$successGifs[array_rand($successGifs)].'.gif')
            );
        }
    });
});

$discord->run();
