<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\ModuleManager;

/**
 * Class webpractik_sentry
 */
Class webpractik_sentry extends \CModule
{
    
    private static $arParametersForFullInstall = [
        'debug' => false,
        'handled_errors_types' => 4437,
        'exception_errors_types' => 4437,
        'ignore_silence' => false,
        'assertion_throws_exception' => true,
        'assertion_error_type' => 256,
        'log' =>
            array (
                'settings' =>
                    array (
                        'file' => 'local/php_interface/error.log',
                        'log_size' => 1000000,
                    ),
                'class_name' => '\\Webpractik\\Sentry\\ExceptionMailer',
                'extension' => '',
                'required_file' => '/modules/webpractik.sentry/lib/exceptionmailer.php',
            ),
    ];
    
    private static $arParametersForPartialInstall = [
        'class_name' => '\\Webpractik\\Sentry\\ExceptionMailer',
        'extension' => '',
        'required_file' => '/modules/webpractik.sentry/lib/exceptionmailer.php',
    ];
    /**
     * webpractik_sentry constructor.
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_NAME         = 'Webpractik';
        $this->MODULE_DESCRIPTION  = 'Модуль для отправки логов в sentry';
        $this->MODULE_ID           = 'webpractik.sentry';
        $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME        = 'Webpractik';
        $this->PARTNER_URI         = 'https://webpractik.ru';
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->addPointToSettings();
    }

    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
    
    public function addPointToSettings()
    {
        $configuration = Configuration::getInstance();
    
        if(empty($configuration['exception_handling']) || (int)$configuration['exception_handling']['handled_errors_types'] !== 4437) {
            $configuration->add('exception_handling', self::$arParametersForFullInstall);
            $configuration->saveConfiguration();
            
        } else {
            $fconf = $configuration['exception_handling'];
            $log = array_merge($configuration['exception_handling']['log'], self::$arParametersForPartialInstall);
            $fconf['log'] = $log;
            $configuration->add('exception_handling', $fconf);
            $configuration->saveConfiguration();
        }
    }
}
