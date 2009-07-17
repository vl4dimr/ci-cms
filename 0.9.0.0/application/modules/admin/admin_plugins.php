<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$this->add_filter('member_profile_quick_menu', 'admin_profile_quick_menu');


		
function admin_profile_quick_menu($rows)
{
	$obj =& get_instance();
	if (count($obj->user->level) >0 )
	{
		$rows[__("Admin interface", "admin")] = site_url('admin');
		return $rows;
	}
	else
	{
		return $rows;
	}
}

?>