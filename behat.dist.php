<?php

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Filter\TagFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Behatch\Extension as BehatchExtension;

return (new Config())
    ->withProfile((new Profile('default'))
        ->withExtension(new Extension(MinkExtension::class, [
            'base_url' => 'http://localhost:8080',
            'files_path' => '%paths.base%/tests/fixtures/files',
            'sessions' => [
                'default' => [
                    'browserkit_http' => null,
                ],
                'symfony2' => [
                    'selenium2' => [
                        'browser' => 'chrome',
                        'capabilities' => [
                            'browserName' => 'chrome',
                            'chrome' => [
                                'switches' => [
                                    '--headless',
                                    '--disable-gpu',
                                    '--no-sandbox',
                                ],
                            ],
                            'extra_capabilities' => [
                                'goog:chromeOptions' => [
                                    'w3c' => false,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]))
        ->withExtension(new Extension(BehatchExtension::class))
        ->withSuite((new Suite('default'))
            ->addContext(MinkContext::class)
            ->addContext(
                'behatch:context:browser',
                [
                    'timeout' => 1,
                ]
            )
            ->addContext(
                'behatch:context:debug',
                [
                    'screenshotDir' => '.',
                ]
            )
            ->addContext(
                'behatch:context:json',
                [
                    'evaluationMode' => 'javascript',
                ]
            )
            ->addContext('behatch:context:rest')
            ->addContext(
                'behatch:context:system',
                [
                    'root' => '.',
                ]
            )
            ->addContext('behatch:context:table')
            ->addContext('behatch:context:xml')
            ->withPaths('%paths.base%/tests/features')
            ->withFilter(new TagFilter('~@user'))))
    ->withProfile((new Profile('symfony2'))
        ->withExtension(new Extension(MinkExtension::class, [
            'default_session' => 'symfony2',
        ]))
        ->withSuite((new Suite('default'))
            // Ignore @statusCode and @rest tags because Selenium2Driver does not support headers or status code (https://github.com/php-webdriver/php-webdriver/issues/811)
            // Ignore @json and @xml tags because response is wrapped inside html tags
            ->withFilter(new TagFilter('~@user&&~@statusCode&&~@rest&&~@json&&~@xml'))));
