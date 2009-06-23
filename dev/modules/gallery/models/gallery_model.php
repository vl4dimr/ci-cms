<?php
/**
 * @File: gallery_model.php
 *
 * @Desc: This file provides a low level
 * model of our gallery. The basic model
 * provides CRUD-like functionality at a
 * higher level than the db implementation
 *
 */
 
 /**
  * The gallery uses it's own database table
  * to store images. The main reasons for doing
  * this was to allow the code to be easily 
  * ported to other CodeIgniter projects, reduce
  * database table size in large galleries, and 
  * to allow the categorizing of images in the
  * gallery.
  *
  * The gallery expects the db tables to be 
  * defined as follows:
  *
  * ci_gallery_images(
  * 	id INT(11) NOT NULL,
  *		gallery_cat_id INT(11) NOT NULL DEFUALT(0),
  * 	title VARCHAR(50) NOT NULL DEFAULT(''),
  *     caption VARCHAR(255) NOT NULL DEFAULT('')
  *		INDEX gallery_cat_id,
  *		INDEX title,
  *		PRIMARY KEY id
  * );
  *
  * ci_gallery_categories(
  *		id INT(11) NOT NULL,
  *		name VARCHAR(50) NOT NULL,
  *		description VARCHAR(255) NOT NULL DEFUALT(''),
  *		INDEX name,
  *		PRIMARY KEY id
  *	);
  *
  */
  
/**
 * SQL 
 
CREATE TABLE `ci_gallery` (
  `id` int(11) NOT NULL auto_increment,
  `gallery_cat_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `order` int(11) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This table holds image information for the photo gallery' AUTO_INCREMENT=1 ;


CREATE TABLE `ci_gallery_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table holds the categories used for the image gallery' AUTO_INCREMENT=1 ;

*/


class Gallery_model extends Model {
 
	/////////////////////////////////////////
	//         Gallery Properties
	/////////////////////////////////////////
	
	// Gallery Image Table
	var $table = "gallery_images";
	// Image table primary key
	var $table_pk = 'id';
	// Image table's category foriegn key column
	// Used in join.
	var $table_fk = 'gallery_cat_id';
	// Gallery Categories table
	var $cat_table = 'gallery_categories';
	// Gallery categories table key column
	// use in join. 
	var $cat_pk = 'id';
	// Category Name field
	var $cat_name = "name";
	
	// Index of current image
	var $cur_image = 0;
	// Total Image Count
	var $image_cnt = 0;
	// Current Image category 
	var $cur_category = 'all';
	// Array of Navigation images
	
	var $nav_images = array();
	// Currently Selected Image 
	var $selected_image = null;
	// Navigation Start position
	var $nav_start = 0;
	// Navigation start position when wrapping
	var $_nav_start = 0;
	// Navigation End position
	var $nav_end = 0;
	// Navigation end position when wrapping
	var $_nav_end = 0;
	// Navigation  bar size
	var $nav_size = 5;
	// Navigation Mode
	// set to true to cause wrapping
	var $nav_wrap = FALSE;
	
