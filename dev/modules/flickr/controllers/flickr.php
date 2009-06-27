<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Flickr extends Controller {

	function Flickr()
	{
		parent::Controller();
	

		$this->template['module']	= 'flickr';
		$this->load->plugin('phpflickr');
		$this->load->model('flickr_model', 'flickr');
		$this->flickr->get_settings();
		
		$this->f = new phpFlickr($this->flickr->flickr_api_key);
		$this->f->enableCache("fs", "./cache");
		$nsid_array = $this->f->people_findByUsername($this->flickr->flickr_user_name);
		$this->nsid = $nsid_array['id'];
	}
	
	function index()
	{

		$sets = $this->f->photosets_getList($this->nsid);
		
		$this->template['title'] = sprintf(__("Album list for %s", "flickr"), $this->flickr->flickr_user_name);
		$this->template['sets'] = $sets['photoset'];
		
		$this->layout->load($this->template, 'sets');
		
		
	}
	
	function set($setid = null)
	{
		if (is_null($setid))
		{
			$this->session->set_flashdata('notification', __("Id is not set", "flickr"));
			redirect('flickr');
		}
		
		$photosSetInfo = $this->f->photosets_getInfo($setid);
		
		$this->template['title'] = $photosSetInfo['title'];
		$photos = $this->f->photosets_getPhotos($setid);						
 		$this->template['photos'] = $photos['photoset']['photo'];
		$this->layout->load($this->template, 'photos');
	}
}