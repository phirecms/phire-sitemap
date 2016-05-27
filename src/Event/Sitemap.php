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
namespace Phire\Sitemap\Event;

use Phire\Sitemap\Model;
use Pop\Application;
use Phire\Controller\AbstractController;

/**
 * Sitemap Event class
 *
 * @category   Phire\Sitemap
 * @package    Phire\Sitemap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Sitemap
{

    /**
     * Init the sitemap model
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function init(AbstractController $controller, Application $application)
    {
        if ($controller->hasView()) {
            $controller->view()->phire->sitemap = new Model\Sitemap();
        }
    }

    /**
     * Parse view object
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function parseSitemap(AbstractController $controller, Application $application)
    {
        if (($controller->hasView()) && ($controller instanceof \Phire\Content\Controller\IndexController)) {
            $body = $controller->response()->getBody();
            if (strpos($body, '[{sitemap') !== false) {
                $sitemaps = [];
                preg_match_all('/\[\{sitemap.*\}\]/', $body, $sitemaps);

                if (isset($sitemaps[0]) && isset($sitemaps[0][0])) {
                    foreach ($sitemaps[0] as $sitemap) {
                        $node = 'nav';
                        $tid = substr($sitemap, (strpos($sitemap, 'sitemap') + 7));
                        $tid = str_replace('}]', '', $tid);
                        if ($tid != '') {
                            if (substr($tid, 0, 1) == '_') {
                                $tid =substr($tid, 1);
                            }
                            if (strpos($tid, '_') !== false) {
                                $tidAry = explode('_', $tid);
                                $node = $tidAry[0];
                                $tid  = $tidAry[1];
                            } else {
                                $node = $tid;
                                $tid  = null;
                            }
                        } else {
                            $tid = null;
                        }
                        $siteMapObject = (new Model\Sitemap())->getSitemapObject($node, $tid);
                        $body = str_replace($sitemap, $siteMapObject, $body);
                    }
                }

                $controller->response()->setBody($body);
            }
        }
    }

}