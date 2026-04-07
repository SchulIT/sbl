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
        'path' => './assets/js/app.js',
        'entrypoint' => true,
    ],
    'checkout' => [
        'path' => './assets/js/checkout.js',
        'entrypoint' => true,
    ],
    'import' => [
        'path' => './assets/js/import.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/all.css' => [
        'version' => '7.2.0',
        'type' => 'css',
    ],
    'choices.js' => [
        'version' => '11.2.1',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'axios' => [
        'version' => '1.14.0',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
];
