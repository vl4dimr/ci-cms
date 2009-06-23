<?php
/**
 * @File: gallery.php
 *
 * @Desc: This file is the primary controller 
 * for the gallery module. The module provides
 * a simple ci-cms photo gallery that includes
 * an ajax presentation.
 */
 
class Gallery extends Controller {

	var $table = "gallery";

	function Gallery ()
	{
		parent::Controller();
		
		// Load Database
		$this->load->database();
		
		// Load Ajax4CI
		$this->load->library('ajax');
		
		// Enable profiler for debugging
		//$this->output->enable_profiler(true);
		
		$this->_init();	
	}

	function _init()
	{
		// Tell CMS what module we'll be using
		$this->template['module'] = "gallery";
		
		// Load the event model
		$this->load->model('gallery_model', 'gallery');	
		
		// Setup Ajax
		$this->load->library("ajax");
	
	}
	
	/**
	 * Default Method shows the page
	 * as static html.
	 */
	function index()
	{
		$idx = $this->uri->segment(3);
		
		
		if(is_null($idx) || $idx='')
		{
			$idx = 3;
		}
		
		$this->gallery->cur_category = 'all';
		
		// Get data for view and assign it to a template data array index
		//$this->template['main_image'] = $this->gallery->getImageById($idx, false);
		$this->template['main_image'] = $this->getAjaxMainImage(false);
		//$this->template['nav_images'] = $this->gallery->loadNavImages($idx, false);
		$this->template['nav_images'] = $this->getAjaxNavH(false);
		
		// Load and display the template
		$this->layoutparser->load($this->template, 'gallery_view');

	}
	
	/**
	 * Return the ajax navigation
	 * bar. Given the main-image index and the
	 * size of the navigation bar (number of images to
	 * show in the horizontal nav bar.
	 *
	 * If no main-image index or size is passed, use the 
	 * first image as the main image and the size provided
	 * in the gallery configuration  
	 *
	 * Ex. URL: <site>/gallery/getajaxnavh/<main-image>/<size>
	 */
	function getAjaxNavH($show=true)
	{
		$idx = $this->uri->segment(3);
		$ord = $this->uri->segment(4);
		
		if(!isset($idx) or $idx=='' or is_null($idx))
		{
			$idx= $this->gallery->getDefaultImageId();
		}
		
		if(!isset($ord) or $ord =='' or is_null($ord))
		{
			$ord = $this->gallery->getImageOrderById($idx);
		}
		
		$ord = $ord-100;
		
		$idx = $ord;
		
		if(is_null($idx))
		{
			$idx = 1;
		}
		
		$images = $this->gallery->loadNavImages($idx);
		
		$out = '<table class="galleryNavHTbl" ><tr class="galleryNavHTr" >';
		
		
		foreach($images as $image)
		{
			// UpdateGallery('image_id');
			//$txt = $this->ajax->link_to_remote("gallery", array('url' => "/getAjaxNavH/".$idx, 'update' => 'divNav'));
			$out .= '<td class="galleryNavHTd"><a onclick="javascript:updateGallery();" class="galleryNavHA" rel="'
													.site_url().'/gallery/getAjaxNavH/'.$image['id'].'/'.$image['ordering']
													.'" href='.site_url().'/gallery/getAjaxMainImage/'.$image['id'].'/'.$image['ordering']
													.' ><img class="galleryNavHImg" src='.base_url().'images/o/'.$image['file']
													.' /></a></td>';
		}
		$out .= "</tr>";
		
		$out .= "</table>";
		
		if($show)
		{
			echo $out;
		}
		else
		{
			return $out;
		}
		
	}
	
	
	/**
	 * @File: Returns the navigation bar end points
	 */
	function getAjaxMainImage($show=true)
	{
		$idx = $this->uri->segment(3);
		
		if(is_null($idx) or $idx == '' or !isset($idx))
		{
			$idx = $this->gallery->getDefaultImageId();
		}
		
		$image = $this->gallery->getImageById($idx);
		
		$out = '<img height="300" width="40%" class="galleryMainImg" src='.base_url()."images/o/".$image['file']." />";
		$out .= '<p class="galleryImageCaption">'.$image['caption'].'</p>';
		
		if($show)
		{
			echo $out;
		}
		else
		{
			return $out;
		}	
	}
		
}
?>