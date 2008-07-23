<?php

	$route['news/list(/:any)?'] = 'news/index';
	$route['news/comment(/:any)?'] = 'news/comment';
	$route['news/(:any)'] = 'news/read/$1';

?>