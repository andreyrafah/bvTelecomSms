<?php
namespace Andreyrafah\BvTelecomSms;

use Throwable;

class InvalidNumberException extends \Exception
{
    public function __construct($message, $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
