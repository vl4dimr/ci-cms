<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');
			$this->user->lang = $this->session->userdata('lang');

			$this->template['module']	= 'page';
			$this->template['admin']		= true;
			
			$this->load->model('page_model', 'pages');
			
			$this->page_id = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : NULL;
			
		}
		
		function index($start = 0)
		{
		
			if (!is_writable  ('./media/images'))
			{
			$this->template['notice'] = __("The <i>images</i> directory is not writable. Please fix it'");
			}
			elseif (!is_dir('./media/images/o'))
			{
				@mkdir('./media/images/o');
				@mkdir('./media/images/s');
				@mkdir('./media/images/m');
			}
			
			$per_page = 20;
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			
			if ( !$data = $this->cache->get('pagelist'.$this->user->lang, 'page') )
			{
				if (!$data = $this->pages->list_pages()) $data = array();
				$this->cache->save('pagelist'.$this->user->lang, $data, 'page', 0);
			}
			

			$this->template['pages'] = array_slice($data, $start, $per_page);
			
			
			$this->load->library('pagination');
			
			$config['uri_segment'] = 4;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = base_url() . 'admin/page/index';
			$config['total_rows'] = count($data);
			$config['per_page'] = $per_page; 

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
							'meta_keywords'		=> $this->input->post('meta_keywords'),
							'meta_description'	=> $this->input->post('meta_description'),
							'body'				=> $this->input->post('body'),
							'active'			=> $this->input->post('status'),
							'lang'				=> $this->input->post('lang')
						);
				
				if ($this->input->post('uri') != '')
				{
					$data['uri'] = $this->input->post('uri');
				}
				else
				{
					$parent_uri = '';
					if ($parent_id = $this->input->post('parent_id'))
					{
						$parent = $this->pages->get_page(array('id' => $parent_id));
						$parent_uri = $parent['uri'] . "/";
					}
					$data['uri'] = $parent_uri . format_title($this->input->post('title'));
				}
				$this->db->insert('pages', $data);
				$id = $this->db->insert_id();
				
				if ($image_ids = $this->input->post('image_ids'))
				{
					foreach($image_ids as $image_id)
					{
						$this->db->set('src_id', $id);
						$this->db->where('id', $image_id);
						$this->db->update('images');
					}	
				}
				$this->cache->remove('pagelist'.$this->user->lang, 'page');

				
				if ($_FILES['image']['name'] != '')
				{

					//var_dump($this->input->post('image'));
					//there is an image attached
					$config['upload_path'] = './media/images/o/';
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
						$config['new_image'] = './media/images/s/';
						$config['width'] = 150;
						$config['height'] = 100;
						$config['maintain_ratio'] = true;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);
						if($this->image_lib->resize())
						{						
					
						
							$config['source_image'] = $image_data['full_path'];
							$config['new_image'] = './media/images/m/';
							$config['width'] = 300;
							$config['height'] = 200;
							$config['maintain_ratio'] = TRUE;
							$config['master_dim'] = 'width';
							$config['create_thumb'] = FALSE;
							$this->image_lib->initialize($config);

							$this->image_lib->resize();
							
							$this->pages->attach($id, $image_data);
						
						}
	
						
					}				
				}	
					
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been created, continue editing here');	
				
				redirect('admin/page');
			}
			else
			{
				$this->javascripts->add('ajaxfileupload.js');
				//get pending images
				$this->db->where('src_id', 0);
				$this->db->where('module', 'page');
				$query = $this->db->get('images');
				
				$this->template['images'] = $query->result_array();
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
				$this->cache->remove('pagelist'.$this->user->lang, 'page');				
				
				
				
			
				if ($image_ids = $this->input->post('image_ids'))
				{
					foreach($image_ids as $image_id)
					{
						$this->db->set('src_id', $this->input->post('id'));
						$this->db->where('id', $image_id);
						$this->db->update('images');
					}	
				}
				$this->cache->remove('pagelist'.$this->user->lang, 'page');

				
				if ($_FILES['image']['name'] != '')
				{

					//var_dump($this->input->post('image'));
					//there is an image attached
					$config['upload_path'] = './media/images/o/';
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
						$config['new_image'] = './media/images/s/';
						$config['width'] = 150;
						$config['height'] = 100;
						$config['maintain_ratio'] = true;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);
						if($this->image_lib->resize())
						{						
					
						
							$config['source_image'] = $image_data['full_path'];
							$config['new_image'] = './media/images/m/';
							$config['width'] = 300;
							$config['height'] = 200;
							$config['maintain_ratio'] = TRUE;
							$config['master_dim'] = 'width';
							$config['create_thumb'] = FALSE;
							$this->image_lib->initialize($config);

							$this->image_lib->resize();
							
							$this->pages->attach($this->input->post('id'), $image_data);
						
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
				
				
				
				
				
				
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been saved ...');
				
				
				
				redirect('admin/page');
			}

			if ( !$data = $this->cache->get('pagelist'.$this->user->lang, 'page') )
			{
				$data = $this->pages->list_pages();
				$this->cache->save('pagelist'.$this->user->lang, $data, 'page', 0);
			}			
			$this->javascripts->add('ajaxfileupload.js');
			$this->template['pages'] = $data;
			$this->db->where('src_id', 0);
			$this->db->or_where('src_id', $this->page_id);
			$query = $this->db->get('images');
			
			$this->template['images'] = $query->result_array();
			
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
				
				$this->db->where('src_id', $this->input->post('id'));
				$this->db->set('src_id', 0);
				$query = $this->db->update('images');
				
				$this->session->set_flashdata('notification', 'Page has been deleted.');
				$this->cache->remove('pagelist'.$this->user->lang, 'page'); 
				redirect('admin/page');
			}
			else
			{
				$this->template['page'] = $this->pages->get_page( array('id' => $this->page_id) );
				
				$this->layout->load($this->template, 'delete');
			}
		}
		
		function tinyimagelist()
		{
			if ( !$rows = $this->cache->get('imagelist', 'page') )
			{	
				$this->db->where('module', 'page');
				$this->db->order_by('file');
				$query = $this->db->get('images');
				$rows = $query->result_array();
				$this->cache->save('imagelist', $rows, 'page', 0);
			}		

			$images = array();
			foreach ($rows as $row)
			{
				$images[] = "[\"". stripslashes($row['file']) . "\", \"". site_url('media/images/m/' . $row['file']) . "\"]" ; 
			}	
			
			echo "var tinyMCEImageList = new Array(";
			echo join(", ", $images);
			echo ");";			
		
		}
		function tinypagelist()
		{
			if ( !$rows = $this->cache->get('pagelist'.$this->user->lang, 'page') )
			{
				if (!$rows = $this->pages->list_pages()) $rows = array();
				$this->cache->save('pagelist'.$this->user->lang, $rows, 'page', 0);
			}
			
			$pages = array();
			foreach ($rows as $row)
			{
				$pad = str_repeat('&nbsp;&nbsp;', $row['level']);
				$pages[] = "[\"".$pad." " . stripslashes($row['title']) . "\", \"". site_url($row['uri']) . "\"]" ; 
			}
			echo "var tinyMCELinkList = new Array(";
			echo join(", ", $pages);
			echo ");";
		}
		
		function ajax_delete()
		{
			$this->db->where('id', $this->input->post('id'));
			$this->db->delete('images');
			echo __("The image was deleted");
		}
		
		function ajax_upload()
		{
			$image_data = array();
			$error = "";
			if(!empty($_FILES['image']['error']))
			{
				switch($_FILES['image']['error'])
				{

					case '1':
						$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini');
						break;
					case '2':
						$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
						break;
					case '3':
						$error = __('The uploaded file was only partially uploaded');
						break;
					case '4':
						$error = __('No file was uploaded.');
						break;

					case '6':
						$error = __('Missing a temporary folder');
						break;
					case '7':
						$error = __('Failed to write file to disk');
						break;
					case '8':
						$error = __('File upload stopped by extension');
						break;
					case '999':
					default:
						$error = __('No error code avaiable');
				}
			}
			elseif(empty($_FILES['image']['tmp_name']) || $_FILES['image']['tmp_name'] == 'none')
			{
				$error = __('No file was uploaded..');
			}
			else 
			{
			
			
				$config['upload_path'] = './media/images/o/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '500';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				
				//var_dump($config['upload_path']);
				$this->load->library('upload', $config);	
				
				if ( ! $this->upload->do_upload('image'))
				{
					$error = $this->upload->display_errors('', '');
					
				}	
				else
				{

					$image_data = $this->upload->data();
					

					$config = array();
					//resize to 150
					$config['source_image'] = $image_data['full_path'];
					$config['new_image'] = './media/images/s/';
					$config['width'] = 150;
					$config['height'] = 100;
					$config['maintain_ratio'] = true;
					$config['master_dim'] = 'width';
					$config['create_thumb'] = FALSE;
					
					$this->load->library('image_lib');
					$this->image_lib->initialize($config);
					$id = '';
					
					if($this->image_lib->resize())
					{						
				
						$config = array();
						$config['source_image'] = $image_data['full_path'];
						$config['new_image'] = './media/images/m/';
						$config['width'] = 300;
						$config['height'] = 200;
						$config['maintain_ratio'] = TRUE;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);

						$this->image_lib->resize();
						$data = array('file' => $image_data['file_name'], 'module' => 'page');
						$this->db->insert('images', $data);
						$id = $this->db->insert_id();
						
					}
				}
			}
			echo "{";
			echo "error: '" . $error . "'";
			//echo "error: 'error is " . $image_data['file_name'] . "'";
			echo ",\n image: '" . (isset($image_data['file_name']) ? $image_data['file_name'] : '') . "'";
			echo ",\n imageid: " . (isset($id) ? $id : 5)  . "";
			echo "\n}";	
		}
		
	}

?>