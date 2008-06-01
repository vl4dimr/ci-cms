<?php

	class Page_Partials {
		
		function Page_Partials()
		{
			$this->obj =& get_instance();
			
			$this->obj->load->model('page_model');
		}
		
		function new_pages()
		{
			$data['pages'] = $this->obj->page_model->new_pages(10);
			return $this->obj->load->view('partials/newpages', $data, true);
		}
		
	}

?>