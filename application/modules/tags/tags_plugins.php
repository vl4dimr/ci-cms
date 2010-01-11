<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


$this->add_filter('page_custom_fields', 'tag_page_fields', 10, 2);
$this->add_action('page_save', 'tag_page_save');
$this->add_action('page_delete', 'tag_page_delete');

//$this->add_action('news_save', 'tag_news_save');
$this->add_action('news_delete', 'tag_news_delete');

function tag_news_save($id)
{
	$obj =& get_instance();
	$obj->load->model('tags_model', 'tag');
	if ($tagitem = $obj->input->post('tags'))
	{
		$news = $obj->news->get_news(array('news.id' => $id));
		$tags = explode(",", $tagitem);

		$obj->tag->delete(array('srcid' => $id, 'module' => 'news'));
		foreach ($tags as $tag)
		{
			$data = array(
				'url' => 'news/' . $news['uri'], 
				'title' => $news['title'],
				'lang' => $news['lang'],
				'srcid' => $news['id'],
				'tag' => trim($tag),
				'module' => 'news',
				'summary' => substr(strip_tags($news['body']), 0, 200)
				);
			$obj->tag->save_tag($data);
		}
	}
}

function tag_news_delete($id)
{
	$obj =& get_instance();
	$obj->load->model('tags_model', 'tag');
	
	$obj->tag->delete(array('srcid' => $id, 'module' => 'news'));

}



function tag_page_delete($id)
{
	$obj =& get_instance();
	$obj->load->model('tags_model', 'tag');
	
	$obj->tag->delete(array('srcid' => $id, 'module' => 'page'));

}


function tag_page_fields($string, $id = null)
{
	$tag_string = "";
	if (!is_null($id))
	{
		$obj =& get_instance();
		$page = $obj->pages->get_page(array('id' => $id));
	
		$obj->load->model('tags_model', 'tag');
		if($tags = $obj->tag->get_tags(array('srcid' => $page['id'], 'module' => 'page')))
		{
			foreach ($tags as $tag)
			{
				$tag_string .= $tag['tag'] . ", ";
			}
			$tag_string = substr($tag_string, 0, strlen($tag_string) - 2); //remove the ,
		}
	}
	
	$output = "
<label for=\"tags\">" . __("Tags:", "tags") . "</label>
<input type=\"text\" name=\"tags\" value=\"" . $tag_string . "\" id=\"tags\" class=\"input-text\" /><br />
" . __("Enter tags separated by comma", "tags") . "<br />";
	
	return $output;
}

function tag_page_save($id)
{
	$obj =& get_instance();
	$obj->load->model('tags_model', 'tag');
	if ($tagitem = $obj->input->post('tags'))
	{
		$page = $obj->pages->get_page(array('id' => $id));
		$tags = explode(",", $tagitem);

		$obj->tag->delete(array('srcid' => $id, 'module' => 'page'));
		
		foreach ($tags as $tag)
		{
			$data = array(
				'url' => $page['uri'], 
				'title' => $page['title'],
				'lang' => $page['lang'],
				'srcid' => $page['id'],
				'tag' => trim($tag),
				'module' => 'page',
				'summary' => substr(strip_tags($page['body']), 0, 200)
				);
			$obj->tag->save_tag($data);
		}
	}
}

?>