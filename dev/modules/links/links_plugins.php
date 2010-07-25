<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');



$this->add_filter('member_profile_quick_menu', 'links_profile_quick_menu');


		
function links_profile_quick_menu($rows)
{
	$obj =& get_instance();
	if ($obj->user->logged_in )
	{
		$rows[__("Add a link", "links")] = site_url('links/link/create');
		return $rows;
	}
	else
	{
		return $rows;
	}
}


?>