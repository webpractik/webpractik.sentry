<?php

namespace Webpractik\Sentry;

use Bitrix\Main\Diag\ExceptionHandlerFormatter;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

class ExceptionMailer extends ExceptionHandlerLog
{
    private $URL;
    
    public function initialize(array $options){
        \Sentry\init(['dsn' => 'https://'.Loc::getMessage('KEY').'@sentry.w6p.ru/'.Loc::getMessage('NUMBER') ]);
    
    }
    
    public function write($exception, $logType)
    {
        \Sentry\init(['dsn' => 'https://'.Loc::getMessage('KEY').'@sentry.w6p.ru/'.Loc::getMessage('NUMBER') ]);
    }
}
