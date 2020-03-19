<?php

namespace Webpractik\Sentry;

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\FileExceptionHandlerLog;

use function Sentry\captureException;
use function Sentry\init;

/**
 * Class SentryException
 */
class SentryException extends FileExceptionHandlerLog
{
    public $level;

    /**
     * Запись ошибки в лог и отправка в Sentry
     *
     * @param $exception
     * @param $logType
     */
    public function write($exception, $logType)
    {
        // фильтруем ошибки вида NOTICE
        if ($logType === ExceptionHandlerLog::LOW_PRIORITY_ERROR) {
            return;
        }

        // отправляем в sentry
        $this->sendToSentry($exception);

        // вызываем родительский метод записи в лог
        parent::write($exception, $logType);
    }

    /**
     * @param array $options
     */
    public function initialize(array $options)
    {
        $this->level = $this->getSettingsErrorLevel();
        $this->initSentry();
        parent::initialize($options);
    }

    /**
     * Инициализация подключения к Sentry
     */
    public function initSentry():void
    {
        $environment = getenv('APP_ENV');

        // инициализация Sentry
        if ($environment && $environment !== 'local' && function_exists('Sentry\init')) {
            init(
                [
                    'dsn'         => getenv('SENTRY_DSN'),
                    'environment' => $environment,
                    'error_types' => $this->level
                ]
            );
        }
    }

    /**
     * Отправка уведомления в Sentry
     *
     * @param \Throwable $exception
     */
    public function sendToSentry(\Throwable $exception):void
    {
        captureException($exception);
    }

    /**
     * Получить битовую маску отлавливаемых ошибок из конфига битрикс
     *
     * @return int
     */
    public function getSettingsErrorLevel():int
    {
        $exceptionHandling = Configuration::getValue('exception_handling');

        return $exceptionHandling['handled_errors_types'] ?? 4437;
    }
}
