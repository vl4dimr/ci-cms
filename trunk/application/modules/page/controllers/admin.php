<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');
			$this->lang = $this->session->userdata('lang');

			$this->template['module']	= 'page';
			$this->template['admin']		= true;
			
			$this->load->model('page_model', 'pages');
			
			$this->page_id = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : NULL;
			
		}
		
		function index($start)
		{
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			if ( !$data = $this->cache->get('pagelist'.$this->lang, 'page') )
			{
				$data = $this->pages->list_pages();
				$this->cache->save('pagelist'.$this->lang, $data, 'page', 0);
			}
			
			$this->template['pages'] = $data;
			
			
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'admin/page/index/';
			$config['total_rows'] = $this->pages->get_total();
			$config['per_page'] = '20'; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			$this->layout->load($this->template, 'admin');
			
		}
		/**
		 * Dealing with page module settings
		 **/
		function settings()
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ($post = $this->input->post('submit') )
			{
				$fields = array('page_home');
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->system->set($field, $this->input->post($field));
					}
				}
				$this->session->set_flashdata('notification', __("Settings updated"));	
				redirect('admin/page/settings');
			}
			else
			{
				$this->layout->load($this->template, 'settings');
			}
		}
		function create()
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'title'				=> $this->input->post('title'),
							'parent_id'			=> $this->input->post('parent_id'),
							'uri'				=> $this->input->post('uri'),
							'meta_keywords'		=> $this->input->post('meta_keywords'),
							'meta_description'	=> $this->input->post('meta_description'),
							'body'				=> $this->input->post('body'),
							'active'			=> $this->input->post('status'),
							'lang'				=> $this->input->post('lang')
						);
						
				$this->db->insert('pages', $data);
				$id = $this->db->insert_id();
				$this->cache->remove('pagelist'.$this->lang, 'page');

				
				if (isset($_FILES['image']))
				{
					//var_dump($this->input->post('image'));
					//there is an image attached
					$config['upload_path'] = './images/o/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '500';
					$config['max_width']  = '1024';
					$config['max_height']  = '768';
					
					//var_dump($config['upload_path']);
					$this->load->library('upload', $config);
				
					if ( ! $this->upload->do_upload('image'))
					{
						$error = array('error' => $this->upload->display_errors());
						
						$this->load->view('upload_form', $error);
					}	
					else
					{
						$this->load->library('image_lib');
						$image_data = $this->upload->data();
						
						//var_dump($image_data);
						
						//resize to 150
						$config['source_image'] = $image_data['full_path'];
						$config['new_image'] = './images/s/';
						$config['width'] = 150;
						$config['height'] = 100;
						$config['maintain_ratio'] = true;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);
						if($this->image_lib->resize())
						{						
					
						
							$config['source_image'] = $image_data['full_path'];
							$config['new_image'] = './images/m/';
							$config['width'] = 300;
							$config['height'] = 200;
							$config['maintain_ratio'] = TRUE;
							$config['master_dim'] = 'width';
							$config['create_thumb'] = FALSE;
							$this->image_lib->initialize($config);

							$this->image_lib->resize();
							
							$this->pages->attach($id, $image_data);
						
						}
						/*
						[file_name]    => mypic.jpg
						[file_type]    => image/jpeg
						[file_path]    => /path/to/your/upload/
						[full_path]    => /path/to/your/upload/jpg.jpg
						[raw_name]     => mypic
						[orig_name]    => mypic.jpg
						[file_ext]     => .jpg
						[file_size]    => 22.2
						[is_image]     => 1
						[image_width]  => 800
						[image_height] => 600
						[image_type]   => jpeg
						[image_size_str] => width="800" height="200"
						*/
						
						
					}				
				}	
					
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been created, continue editing here');	
				
				redirect('admin/page');
			}
			else
			{
				
				$this->layout->load($this->template, 'create');
			}
		}
		
		function edit()
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'title'				=> $this->input->post('title'),
							'parent_id'		=> $this->input->post('parent_id'),
							'uri'				=> $this->input->post('uri'),
							'meta_keywords'		=> $this->input->post('meta_keywords'),
							'meta_description'	=> $this->input->post('meta_description'),
							'body'				=> $this->input->post('body'),
							'active'			=> $this->input->post('status'),
							'lang'			=> $this->input->post('lang')
						);
					
				$this->db->where('id', $this->input->post('id'));
				$this->db->update('pages', $data);
				$this->cache->remove('pagelist'.$this->lang, 'page');				
				
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been saved ...');
				
				redirect('admin/page');
			}

			if ( !$data = $this->cache->get('pagelist'.$this->lang, 'page') )
			{
				$data = $this->pages->list_pages();
				$this->cache->save('pagelist'.$this->lang, $data, 'page', 0);
			}			
			$this->template['pages'] = $data;
			
			$this->template['page'] = $this->pages->get_page( array('id' => $this->page_id) );
			$this->layout->load($this->template, 'edit');
		}
		
		function delete()
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ( $post = $this->input->post('submit') )
			{
				$this->db->where('id', $this->input->post('id'));
				$query = $this->db->delete('pages');
				
				$this->session->set_flashdata('notification', 'Page has been deleted.');
				$this->cache->remove('pagelist'.$this->lang, 'page'); 
				redirect('admin/page');
			}
			else
			{
				$this->template['page'] = $this->pages->get_page( array('id' => $this->page_id) );
				
				$this->layout->load($this->template, 'delete');
			}
		}
		
	}

?>