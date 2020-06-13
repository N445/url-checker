<?php

namespace App\Utils\Rapport;
class ErrorLevel
{
    const NO_ERROR      = 'no_error';
    const RESPONSE_TIME = 'response_time';
    const BAD_CODE      = 'bad_code';
    const NO_RESPONSE   = 'no_response';

    public static function getErrorCodeLabel(string $errorCode): string
    {
        switch ($errorCode) {
            case self::RESPONSE_TIME:
                return 'Temps de réponse trop dépassé';
                break;
            case self::BAD_CODE:
                return 'Mauvais code de réponse';
                break;
            case self::NO_RESPONSE:
                return 'Pas de réponse';
                break;
            default:
                return 'Pas d\'erreur';
        }
    }
}