<?='<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL; ?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <!-- created with the Phire Sitemap module for Phire CMS 2 https://github.com/phirecms -->
<?php if (count($urls) > 0): ?>
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?=$url['url']; ?></loc>
        <changefreq><?=$frequency; ?></changefreq>
        <priority><?=number_format(round(((($deepest + 1) - $url['depth']) / ($deepest + 1)), 2), 2); ?></priority>
    </url>
<?php endforeach; ?>
<?php endif; ?>
</urlset>
