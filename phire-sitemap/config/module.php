<?php
/**
 * Module Name: phire-sitemap
 * Author: Nick Sagona
 * Description: This is the sitemap module for Phire CMS 2
 * Version: 1.0
 */
return [
    'phire-sitemap' => [
        'prefix'     => 'Phire\Sitemap\\',
        'src'        => __DIR__ . '/../src',
        'routes'     => [
            '/sitemap.xml' => [
                'controller' => 'Phire\Sitemap\Controller\IndexController',
                'action'     => 'index',
            ]
        ],
        'events' => [
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Sitemap\Event\Sitemap::init',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.post',
                'action'   => 'Phire\Sitemap\Event\Sitemap::parseSitemap',
                'priority' => 1000
            ]
        ],
        'type_id'   => null,
        'frequency' => 'monthly'
    ]
];
