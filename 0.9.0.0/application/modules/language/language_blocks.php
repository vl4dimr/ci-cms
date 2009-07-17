<?php
$this->set('get_language_links', 'language_links');

		
function language_links()
{
	$this->obj =& get_instance();
	return $this->obj->locale->get_active();
}
		

?>