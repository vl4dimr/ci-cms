<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Language extends Controller {
		
		function Language()
		{
			parent::Controller();
			
			$this->template['module'] = "language";
					
		}
		
		//all available blocks
		function set () 
		{

			$lang = $this->uri->segment(1);
			if (in_array($lang, $this->locale->codes)) 
			{
				$this->session->set_userdata('lang', $lang);
			} 
			else
			{
				$this->session->set_userdata('lang', $this->locale->default);
			}
			$redirect = str_replace("/" . $lang , "", $this->uri->uri_string());

			redirect($redirect);
		}
	}
?>