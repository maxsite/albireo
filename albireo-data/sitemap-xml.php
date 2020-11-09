<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

layout: empty.php
slug: sitemap.xml
compress: 0
parser: -

**/

# https://you-site/sitemap.xml

$changefreq = 'monthly';
$priority = '0.8';

$pagesInfo = getVal('pagesInfo');

$out = '';

foreach($pagesInfo as $file => $info) {
	$out .= '<url>' . "\n";
	$out .= '<loc>' . SITE_URL . $info['slug'] . '/</loc>' . "\n";
	$out .= '<lastmod>' . date('Y-m-d', filemtime($file)) . '</lastmod>' . "\n";
	$out .= '<changefreq>' . $changefreq . '</changefreq>' . "\n";
	$out .= '<priority>' . $priority . '</priority>' . "\n";
	$out .= '</url>' . "\n";
}

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo $out;
echo '</urlset>';

/*
// https://www.sitemaps.org/ru/protocol.html

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>http://www.example.com/</loc>
      <lastmod>2005-01-01</lastmod>
      <changefreq>monthly</changefreq>
      <priority>0.8</priority>
   </url>
</urlset> 

*/