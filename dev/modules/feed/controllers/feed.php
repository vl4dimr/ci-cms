<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/
 
 class Feed extends Controller {
 
 	function Feed()
	{
		parent::Controller();
	

		$this->template['module']	= 'feed';
		$this->settings = isset($this->system->tags_settings) ? unserialize($this->system->tags_settings) : null;

		$this->load->library('cache');

	}
	

	function index($tag = null, $start = null)
	{

	}

	
}