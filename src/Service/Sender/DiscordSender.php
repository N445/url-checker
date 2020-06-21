<?php

namespace App\Service\Sender;

use App\Entity\Rapport;
use App\Entity\Site;
use App\Entity\Url;
use App\Utils\Rapport\ErrorLevel;
use N445\EasyDiscord\Model\Embed;
use N445\EasyDiscord\Model\Field;
use N445\EasyDiscord\Model\Message;
use N445\EasyDiscord\Service\DiscordSender as N445DiscordSender;

class DiscordSender
{
    /**
     * @param Rapport[] $rapports
     */
    public function send(array $rapports)
    {
        if (!count($rapports)) {
            return;
        }

        $discord = (new N445DiscordSender())->addIdToken($_ENV['DISCORD_ID'], $_ENV['DISCORD_TOKEN']);

        $sites = [];
        /** @var Rapport $rapport */
        foreach ($rapports as $rapport) {
            /** @var Url $url */
            $url = $rapport->getUrl();
            /** @var Site $site */
            $site                                                                  = $url->getSite();
            $sites[sprintf('%s://%s', $site->getProtocol(), $site->getDomain())][] = $rapport;
        }

        $texts = [];
        foreach ($sites as $domaine => $toto) {
            $rapportText = implode(',', array_map(function (Rapport $rapport) {
                return $rapport->getErrorCode();
                return ErrorLevel::getErrorCodeLabel($rapport->getErrorCode());
            }, $toto));
            $texts[]     = sprintf("%s %d urls (%s)", $domaine, count($toto), $rapportText);
        }

        $embed = (new Embed())->setTitle('Liste des erreurs')->setDescription(implode("\n", $texts));

        $message = (new Message())
            ->setUsername('URL Checker')
            ->setContent($this->getMessageContent())
            ->addEmbed($embed)
        ;
        $discord->send($message);
    }

    private function getMessageContent()
    {
        $text = '';
        $text .= sprintf("%s => %s\n", ErrorLevel::NO_ERROR_CODE, ErrorLevel::NO_RESPONSE_TEXT);
        $text .= sprintf("%s => %s\n", ErrorLevel::RESPONSE_TIME_CODE, ErrorLevel::RESPONSE_TIME_TEXT);
        $text .= sprintf("%s => %s\n", ErrorLevel::BAD_CODE_CODE, ErrorLevel::BAD_CODE_TEXT);
        $text .= sprintf("%s => %s\n", ErrorLevel::NO_RESPONSE_CODE, ErrorLevel::NO_RESPONSE_TEXT);
        return $text;
    }
}