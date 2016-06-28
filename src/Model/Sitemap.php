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
namespace Phire\Sitemap\Model;

use Phire\Model\AbstractModel;
use Pop\Nav\Nav;

/**
 * Sitemap Model class
 *
 * @category   Phire\Sitemap
 * @package    Phire\Sitemap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Sitemap extends AbstractModel
{

    /**
     * Get content for sitemap
     *
     * @param  int   $tid
     * @return array
     */
    public function getSitemap($tid = null)
    {
        $urls = [];
        if (null !== $tid) {
            if (is_array($tid)) {
                $content = [];
                foreach ($tid as $t) {
                    $c = \Phire\Content\Table\Content::findBy([
                        'type_id' => (int)$t,
                        'status'  => 1,
                        'roles'   => 'a:0:{}'
                    ], ['order' => 'order, id ASC'])->rows();
                    $content = array_merge($content, $c);
                }
            } else {
                $content = \Phire\Content\Table\Content::findBy([
                    'type_id' => (int)$tid,
                    'status'  => 1,
                    'roles'   => 'a:0:{}'
                ], ['order' => 'order, id ASC'])->rows();
            }
        } else {
            $content = \Phire\Content\Table\Content::findBy([
                'status' => 1,
                'roles'  => 'a:0:{}'
            ], ['order' => 'order, id ASC'])->rows();
        }

        $deepest = 0;

        foreach ($content as $c) {
            $depth = ($c->hierarchy != '') ? count(explode('|', $c->hierarchy)) : 0;
            if ($depth > $deepest) {
                $deepest = $depth;
            }
            $urls[] = [
                'url'   => (($c->force_ssl) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . BASE_PATH . $c->uri,
                'depth' => $depth
            ];
        }

        return [
            'urls'    => $urls,
            'deepest' => $deepest
        ];
    }

    /**
     * Get content for sitemap as a nav object
     *
     * @param  string $node
     * @param  int    $tid
     * @return array
     */
    public function getSitemapObject($node = 'nav', $tid = null)
    {
        $tree       = [];
        $content    = new \Phire\Content\Model\Content();
        $contentAry = $content->getAll($tid, 'order ASC');

        foreach ($contentAry as $c) {
            $branch = [
                'id'       => $c->id,
                'type'     => 'content',
                'name'     => $c->title,
                'href'     => $c->uri,
                'children' => ((isset($c->status) && $c->status == 1) || !isset($c->status)) ?
                    $this->getNavChildren($c, 0, false) : []
            ];

            if (isset($c->roles)) {
                $roles = unserialize($c->roles);
                if (count($roles) > 0) {
                    $branch['acl'] = [
                        'resource' => 'content-' . $c->id
                    ];
                }
            }

            if ((isset($c->status) && $c->status == 1) || !isset($c->status)) {
                $tree[] = $branch;
            }
        }

        $config = [
            'top' => [
                'node'  => 'nav',
                'id'    => 'sitemap',
                'class' => 'sitemap'
            ],
            'parent' => [
                'node' => 'nav',
            ],
            'child' => [
                'node' => 'nav',
            ]
        ];

        if ($node == 'ul') {
            $config['top']['node']    = 'ul';
            $config['parent']['node'] = 'ul';
            $config['child']['node']  = 'li';
        } else if ($node == 'ol') {
            $config['top']['node']    = 'ol';
            $config['parent']['node'] = 'ol';
            $config['child']['node']  = 'li';
        }

        return new Nav($tree, $config);
    }

    /**
     * Get navigation children
     *
     * @param  mixed   $content
     * @param  int     $depth
     * @param  boolean $cat
     * @return array
     */
    protected function getNavChildren($content, $depth = 0, $cat = false)
    {
        $children = [];
        $child    = \Phire\Content\Table\Content::findBy(['parent_id' => $content->id], ['order' => 'order ASC']);

        if ($child->hasRows()) {
            foreach ($child->rows() as $c) {
                $branch = [
                    'id'       => $c->id,
                    'type'     => ($cat) ? 'category' : 'content',
                    'name'     => $c->title,
                    'href'     => $c->uri,
                    'children' => ((isset($c->status) && $c->status == 1) || !isset($c->status)) ?
                        $this->getNavChildren($c, ($depth + 1)) : []
                ];

                if (isset($c->roles)) {
                    $roles = unserialize($c->roles);
                    if (count($roles) > 0) {
                        $branch['acl'] = [
                            'resource' => 'content-' . $c->id
                        ];
                    }
                }

                if ((isset($c->status) && $c->status == 1) || !isset($c->status)) {
                    $children[] = $branch;
                }
            }
        }

        return $children;
    }

}