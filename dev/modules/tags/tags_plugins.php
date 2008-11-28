<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


$this->add_filter('page_custom_fields', 'tag_fields');
$this->add_action('page_save', 'tag_save');

function tag_fields($string)
{
	$output = "
<label for=\"tags\">" . __("Tags:", "tags") . "</label>
<input type=\"text\" name=\"tags\" value=\"\" id=\"tags\" class=\"input-text\" /><br />
" . __("Enter tags separated by comma", "tags") . "<br />";
	
	return $output;
}

function tag_save($input)
{
	$obj =& get_instance();
	if ($tagitem = $obj->input->post('tags'))
	{
		$tags = explode(",", $tagitem);

		if ($obj->input->post('uri') != '')
		{
			$uri = $obj->input->post('uri');
		}
		else
		{
			$parent_uri = '';
			if ($parent_id = $obj->input->post('parent_id'))
			{
				$parent = $obj->pages->get_page(array('id' => $parent_id));
				$parent_uri = $parent['uri'] . "/";
			}
			$uri = $parent_uri . format_title($obj->input->post('title'));
		}		
		
		foreach ($tags as $tag)
		{
			$data = array(
				'url' => $uri, 
				'title' => $obj->input->post('title'),
				'lang' => $obj->input->post('lang'),
				'srcid' => 
				
				);
			$obj->tag->save_tag($data);
		}
	}
	exit();
}

?>