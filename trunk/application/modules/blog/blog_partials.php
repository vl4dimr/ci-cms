<?php

	class Blog_Partials {
		
		function Blog_Partials()
		{
			$this->obj =& get_instance();
			
			$this->obj->load->model('blog_model');
		}
		
		function latest_items()
		{
			$data['posts'] = $this->obj->blog_model->latest_headlines(10);
			return $this->obj->load->view('partials/latest', $data, true);
		}
		
		function tag_cloud()
		{
			$data['tags'] = $this->obj->blog_model->tag_cloud(10);

			if (!empty($data['tags']))
			{
				// change these font sizes if you will
				$data['max_size'] = 150; // max font size in %
				$data['min_size'] = 100; // min font size in %

				// get the largest and smallest array values
				$data['max_qty'] = max(array_values($data['tags']));
				$data['min_qty'] = min(array_values($data['tags']));

				// find the range of values
				$data['spread'] = $data['max_qty'] - $data['min_qty'];

				if (0 == $data['spread']) { // we don't want to divide by zero
				    $data['spread'] = 1;
				}

				// determine the font-size increment
				// this is the increase per tag quantity (times used)
				$data['step'] = ($data['max_size'] - $data['min_size'])/($data['spread']);

				return $this->obj->load->view('partials/tag_cloud', $data, true);	
			}
		}
	}

?>