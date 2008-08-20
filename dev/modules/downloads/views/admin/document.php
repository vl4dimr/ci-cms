<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Controller {

	function Admin()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'projects';
		$this->template['admin']		= true;
		$this->load->library('country');

	}
	
