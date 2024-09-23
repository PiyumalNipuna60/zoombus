<?php

return [
    'supportedLocales' => [
        'ka' => ['name' => 'Geo', 'id' => 1],
        'en' => ['name' => 'Eng', 'id' => 2],
    ],
    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => true,
    'localesOrder' => [],
    'localesMapping' => [],
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),
    'urlsIgnored' => ['/admin/*'],
];
