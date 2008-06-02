<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Admin extends Controller {
		
		function Admin()
		{

			parent::Controller();
			
			$this->load->library('administration');
			
			$this->template['module'] = "admin";
			$this->locale->load_textdomain(APPPATH . 'modules/'.$this->template['module'].'/locale/' . $this->session->userdata('lang') . '.mo' );
		}
		
		function index()
		{
			
			$this->load->library('simplepie');
			$this->simplepie->set_feed_url('http://ci-cms.blogspot.com/feeds/posts/default/-/news');
			$this->simplepie->set_cache_location(APPPATH.'cache/rss');
			$this->simplepie->init();
			$this->simplepie->handle_content_type();
			
			$this->template['blaze_news'] = $this->simplepie->get_items();
			
			$this->layout->load($this->template, 'index');
		}
		
		function login()
		{
			if ( $this->user->logged_in )
			{
				redirect('admin');
			}
			else
			{
				if ( !$this->input->post('submit') )
				{
					$this->layout->load($this->template, 'login');

				}
				else
				{
					$username = $this->input->post('username');
					$password = $this->input->post('password');
					
					if ($this->user->login($username, $password))
					{
						redirect('admin');
					}
					else
					{
						$this->layout->load($this->template, 'login');
					}
				}
			}
		}
		
		function logout()
		{
			$this->user->logout();
			redirect('admin/login');
		}
		
		function navigation()
		{

			$this->db->orderby('weight', "ASC");
			$query = $this->db->get('navigation');
			
			if ( $query->num_rows() > 0 )
			{
				$navigation = $query->result_array();
				
				foreach ($navigation as $nav)
				{
					if ( $nav['parent_id'] > 0 )
					{
						$this->template['navigation_children'][$nav['parent_id']][] = $nav;
					}
					else
					{
						$this->template['navigation'][] = $nav;
					}
				}
			}
			
			$this->layout->load($this->template, 'navigation/index');
		}
		
		function nav_ajax_reorder()
		{
			$i = 1;
			
			foreach($_POST['item'] as $key => $value):
			
				$data = array('weight' => $i);
				$this->db->where('id', $value);
				$query = $this->db->update('navigation', $data);
				$i++;
				
			endforeach;
			
			echo 'Update Complete';
		}
		
		function nav_ajax_add()
		{
			
		}
		
		function nav_ajax_delete()
		{
			
		}
		
		function settings()
		{
			if ( !$this->input->post('submit') )
			{
				
				$this->template['themes'] = $this->layout->get_themes();
				$this->template['available_partials'] = $this->layout->get_available_partials();
				$this->template['available_blocks'] = $this->layout->get_available_blocks();
				$this->template['blocks'] = $this->layout->get_blocks();
				$this->layout->load($this->template, 'settings');
			}
			else
			{

				$fields = array('site_name', 'meta_keywords', 'meta_description', 'cache', 'cache_time', 'theme');
				
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->system->set($field, $this->input->post($field));
					}
				}
				
				if ($this->input->post('cache') == 0)
				{
					$this->system->clear_cache();
				}
				
				$this->db->where('id >', 0);
				$this->db->delete('blocks');
				if ($blocks = $this->input->post('blocks'))
				{
					foreach( $blocks as $module => $modules )
					{
						foreach( $modules as $method => $area )
						{
							$block = 	array(
										'theme' 		=> $this->input->post('theme'),
										'area'		=> $area,
										'module'		=> $module,
										'method'		=> $method
										);
							$this->db->insert('blocks', $block);
						}										
					}
				}
				
			
				redirect('admin/settings');
			}
		}
	}

?>