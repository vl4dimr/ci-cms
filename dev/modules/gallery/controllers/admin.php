<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			$this->output->enable_profiler(true);
			
			$this->error = '';
			$this->load->library('administration');
			$this->load->model('gallery_model');
			$this->load->library('validation');			
			$this->template['module']	= 'gallery';
			$this->template['admin']	= true;
			$this->_init();
		}
		
		function index()
		{	
			redirect('admin/gallery/listall');
		}
		
		function _init() 
		{
			/*
			* default values
			*/
			if (!is_writable  ('./images'))
			{
				$this->template['notice'] = __("The <i>images</i> directory is not writable. Please fix it'", $this->template['module']);
			}
			elseif (!is_dir('./images/o'))
			{
				@mkdir('./images/o');
				@mkdir('./images/s');
				@mkdir('./images/m');
			}
			
			if (!isset($this->system->login_signup_enabled)) 
			{
				 $this->system->login_signup_enabled = 1;
			}
			
		}
		
		function listall() 
		{
			$debut = $this->uri->segment(4);
			$limit = $this->uri->segment(5);
			$this->template['images'] = $this->gallery_model->get_list();
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'gallery/listall/';
			$config['total_rows'] = $this->gallery_model->countImagesInCat();
			$config['per_page'] = '20'; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			
			$this->layout->load($this->template, 'admin/list_all_view');
			return;
		}
				
		
		function move()
		{
			$direction = $this->uri->segment(4);
			$id = $this->uri->segment(5);
			
			$this->gallery_model->move($direction, $id);
			
			redirect('admin/gallery/listall');
		}
		
		function makeDefault()
		{
			$id = $this->uri->segment(4);
			$default = $this->uri->segment(5);
			
			$this->gallery_model->makeDefault($id, $default);
			
			redirect('admin/gallery');
		}
		
		
		function settings()
		{
		
		}
		
		
		/**
		 * Edit Gallery Image
		 */
		function edit()
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			
			$this->rec_id = $this->uri->segment('4');
			
			// If we have a posted form process it!
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'				=> $this->input->post('name'),
							'caption'		=> $this->input->post('caption'),
							'author'		=> $this->input->post('author'),
							'file'			=> $this->input->post('file'),
							'id'			=> $this->rec_id
						);
				
				//$id = $this->gallery_model->saveImage($data);
				//$this->cache->remove('eventlist'.$this->lang, 'events');				
					
				$old_img = $this->gallery_model->getImageById($this->input->post('id'));
								
				if ($_FILES['image']['name'] != '')
				{
					// First get the old image and save filename for later deletion
					// If we made it here we need to delete 
					// the old image files
					if(is_file('./images/o/'.$old_img['file']))
					{
							$path = './images/';
							$original = $path.'o/'.$old_img['file'];
							$medium = $path.'m/'.$old_img['file'];
							$small = $path.'s/'.$old_img['file'];
							
						if(is_file($original))
						{
							unlink($original);
						}
						
						if(is_file($medium))
						{
							unlink($medium);
						}
						
						if(is_file($small))
						{
							unlink($small);
						}
					}
					
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
						$this->session->set_flashdata('notification', 'Image "'.$this->input->post("name").'" Upload Error '.$error.' ...');	
				
				redirect('admin/gallery');
						
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
						$config['max_width'] = 150;
						$config['max_height'] = 100;
						$config['maintain_ratio'] = true;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);
						if($this->image_lib->resize())
						{						
					
						
							$config['source_image'] = $image_data['full_path'];
							$config['new_image'] = './images/m/';
							$config['max_width'] = 300;
							$config['max_height'] = 200;
							$config['maintain_ratio'] = TRUE;
							$config['master_dim'] = 'width';
							$config['create_thumb'] = FALSE;
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
							
						}
	
						
					}				
				}// End file upload
				
				// Save file data
				$filename = $this->input->post('file');
				if ($_FILES['image']['name'] != '')
				{
					$filename = str_replace(' ','_',$_FILES['image']['name']);
				}
								
				// Save
				$data = array(
					'name'				=> $this->input->post('name'),
					'caption'		=> $this->input->post('caption'),
					'author'		=> $this->input->post('author'),
					'file'			=> $filename,
					'id'			=> $this->rec_id
				);
				
				$id = $this->gallery_model->saveImage($data);

				
				$this->session->set_flashdata('notification', 'Image "'.$this->input->post("name").'" has been saved ...');	
				
				//redirect('admin/gallery');
			} // End of Subittion processing

			
			//------------------------------------------------
			// If we have no posted data, get the image info
			// and display on form
			//------------------------------------------------
			
			
			$this->javascripts->add('ajaxfileupload.js');
			//$this->template['events'] = $data;
			$this->db->where('id', 0);
			$this->db->or_where('id', $this->rec_id);
			$query = $this->db->get('gallery_images');
			
			$temp_data = $query->result_array();
			
			$this->template['image'] = $temp_data[0];
			$this->template['categories'] = $this->gallery_model->getImageCategories();;
		
			$this->layout->load($this->template, 'admin/edit');
		}
		




		function create()
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
		
			// If we have a posted form process it!
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'				=> $this->input->post('name'),
							'caption'		=> $this->input->post('caption'),
							'author'		=> $this->input->post('author'),
							'gallery_cat_id'  	=> $this->input->post('category_id'),
							'default'		=> $this->input->post('dafault')
						);
								
				if ($_FILES['image']['name'] != '')
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
						$this->session->set_flashdata('notification', 'Image "'.$this->input->post("name").'" Upload Error '.$error.' ...');	
				
				redirect('admin/gallery');
						
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
						$config['max_width'] = 150;
						$config['max_height'] = 100;
						$config['maintain_ratio'] = true;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);
						if($this->image_lib->resize())
						{						
					
						
							$config['source_image'] = $image_data['full_path'];
							$config['new_image'] = './images/m/';
							$config['max_width'] = 300;
							$config['max_height'] = 200;
							$config['maintain_ratio'] = TRUE;
							$config['master_dim'] = 'width';
							$config['create_thumb'] = FALSE;
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
							
						}
	
						
					}				
				}// End file upload
				else
				{
					$this->session->set_flashdata('notification', 'You have not selected an image to upload...');	
				
					redirect('admin/gallery');
				
				}// End file upload error
				
				
				// Save file data
				if ($_FILES['image']['name'] != '')
				{
					$filename = $image_data['file_name'];//str_replace(' ','_',$_FILES['image']['name']);
				}
								
				// Save
				$data = array(
					'name'				=> $this->input->post('name'),
					'caption'		=> $this->input->post('caption'),
					'author'		=> $this->input->post('author'),
					'file'			=> $filename,
					'gallery_cat_id'	=> $this->input->post('category_id'),
					'default'		=> $this->input->post('default')
				);
				
				$id = $this->gallery_model->saveImage($data);

				$this->session->set_flashdata('notification', 'Image "'.$this->input->post("name").'" has been saved ...');	
				
				redirect('admin/gallery');
			} // End of Subittion processing

			
			//------------------------------------------------
			// If we have no posted data, get the image info
			// and display on form
			//------------------------------------------------
			
			
			$this->javascripts->add('ajaxfileupload.js');
			$this->template['categories'] = $this->gallery_model->getImageCategories();
				
			$this->layout->load($this->template, 'admin/create');		
	}


	/**
	 * Remove Image record and
	 * image files from site
	 */
	function delete()
	{	
		$id = $this->uri->segment(4);
		
		$this->gallery_model->removeImageById($id);
		
		$this->session->set_flashdata("notification", __("Image deleted"));
		redirect("admin/gallery/");
		
	}
		

	/**
	 * Process file upload
	 */
	function do_upload($size='o')
	{
		$this->load->library('upload');
		
		$config['upload_path'] = "./images/$size/";
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload('image'))
		{
			$this->error = array('error' => $this->upload->display_errors());
			return false;
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
	
		
		
	}
	
?>