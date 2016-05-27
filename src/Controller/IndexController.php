<?php
/**
 * Phire Sitemap Module
 *
 * @link       https://github.com/phirecms/phire-sitemap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Sitemap\Controller;

use Phire\Sitemap\Model;
use Phire\Controller\AbstractController;

/**
 * Sitemap Index Controller class
 *
 * @category   Phire\Sitemap
 * @package    Phire\Sitemap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
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
            $sitemap = (new Model\Sitemap())->getSitemap($this->application->module('phire-sitemap')['type_id']);
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
