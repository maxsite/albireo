<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) MaxSite CMS, https://max-3000.com/
 * 
 */

/**
 *  сжатие HTML
 * 
 *  @param $text входной текст
 *  @return string
 */
function compress_html($text)
{
	// защищенный текст
	$text = preg_replace_callback('!(<pre.*?>)(.*?)(</pre>)!is', '_mso_protect_pre', $text);
	$text = preg_replace_callback('!(<code.*?>)(.*?)(</code>)!is', '_mso_protect_pre', $text);
	$text = preg_replace_callback('!(<script.*?>)(.*?)(</script>)!is', '_mso_protect_script', $text);
	$text = preg_replace_callback('!(<style.*?>)(.*?)(</style>)!is', '_mso_protect_script', $text);

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
 *  pre, которое загоняется в [html_base64]
 *  callback
 * 
 *  @param $matches matches
 *  @return string
 */
function _mso_protect_pre($matches)
{
	$text = trim($matches[2]);

	$text = str_replace('<p>', '', $text);
	$text = str_replace('</p>', '', $text);
	$text = str_replace('[', '&#91;', $text);
	$text = str_replace(']', '&#93;', $text);
	$text = str_replace("<br>", "\n", $text);
	$text = str_replace("<br />", "<br>", $text);
	$text = str_replace("<br/>", "<br>", $text);
	$text = str_replace("<br>", "\n", $text);

	$text = str_replace('<', '&lt;', $text);
	$text = str_replace('>', '&gt;', $text);
	$text = str_replace('&lt;pre', '<pre', $text);
	$text = str_replace('&lt;/pre', '</pre', $text);
	$text = str_replace('pre&gt;', 'pre>', $text);

	$text = $matches[1] . "\n" . '[html_base64]' . base64_encode($text) . '[/html_base64]' . $matches[3];

	return $text;
}

/**
 *  script и style, которые загоняются в [html_base64]
 *  callback
 * 
 *  @param $matches matches
 *  @return string
 */
function _mso_protect_script($matches)
{
	$text = trim($matches[2]);
	$text = $matches[1] . '[html_base64]' . base64_encode($text) . '[/html_base64]' . $matches[3];

	return $text;
}

# end of file
