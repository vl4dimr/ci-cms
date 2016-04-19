#hooks

# ci-cms hooks #

In my old cms there are some hooks that can catch events easily. I put it in ci-cms to allow modules acting with them. The hooks work like in [WordPress](http://codex.wordpress.org/Plugin_API). There are two types of hooks: actions and filters. Actions are just a function that will be run at some point, when the hook is rendered. Filters will return the modified parameters.

Some hooks

### Actions ###
#### news\_save ####
_passing $id : the saved news id_

Tiggered when saving or updating a news.

#### news\_delete ####
_passing $id : the id of the deleted news_

Tiggered when deleting an article.

#### page\_save ####
_passing $id : the saved page id_

Tiggered when saving a page. You can then play with the page id

#### page\_delete ####
_passing $id : the id of the deleted page_

Tiggered when deleting a page.
### Filters ###

#### feed\_content ####

$content
a list of array containing something like this

```
$contents[$key]['title'] = $row['title'];
$contents[$key]['url'] = site_url($row['uri']);
$contents[$key]['body'] = $row['body'];
$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
```

#### news\_custom\_fields ####
_type = string_

#### news\_pre\_content ####
_type = string_

Goes before news content. Just after the title

#### news\_content ####
_type = string_

the news content itself
#### news\_post\_content ####
_type = string_

Goes just after news content


#### page\_item ####
This is the page item (as array) before it is sent to the view. So that you can then modify it's content etc.

#### page\_custom\_fields ####
_type = string_

This will show inside the second tab of page create

You can add other fields, like tags etc.

example
```

// the parameters just mean that priority is 10 and allowed fields are 2 which is 
// $string and $id

$this->add_filter('page_custom_fields', 'tag_page_fields', 10, 2);


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

```

#### member\_profile\_quick\_menu ####
This should return an array to show a quick menu in the member profile form
```
$qm = array("Menu item" => "http://menu.link.com");
```

#### member\_profile\_pre\_form ####
Text to put before all the fields for profile

#### member\_profile\_post\_form ####
Text to put before after the fields for profile

#### member\_signup\_pre\_form ####

Text to put before the signup form. It is included in form so other fields can be put here.

#### member\_signup\_post\_form ####

Like members\_signup\_pre\_form but this is under the fields, just about the submit button.

#### member\_registered\_msg ####

When a member is registered, he receives a message with his password. After that, there is a space where a customized message can be added.

#### search\_result ####

Search result returns an array

```
$rows = array('result_link', 'result_title', 'result_type', 'result_date', 'result_text', 'result_order');
```

The elements are optionals.

It means in your module, you can hook the "search\_result" hook like this.

in your\_module\_plugin.php
```
$this->add_filter('search_result', 'your_module_search_result');
	
function your_module_search_result($rows)
{
    //modify rows here
    return $rows;
}
```