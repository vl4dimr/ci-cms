<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Page extends Controller {
		
		function Page()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "page";
			$this->load->model('page_model', 'pages');
			$this->user->lang = $this->session->userdata('lang');
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
			
			if ( $page = $this->pages->get_page(array('uri' => $built_uri, 'lang' => $this->user->lang)) )
			{
				
				if ($page['active'] == 1)
				{
				
					$this->template['page'] = $page;
					$view = 'index';
					
					$this->template['breadcrumb'][] = 	array(
															'title'	=> (strlen($this->template['page']['title']) > 20 )? substr($this->template['page']['title'], 0, 20) . '...': $this->template['page']['title'],
															'uri'	=> $this->template['page']['uri']
														);
					
					$this->template['title'] = $this->template['page']['title'];
														
					$this->template['meta_keywords'] 	= $this->template['page']['meta_keywords'];
					$this->template['meta_description'] = $this->template['page']['meta_description'];
					//page hit
					if ($this->session->userdata('page'.$page['id']) != $page['id'])
					{
						$this->session->set_userdata('page'.$page['id'], $page['id']);
						$this->db->where('id', $page['id']);
						$this->db->set('hit', 'hit+1', FALSE);
						$this->db->update('pages');
						$this->cache->remove('pagelist'.$this->user->lang, 'page');
					}
				}
				else
				{
					$this->output->set_header("HTTP/1.0 403 Forbidden");
					$view = '403';
				}
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
