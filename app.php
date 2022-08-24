<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

$env = parse_ini_file('.env');
$token = $env['token'];

$discord = new Discord([
    'token' => $token
]);

$discord->on('ready', function(Discord $discord){
    echo "Watching...\n";
    $successGifs = array_diff(scandir('./gifs/success'), array('.','..'));
    $failGifs = array_diff(scandir('./gifs/fail'), array('.','..'));
    
    $successMsgs = ['Aí sim', 'Boa', 'Show'];
    $failMsgs = ['Vish...','Xiii', 'Ihh...','Lascou'];

    $discord->on(Event::MESSAGE_CREATE, function(Message $message, Discord $discord) use ($successGifs, $successMsgs, $failGifs, $failMsgs){
        if ($message->author->id === $discord->id) return;
        if (!$message->embeds[0]) return;

        $content = $message->embeds[0]->description;

        if (str_contains($content, 'has failed')) {
            $message->channel->sendMessage(MessageBuilder::new()
                ->setContent($failMsgs[array_rand($failMsgs)])
                ->addFile('./gifs/fail/'.$failGifs[array_rand($failGifs)])
            );
        } elseif (str_contains($content, 'has passed')) {
            $message->channel->sendMessage(MessageBuilder::new()
                ->setContent($successMsgs[array_rand($successMsgs)])
                ->addFile('./gifs/success/'.$successGifs[array_rand($successGifs)])
            );
        }
    });
});

$discord->run();
