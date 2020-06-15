<?php

namespace App\Service\Sender;

use App\Entity\Rapport;
use App\Utils\Rapport\ErrorLevel;
use N445\EasyDiscord\Model\Embed;
use N445\EasyDiscord\Model\Field;
use N445\EasyDiscord\Model\Message;

class DiscordSender
{
    /**
     * @param Rapport[] $rapports
     */
    public function send(array $rapports)
    {
        $discord = (new \N445\EasyDiscord\Service\DiscordSender())->addIdToken('721354431270092871', 'rUK71iWIExWutA6-S2iWVPfR9feqQroDauZdQ71aNtGJzk4U0-4uPNaSTduzF8-xXyDJ');
        $embed   = (new Embed())->setTitle('Liste des erreurs')->setDescription('rien');
        foreach ($rapports as $rapport) {
            $field = (new Field())
                ->setName($rapport->getUrl()->getSite()->getDomain() . $rapport->getUrl()->getUrl())
                ->setValue(ErrorLevel::getErrorCodeLabel($rapport->getErrorCode()))
            ;
            $embed->addField($field);
        }
        $message = (new Message())->setUsername('URL Checker')->addEmbed($embed);
        $discord->send($message);
    }
}