	/**
	* Class Constructor
	*/
	function Gallery_Model()
	{
		parent::Model();
	}
	
	
	/**
	 * Save Gallery Image Record
	 */
	function saveImage($data=null)
	{
		// Do we have data?
		if(is_null($data))
		{
			return false;
		}
		
		if(is_array($data))
		{
			if(array_key_exists('id', $data))
			{
				$this->db->where('id', $data['id']);
				$this->db->update($this->table, $data);
				
				return $data['id'];
			}
			else
			{
				$this->db->insert($this->table, $data);
				return $id = $this->db->insert_id();
			}
		
		}
	}
	
	
	/**
	 * Delete Image record and 
	 * image files frm site
	 */
	function removeImageById()
	{
		$id = $this->uri->segment(4);
		$status = false;
		//echo $id;
		
		// Get the image file names
		$this->db->where('id', "$id");
		$query = $this->db->get($this->table);
		
		$tmp_image = $query->result_array();
		$image = $tmp_image[0];
		
		// Remove image files
		if(is_file('./images/o/'.$image['file']))
		{
				$path = './images/';
				$original = $path.'o/'.$image['file'];
				$medium = $path.'m/'.$image['file'];
				$small = $path.'s/'.$image['file'];
				
			if(is_file($original))
			{
				$status = unlink($original);
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
		
		// Delete the db record
		$this->db->delete($this->table, "id=$id");
	
		return $status;
	}
	
	
	/**
	* Returns an image given the
	* image's database id.
	*/ 
	function getImageById($id=null)
	{
		$this->db->where($this->table_pk, $id);
		$query = $this->db->get($this->table);
		
		$images = $query->result_array();
		
		return $images[0];
	}
	
	/**
	* Retrieves the navigation images
	* from the database given the current
	* location. The images are stored in 
	* the nav_images array for later use.
	*/ 
	function loadNavImages($idx=0)
	{
	
		// Get offset value
		$offset = (($this->nav_size-1)/2);
		
		$x = 0;
		
		$this->nav_start = $this->countImagesInCat();
		$this->nav_end = $this->nav_size;
		
		// get start location
		if((($idx <= ($this->countImagesInCat()-$offset)) && ($idx > $offset)))
		{
			$this->nav_start = $idx-$offset-1;
			$this->nav_end = $this->nav_size;
			
		}
		else if($idx > ($this->countImagesInCat()-$offset))
		{
			$x = 1;
			 
			// Setup first quesry
			$this->nav_start = ($idx - ($offset+1));
			$this->nav_end = ($this->nav_size-$offset)+1;
			
			// Setup second query to handle wrapping
			$this->_nav_start = 0;
			$this->_nav_end = ($this->nav_size - ($this->countImagesInCat()-$this->nav_start));				
			
		}
		else if($idx <= $offset)
		{ // 1|2|3|4|5|6|7|8|9|10
			$x = 1;
			
			// Set up first query
			$this->nav_start = $this->countImagesInCat()-($offset-$idx)-1;
			$this->nav_end = $this->countImagesInCat();
			
			// Setup second query
			$this->_nav_start = 0;
			$this->_nav_end = $this->nav_size - ($this->countImagesInCat() - $this->nav_start);
			
		}
		
		// Check for category filter
		if($this->cur_category !== '' && strtolower($this->cur_category) !== "all")
		{
			$this->db->where("$this->cat_name", "$this->cat_category");
			$this->db->join("$this->cat_table", "$this->cat_pk = $this->table_fk");
		}
		
		
		// Setup the query limit
		$this->db->limit($this->nav_end, $this->nav_start);
		$this->db->order_by('ordering');
		//$this->db->order_by('id');
		
		// Get the images from the database.
		$query = $this->db->get($this->table);
		
		// Create Image array
		foreach($query->result_array() as $image)
		{
			$this->nav_images[] = $image;
		}
		
		if($x)
		{
			// Check for category filter
			if($this->cur_category !== '' && strtolower($this->cur_category) !== "all")
			{
				$this->db->where("$this->cat_name", "$this->cat_category");
				$this->db->join("$this->cat_table", "$this->cat_pk = $this->table_fk");
			}
			
			// Setup the query limit
			$this->db->limit($this->_nav_end, $this->_nav_start);
			$this->db->order_by('ordering');
		
			// Get the images from the database.
			$query = $this->db->get($this->table);
			
			foreach($query->result_array() as $image)
			{
				$this->nav_images[] = $image;
			}
		}
		
		return $this->nav_images;
		
	}
	
	
	/**
	* Counts the number of images
	* in the current category and
	* sets the count value in the
	* image_cnt property. The method
	* also returns the count to the
	* caller.
	*/
	function countImagesInCat()
	{
		// Check for category filter
		if(!$this->cur_category != '' || strtolower($this->cur_category) != "all")
		{
			$this->db->where($this->cat_name, $this->cat_category);
			$this->db->join("$this->cat_table", "$this->cat_pk = $this->table_fk");
		}
		
		// Get the images from the database.
		$this->image_cnt = $this->db->count_all_results($this->table);
		
		//echo $this->image_cnt;
		
		return $this->image_cnt;
	
	}
	
	/**
	* Get All Images 
	*/
	function get_list()
	{
		$this->db->orderby('ordering');
		$query = $this->db->get($this->table);
		
		// Get Events
		if($query->num_rows() < 1)
		{
			$this->tmpimages = "No Event Records Found!";
		}		
		
		foreach ($query->result_array() as $row) 
		{
			$this->tmpimages[] = array(
				'name' =>$row['name'], 
				'caption' => $row['caption'],
				'id' => $row['id'],
				'ordering' => $row['ordering'],
				'category' => ($this->getImageCategoryName($row['gallery_cat_id'])),
				'default' => $row['default'],
				'file' => $row['file'],
				'image_link' =>(base_url().'/images/s/'.$row['file'])
			);
		}
		
		return $this->tmpimages;
	
	}
	
	/**
	* Returns an array of all categories
	* in the gallery's category table.
	*/
	function getImageCategories()
	{
	  $query = $this->db->get($this->cat_table);
	  
	  if($query->num_rows() > 0)
	  {
		  return $query->result_array();
	  }
	  else
	  {
		  return array(0 => 'all');
	  }
	}
	
	/**
	* Given a category id, returns
	* the category name.
	* Else returns 'Unassigned'
	*/
	function getImageCategoryName($cat_id)
	{
		if((int)$cat_id)
		{
			$this->db->where($this->cat_pk, $cat_id);
		}
		
		$query = $this->db->get($this->cat_table);
		
		if($query->num_rows() > 0)
		{
			$cats = $query->result_array();
			
			return $cats[0]['name'];
		}
		else
		{
			return 'Unassigned';
		}
	}
  
  
  	/**
	 * Re-orders images
	 *
	 * TODO Add support for image categories
	 */
	function move($direction = null, $id = null)
	{
		if (is_null($id) || is_null($direction))
		{
			redirect('admin/gallery');
		}
	
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array($this->table_pk => $id, 'ordering >=' => 100));
		$this->db->set('ordering', 'ordering+'.$move, FALSE);
		$this->db->update($this->table);
		
		$this->db->where(array($this->table_pk => $id, 'ordering >=' => 100));
		$query = $this->db->get($this->table);
		$row = $query->row();
		$new_ordering = $row->ordering;
	
	
		if ( $move > 0 )
		{
			$this->db->set('ordering', 'ordering-1', FALSE);
			$this->db->where(array('ordering <=' => $new_ordering, $this->table_pk.' <>' => $id));
			$this->db->update($this->table);
		}
		else
		{
			$this->db->set('ordering', 'ordering+1', FALSE);
			$this->db->where(array('ordering >=' => $new_ordering, $this->table_pk.' <>' => $id));
			$this->db->update($this->table);			
		}

		//reordinate
		$i = 101;
		$this->db->order_by('ordering');
		//$this->db->where(array('ordering >=' => 100) );
		$query = $this->db->get($this->table);
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('ordering', $i);
				$this->db->where($this->table_pk, $row->id);
				$this->db->update($this->table);
				$i++;
			}
		}
		
