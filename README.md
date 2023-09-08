# polyglot/template-provider-php-file

> A simple [polyglot](https://packagist.org/packages/polyglot/) simple PHP File template provider.

# Install

```shell
composer require polyglot/template-provider-php-file:^1.0
```

# Using

Create translation files:
```php
<?php
// /path/to/translations/messages/en_US.php

return [
    'no_deep' => '"no_deep" template in "messages" domain (en_US)',
    'foo' => [
        'bar' => '"foo.bar" template in "messages" domain (en_US)',
        'baz' => '"foo.baz" template in "messages" domain (en_US)',
    ],
    'h' => [
        'e' => [
            'l' => [
                'l' => [
                    'o' => '"h.e.l.l.o" template in "messages" domain (en_US)',
                ],
            ],
        ],
    ],
];
```

```php
<?php
// /path/to/translations/messages/ru_RU.php

return [
    'no_deep' => '"no_deep" шаблон в домене "messages" (ru_RU)',
    'foo' => [
        'bar' => '"foo.bar" шаблон в домене "messages" (ru_RU)',
        'baz' => '"foo.baz" шаблон в домене "messages" (ru_RU)',
    ],
    'h' => [
        'e' => [
            'l' => [
                'l' => [
                    'o' => '"h.e.l.l.o" шаблон в домене "messages" (ru_RU)',
                ],
            ],
        ],
    ],
];
```

```php
<?php

$directory = '/path/to/translations';
$provider = new \Polyglot\PhpFileTemplateProvider\PhpFileTemplateProvider($directory);

$provider->get('messages', 'no_deep', 'en_US'); // returns '"no_deep" template in "messages" domain (en_US)'
$provider->get('messages', 'foo.bar', 'en_US'); // returns '"foo.bar" template in "messages" domain (en_US)'
$provider->get('messages', 'foo.baz', 'en_US'); // returns '"foo.baz" template in "messages" domain (en_US)'
$provider->get('messages', 'h.e.l.l.o', 'en_US'); // returns '"h.e.l.l.o" template in "messages" domain (en_US)'
$provider->get('messages', 'no_deep', 'ru_RU'); // returns '"no_deep" шаблон в домене "messages" (ru_RU)'
$provider->get('messages', 'foo.bar', 'ru_RU'); // returns '"foo.bar" шаблон в домене "messages" (ru_RU)'
$provider->get('messages', 'foo.baz', 'ru_RU'); // returns '"foo.baz" шаблон в домене "messages" (ru_RU)'
$provider->get('messages', 'h.e.l.l.o', 'ru_RU'); // returns '"h.e.l.l.o" шаблон в домене "messages" (ru_RU)'

// throws Polyglot\Contract\TemplateProvider\Exception\TemplateNotFound exception
$provider->get('messages', 'h.e.l.l.o', 'fr_FR'); 

```
