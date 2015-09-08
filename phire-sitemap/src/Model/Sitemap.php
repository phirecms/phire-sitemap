<?php

namespace Phire\Sitemap\Model;

use Phire\Model\AbstractModel;

class Sitemap extends AbstractModel
{

    /**
     * Get content for sitemap
     *
     * @return array
     */
    public function getSitemap()
    {
        $urls = [];
        $content = \Phire\Content\Table\Content::findBy([
            'status' => 1,
            'roles'  => 'a:0:{}'
        ], ['order' => 'order, id ASC'])->rows();

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

}