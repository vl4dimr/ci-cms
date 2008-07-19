<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Admin extends Controller {
		
		var $nav;
		function Admin()
		{

			parent::Controller();
			
			$this->load->library('administration');
			$this->lang = $this->session->userdata('lang');
			$this->check_latest_version();
			$this->template['module'] = "admin";
		}
		
		function index()
		{
			if (!isset($this->user->level) ||  $this->user->level == 0)
			{
				redirect('admin/unauthorized/admin/1');
			}
			$this->load->library('simplepie');
			$this->simplepie->set_feed_url('http://ci-cms.blogspot.com/feeds/posts/default/-/news');
			$this->simplepie->set_cache_location('./cache');
			$this->simplepie->init();
			$this->simplepie->handle_content_type();
			
			$this->template['blaze_news'] = $this->simplepie->get_items();
			
			$this->layout->load($this->template, 'index');
		}

		function check_latest_version()
		{

			if(!($data = $this->cache->get('latest_version', 'dashboard')))
			{
				
				ini_set('default_socket_timeout', 1);
				$data = @file_get_contents("http://ci-cms.googlecode.com/svn/trunk/application/version.txt");
				if (!$data) $data = $this->system->version;
				$this->cache->save('latest_version', $data, 'dashboard', 86400);

			}
		
			$this->latest_version = $data;
			
		}
		
		function unauthorized($module, $level)
		{
			$this->template['data']  = array('module' => $module, 'level' => $level);
			$this->layout->load($this->template, 'unauthorized');
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
					
						if ($this->input->post('redirect')) 
						{
							redirect($this->input->post('redirect'));
							return;
						}					
						redirect('admin');
					}
					else
					{
						if ($this->input->post('redirect')) 
						{
							$this->session->set_flashdata('redirect', $this->input->post('redirect'));
						}
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
		
		function navigation($action = null)
		{
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			switch ($action) 
			{
				case 'move':
					$direction = $this->uri->segment(4);
					$id = $this->uri->segment(5);
					if (!isset($direction) || !isset($id))
					{
						redirect('admin/navigation');
					}
					
					$query = $this->db->get_where('navigation', array('id' => $id));
					
					
					if ($row = $query->row())
					{
						$parent_id = $row->parent_id;
						
					}
					else
					{
						$parent_id = 0;
					}
					
					
					$move = ($direction == 'up') ? -1 : 1;
					$this->db->where(array('id' => $id));
					
					$this->db->set('weight', 'weight+'.$move, FALSE);
					$this->db->update('navigation');
					
					$this->db->where(array('id' => $id));
					$query = $this->db->get('navigation');
					$row = $query->row();
					$new_ordering = $row->weight;
					
					
					if ( $move > 0 )
					{
						$this->db->set('weight', 'weight-1', FALSE);
						$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'parent_id' => $parent_id, 'lang' => $this->lang));
						$this->db->update('navigation');
					}
					else
					{
						$this->db->set('weight', 'weight+1', FALSE);
						$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'parent_id' => $parent_id, 'lang' => $this->lang);
						
						$this->db->where($where);
						$this->db->update('navigation');
					}
					//reordinate
					$i = 0;
					$this->db->order_by('weight');
					$this->db->where(array('parent_id' => $parent_id, 'lang' => $this->lang));
					
					$query = $this->db->get('navigation');
					
					if ($rows = $query->result())
					{
						foreach ($rows as $row)
						{
							$this->db->set('weight', $i);
							$this->db->where('id', $row->id);
							$this->db->update('navigation');
							$i++;
						}
					}
					//clear cache
					$this->cache->remove_group('navigation');
					redirect('admin/navigation');					
					
					
					
				break;
				case 'create':
					
					if ($this->input->post('submit'))
					{
						$data = array(
							'lang' => $this->input->post('lang'),
							'parent_id' => $this->input->post('parent_id'),
							'active' => $this->input->post('status'),
							'title' => $this->input->post('title'),
							'uri' => $this->input->post('uri')
						);
						
						$this->db->set($data);
						$this->db->insert('navigation');
						
						$this->session->set_flashdata('notification', __("Navigation item saved"));
						$this->cache->remove_group('navigation');
						redirect('admin/navigation');
					}
					else
					{
						$this->template['parents'] = $this->nav_get();
							
						$this->layout->load($this->template, 'navigation/create');
					}
				break;
				case 'delete':
					$id = $this->uri->segment(4);
					if (!isset($id))
					{
						$this->session->set_flashdata('notification', __("Please select a menu"));
						redirect('admin/navigation');	
					}
					else
					{
						$this->db->where('id', $id);
						$this->db->delete('navigation');
						$this->session->set_flashdata('notification', __("Navigation item deleted"));
						$this->cache->remove_group('navigation');
						redirect('admin/navigation');
					}
				break;
				case 'edit':
					$id = $this->uri->segment(4);
					if (!isset($id))
					{
						$this->session->set_flashdata('notification', __("Please select a menu"));
						redirect('admin/navigation');						
					}
					
					if ($this->input->post('submit'))
					{
						$this->db->where('id', $id);
						$data = array(
							'parent_id' => $this->input->post('parent_id'),
							'active' => $this->input->post('status'),
							'title' => $this->input->post('title'),
							'uri' => $this->input->post('uri'),
							'lang' => $this->input->post('lang')
						);
						
						$this->db->set($data);
						$this->db->update('navigation');
						
						$this->session->set_flashdata('notification', __("Navigation item saved"));
						$this->cache->remove_group('navigation');
						redirect('admin/navigation');
					}
					else
					{
						$this->db->where('id', $id);
						$this->db->limit(1);
						$query = $this->db->get('navigation');
						if ($query->num_rows() > 0 )
						{
							$this->template['nav'] = $query->row_array();
							
							$this->layout->load($this->template, 'navigation/edit');
						}
						else
						{
							$this->session->set_flashdata('notification', __("Navigation item not found"));
							redirect('admin/navigation');
						}
					}
				break;
				default:
				
					$this->template['navigation'] = $this->nav_get();
					
					$this->layout->load($this->template, 'navigation/admin');
				break;
			}
		}

		function nav_get($parent = 0, $level = 0) {
			// retrieve all children of $parent
			$this->db->where(array('parent_id' => $parent, 'lang' => $this->lang));
			$this->db->order_by('parent_id, weight');
			$query = $this->db->get('navigation');

			// display each child
			foreach ($query->result_array() as $row) {
			// indent and display the title of this child
			$this->nav[] = array(
				'level' =>$level, 
				'title' => $row['title'],
				'parent_id' => $row['parent_id'],
				'id' => $row['id'],
				'uri' => $row['uri']
			);
			// call this function again to display this
			// child's children
				$this->nav_get($row['id'], $level+1);
			}
			return $this->nav;
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
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			if ( !$this->input->post('submit') )
			{
				
				$this->template['themes'] = $this->layout->get_themes();
				$this->layout->load($this->template, 'settings');
			}
			else
			{

				$fields = array('site_name', 'meta_keywords', 'meta_description', 'cache', 'cache_time', 'theme', 'debug');
				
				
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
				
		
				redirect('admin/settings');
			}
		}
		

	}

?>