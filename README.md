# webpractik.sentry

## Описание

Модуль для отправки PHP ошибок Bitrix в Sentry  
Класс модуля отнаследован от Bitrix\Main\Diag\FileExceptionHandlerLog

## Требования
PHP >= 7.2

## Установка

Установка пакета
```bash
composer require webpractik/sentry
```

## Настройка

### Подключение composer autoload

В файле `init.php` требуется подключить composer autoload, если этого еще не сделано

```php
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
```

### Определение переменных

Для получения окружения и URL используется функция `getenv()`, поэтому в .env файле нужно определить две переменные:

```dotenv
APP_ENV=production
SENTRY_DSN=https://<key>@sentry.w6p.ru/<project>
```

> Чтобы при разработке на локальной версии сайта ошибки не отправлялись в Sentry, нужно в переменной APP_ENV указать значение 'local'. На production-сервер должно быть установлено 'production'

### Получение переменных из .env файла

Вместе с пакетом зависимостью устанавливается библиотека `vlucas/phpdotenv`, посредством которой можно получить переменные из .env файла

Для этого в `init.php` нужно прописать:

```php
if (class_exists('Dotenv\\Dotenv')) {
    $env = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'], '.environment');

    try {
        $env->load();
    } catch (InvalidFileException | InvalidPathException $e) {
    }
}
``` 

В метод `createImmutable` нужно указать путь к файлу .env (или .environment)  

> В примере указана проверка на существование класса Dotenv, чтобы при первом деплое на production-сервер не вызвать ошибку (пока не отработает composer install)


### Настройка Bitrix

Чтобы наш обработчик перехватывал ошибки, нужно его прописать в файле `bitrix/.settings.php`, в секцию  
`[exception_handling][value][log]`

```
'class_name' => '\\Webpractik\\Sentry\\SentryException'
```

Например:

```
'exception_handling' =>
    array (
        'value' =>
            array (
                'debug' => <bool>,
                'handled_errors_types' => <int>,
                'exception_errors_types' => <int>,
                'ignore_silence' => <bool>,
                'assertion_throws_exception' => <bool>,
                'assertion_error_type' => <int>,
                'log' =>
                    array (
                        'settings' =>
                            array (
                                'file' => '<path_to_error_log>/error.log',
                                'log_size' => <int>,
                            ),
                        'class_name' => '\\Webpractik\\Sentry\\SentryException',
                    ),
            ),
        'readonly' => <bool>,
    ),
```

## Миграция с версии 1.0

1. Удалить ключи `extension` и `required_file` из файла `bitrix/.settings.php`
2. В `class_name` изменить класс на `'\\Webpractik\\Sentry\\SentryException'`
3. Деактивировать и удалить модуль в админ панели
4. В файле `composer.json` изменить версию пакета `webpractik/sentry` на 2.0
5. Выполнить в консоли `composer update webpractik/sentry`
6. Сбросить кеш загрузчика composer, если возникнут ошибки `composer dump-autoload`
