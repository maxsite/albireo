<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) MaxSite CMS, https://max-3000.com/
 */

/**
 *  сжатие HTML
 * 
 *  @param $text входной текст
 *  @return string
 */
function compress_html($text)
{
	// защищенный текст, который не нужно сжимать
	$text = preg_replace_callback('!(<pre.*?>)(.*?)(</pre>)!is', '_compress_html_protect', $text);
	$text = preg_replace_callback('!(<code.*?>)(.*?)(</code>)!is', '_compress_html_protect', $text);
	$text = preg_replace_callback('!(<script.*?>)(.*?)(</script>)!is', '_compress_html_protect', $text);
	$text = preg_replace_callback('!(<style.*?>)(.*?)(</style>)!is', '_compress_html_protect', $text);

	// сжатие
	$text = str_replace("\r", "", $text);
	$text = str_replace("\t", ' ', $text);
	$text = str_replace("\n   ", "\n", $text);
	$text = str_replace("\n  ", "\n", $text);
	$text = str_replace("\n ", "\n", $text);
	$text = str_replace("\n", '', $text);
	$text = str_replace('   ', ' ', $text);
	$text = str_replace('  ', ' ', $text);

	// специфичные замены
	$text = str_replace('<!---->', '', $text);
	$text = str_replace('>    <', '><', $text);
	$text = str_replace('>   <', '><', $text);
	$text = str_replace('>  <', '><', $text);

	$text = preg_replace_callback('!\[html_base64\](.*?)\[\/html_base64\]!is', function ($m) {
		return base64_decode($m[1]);
	}, $text);

	return $text;
}

/**
 *  script и style, которые загоняются в [html_base64]
 *  callback-функция
 * 
 *  @param $matches matches
 *  @return string
 */
function _compress_html_protect($m)
{
	return $m[1] . '[html_base64]' . base64_encode($m[2]) . '[/html_base64]' . $m[3];
}

# end of file
