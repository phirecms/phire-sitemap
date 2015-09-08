<?php

namespace Phire\Sitemap\Controller;

use Phire\Sitemap\Model;
use Phire\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        if ($this->application->isRegistered('phire-content')) {
            $sitemap = (new Model\Sitemap())->getSitemap();
            $this->prepareView('sitemap.php');
            $this->view->urls      = $sitemap['urls'];
            $this->view->deepest   = $sitemap['deepest'];
            $this->view->frequency = $this->application->module('phire-sitemap')['frequency'];
            $this->send(200, ['Content-Type' => 'application/xml']);
        } else {
            $this->error();
        }
    }

    /**
     * Prepare view
     *
     * @param  string $template
     * @return void
     */
    protected function prepareView($template)
    {
        $this->viewPath = __DIR__ . '/../../view';
        parent::prepareView($template);
    }

}
