<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'cache' => array(
		'page_parts' => 86400,
		'front_page' => 86400,
		'tags' => 86400
	),
	
	'allowed_html_tags' => array(
		'b' => array(), 'strong' => array(),
		'i' => array(), 'em' => array(),
		'ol' => array('class' => 1), 'ul' => array('class' => 1), 'li' => array(),
		'p' => array('align' => 1, 'class' => 1),
		'br' => array(), 'hr' => array(),
		'h2' => array(), 'h3' => array(), 'h4' => array(),
		'a' => array('href' => 1, 'class' => 1),
		'blockquote' => array('class' => 1),
		'iframe' => array('width' => 1, 'height' => 1, 'src' => 1, 'allowfullscreen' => 1)
	),
);