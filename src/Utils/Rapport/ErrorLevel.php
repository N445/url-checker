<?php

namespace App\Utils\Rapport;
class ErrorLevel
{
    const CODE    = 'code';
    const LABEL   = 'label';
    const MESSAGE = 'message';


    const NO_ERROR      = 'no_error';
    const NO_ERROR_CODE = 0;
    const NO_ERROR_TEXT = 'Pas d\'erreur';

    const RESPONSE_TIME      = 'response_time';
    const RESPONSE_TIME_CODE = 1;
    const RESPONSE_TIME_TEXT = 'Temps de réponse trop dépassé';

    const BAD_CODE      = 'bad_code';
    const BAD_CODE_CODE = 2;
    const BAD_CODE_TEXT = 'Mauvais code de réponse';

    const NO_RESPONSE      = 'no_response';
    const NO_RESPONSE_CODE = 3;
    const NO_RESPONSE_TEXT = 'Pas de réponse';

    const CERTIFICATE      = 'certificate_error';
    const CERTIFICATE_CODE = 4;
    const CERTIFICATE_TEXT = 'Erreur de certificat';

    /**
     * @param int $errorCode
     * @return array
     */
    public static function getErrorMessage(int $errorCode): array
    {
        switch ($errorCode) {
            case self::RESPONSE_TIME_CODE:
                return [
                    self::LABEL   => self::RESPONSE_TIME,
                    self::MESSAGE => self::RESPONSE_TIME_TEXT,
                ];
                break;
            case self::BAD_CODE_CODE:
                return [
                    self::LABEL   => self::BAD_CODE,
                    self::MESSAGE => self::BAD_CODE_TEXT,
                ];
                break;
            case self::NO_RESPONSE_CODE:
                return [
                    self::LABEL   => self::NO_RESPONSE,
                    self::MESSAGE => self::NO_RESPONSE_TEXT,
                ];
                break;
            case self::CERTIFICATE_CODE:
                return [
                    self::LABEL   => self::CERTIFICATE,
                    self::MESSAGE => self::CERTIFICATE_TEXT,
                ];
                break;
            default:
                return [
                    self::LABEL   => self::NO_ERROR,
                    self::MESSAGE => self::NO_ERROR_TEXT,
                ];
        }
    }
    /**
     * @param int $errorCode
     * @return string
     */
    public static function getErrorCodeLabel(int $errorCode): string
    {
        switch ($errorCode) {
            case self::RESPONSE_TIME_CODE:
                return self::NO_ERROR_TEXT;
                break;
            case self::BAD_CODE_CODE:
                return self::RESPONSE_TIME_TEXT;
                break;
            case self::NO_RESPONSE_CODE:
                return self::BAD_CODE_TEXT;
                break;
            default:
                return self::NO_RESPONSE_TEXT;
        }
    }
}