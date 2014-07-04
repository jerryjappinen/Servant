<?php

/**
* Main template for a site with menus, pushed into HTML body
*
* NESTED TEMPLATES
*	html
*	list-toplevelpages
*	list-submenu
*
* CONTENT PARAMETERS
*	0: Body content (string)
*	1: Current page (ServantPage)
*/

// Select a page or root node
$page = $template->content(1);
if (!$page) {
	$page = $servant->sitemap()->root();
}

// Variables used in this template
$mainmenu = array();
$submenu = '';
$sectionMenus = array();
$output = '';

?>