		redirect('admin/gallery');
	}
	
	
	/**
	 * Make the passed image a default image
	 *
	 * TODO Add support for image categories
	 */
	function makeDefault($id=null, $set=0)
	{
		if(is_null($id))
		{
			return false;
		}
		
		// remove any current default image in category
		$this->db->where('default', 1);
		$this->db->set('default', 0);
		$this->db->update($this->table);
		
		
		// Update the image default setting
		if($set)
		$this->db->where($this->table_pk, $id);
		$this->db->set('default', $set);
		$this->db->update($this->table);
	}
	
	/**
	 * Return the integer image order value
	 * for the image id passed in.
	 */
	function getImageOrderById($id=null)
	{
		if(is_null($id) || ($id <= 0))
		{
			$id=1;
		}
		
		$this->db->where($this->table_pk, $id);
		$this->db->select('id, ordering');
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$images = $query->result_array();
			$image = $images[0];
			
			return $image['ordering'];
		} 
		else
		{
			return 0;
		}
	}
	
	/**
	 * Return a default image for the image category.
	 * If no image in the current category is set to default,
	 * return the image with the hestest id value.
	 */
	 function getDefaultImage()
	 {
	 	// Check for category filter
		if(!$this->cur_category = '' || strtolower($this->cur_category) != "all")
		{
			$this->db->where($this->cat_name, $this->cat_category);
			$this->db->join("$this->cat_table", "$this->cat_pk = $this->table_fk");
		}

		$this->db->where($this->table_pk, 'MIN('.$this->table_pk.')');
		
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$images = $query->result_array();
			
			return $images[0];
		}
		else
		{
			return false;
		}
	 }


	/**
	 * Return a default image ID for the image category.
	 * If no image in the current category is set to default,
	 * return the image with the hestest id value.
	 */
	 function getDefaultImageId($default=1)
	 {
	 	// Check for category filter
		if(!$this->cur_category === '' || 0 !== strcasecmp(strtolower($this->cur_category), 'all'))
		{
			$this->db->where($this->cat_table.'.'.$this->cat_name, $this->cur_category);
			$this->db->join($this->cat_table, $this->cat_table.".".$this->cat_pk." = ".$this->table.".".$this->table_fk);
		}
		
		if($default)
		{
			$this->db->where('default', '1');
		}
		else
		{
			$this->db->select_max($this->table.".".$this->table_pk);
		}
		
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$images = $query->result_array();
			return $images[0][$this->table_pk];
		}
		else
		{
			if(!$default) 
			{
				return false;
			}
			else
			{
				return $this->getDefaultImageId(0);
			}
		}
	 }


} 
?>