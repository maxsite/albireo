<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

layout: empty.php
slug: sitemap.xml
compress: 0
parser: -
sitemap: -

**/

# https://you-site/sitemap.xml

/**
  * для изменения можно использовать параметры страницы:
  * sitemap-changefreq: monthly
  * sitemap-priority: 0.9
  *
  * исключить страницу из sitemap.xml
  * sitemap: -
  * 
**/
$changefreqDef = 'monthly';
$priorityDef = '0.5';

$pagesInfo = getVal('pagesInfo');

$out = '';

foreach($pagesInfo as $file => $info) {
	
	$slug = $info['slug'];
	
	if ($slug == '404') continue; // исключить 404
	if (isset($info['method']) and strtoupper($info['method']) !== 'GET') continue;
	if (isset($info['sitemap']) and $info['sitemap'] == '-') continue;
	
	$changefreq = $info['sitemap-changefreq'] ?? $changefreqDef;
	$priority = $info['sitemap-priority'] ?? $priorityDef;
		
	if ($slug == '/') $slug = ''; // главная
	
	$out .= '<url>' . "\n";
	$out .= '<loc>' . rtrim(SITE_URL . $slug, '/') . '</loc>' . "\n";
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