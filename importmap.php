<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'intl-messageformat' => [
        'version' => '10.5.11',
    ],
    'tslib' => [
        'version' => '2.6.2',
    ],
    '@formatjs/icu-messageformat-parser' => [
        'version' => '2.7.6',
    ],
    '@formatjs/fast-memoize' => [
        'version' => '2.2.0',
    ],
    '@formatjs/icu-skeleton-parser' => [
        'version' => '1.8.0',
    ],
    '@symfony/ux-translator' => [
        'path' => './vendor/symfony/ux-translator/assets/dist/translator_controller.js',
    ],
    '@app/translations' => [
        'path' => './var/translations/index.js',
    ],
    '@app/translations/configuration' => [
        'path' => './var/translations/configuration.js',
    ],
];
