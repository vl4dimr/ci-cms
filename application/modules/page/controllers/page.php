<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Page extends Controller {
		
		function Page()
		{
			parent::Controller();
			$this->output->enable_profiler(true);
			$this->template['module'] = "page";
			$this->load->model('page_model', 'pages');
		}
		
		//all available blocks
		function blocks () {
			
		}
		
		function index()
		{
			if ( $this->uri->segment(1) )
			{
				$num = 1;
				$built_uri = '';
				
				while ( $segment = $this->uri->segment($num))
				{
					$built_uri .= $segment.'/';
					$num++;
				}
				
				$new_length = strlen($built_uri) - 1;
				$built_uri = substr($built_uri, 0, $new_length);
			}
			else
			{
				$built_uri = $this->system->page_home;
			}
			
			if ( $this->template['page'] = $this->pages->get_page($built_uri) )
			{
				
				$view = 'index';
				
				$this->template['breadcrumb'][] = 	array(
														'title'	=> (strlen($this->template['page']['title']) > 20 )? substr($this->template['page']['title'], 0, 20) . '...': $this->template['page']['title'],
														'uri'	=> $this->template['page']['uri']
													);
				
				$this->template['title'] = $this->template['page']['title'];
													
				$this->template['meta_keywords'] 	= $this->template['page']['meta_keywords'];
				$this->template['meta_description'] = $this->template['page']['meta_description'];
													
			}
			else
			{
				// Make sure we send a 404 header
				
				$this->output->set_header("HTTP/1.0 404 Not Found");
				$view = '404';
			}
	
			$this->layout->load($this->template, $view);
		}
	}

?>