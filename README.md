# webpractik.sentry

## Описание
Модуль для отправки ошибков бэкенда в Sentry  
Класс модуля отнаследован от Bitrix\Main\Diag\ExceptionHandlerLog

## Установка
Устанавливаем через композер
```
composer require webpractik/sentry
```

Модуль устанавливается в папку bitrix/modules

При установке он заменит, либо дополнит данные в файле .settings и назначит дополнительным классом-обработчиком класс модуля

Править конфиги /webpractik.sentry/lang/ru/lib/exceptionmailer.php

