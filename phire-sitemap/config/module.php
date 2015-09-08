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
        'frequency' => 'monthly'
    ]
];
