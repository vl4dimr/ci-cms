<?php

	class Language_Partials {
		
		function Language_Partials()
		{
			$this->obj =& get_instance();
		}
		
		function language_links()
		{
			$data['langs'] = $this->obj->locale->get_active();
			return $this->obj->load->view('partials/language_links', $data, true);
		}
		
	}

?>