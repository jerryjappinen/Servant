<?php

/**
* Servant's home site main frame
*
* NESTED TEMPLATES
*	html
*	list-toplevelpages
*	list-submenu
*
* CONTENT PARAMETERS
*	0: Body content (string)
*	1: Current page (ServantPage)
*	2: usePageAssets (truy or falsy)		// FLAG this is true if $page is not available?
*/

$page = $template->content(1);
$usePageAssets = $template->content(2) ? true : false;

?>