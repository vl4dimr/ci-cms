<?php

	$route['news/list(/:any)?'] = 'news/index$1';
	$route['news/comment(/:any)?'] = 'news/comment';
	$route['news/cat(/:any)?'] = 'news/cat$1';
	$route['news/tag(/:any)?'] = 'news/tag$1';
	$route['news/rss(/:any)?'] = 'news/rss$1';
	$route['news/(:any)'] = 'news/read/$1';

?>