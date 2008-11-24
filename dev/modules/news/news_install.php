<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!is_dir('./media/captcha'))
{
	@mkdir('./media/captcha');
}		
@mkdir('./media/images');
@mkdir('./media/images/s');
@mkdir('./media/images/m');
@mkdir('./media/images/o');

$news_settings = serialize(array(
	'allow_comments' => 1,
	'approve_comments' => 1
	));
	
$this->system->set('news_settings', $news_settings);


?>