<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/
 
 class Palbum extends Controller {
 
 	function Palbum()
	{
		parent::Controller();
	

		$this->template['module']	= 'palbum';
		$this->settings = isset($this->system->palbum_settings) ? unserialize($this->system->palbum_settings) : null;
		$this->load->helper('xml');
		$this->load->library('cache');
		
		if (is_null($this->settings))
		{
			echo "Please configure your Palbum module ";
			exit();
		}
	}
	
	
	function header()
	{
		$data['width'] = $this->settings['thumbwidth'] + 10;
		$this->load->view('header', $data);
		
	}
	
	
	function index($album = null, $photoid = null)
	{
		
		$userid = $this->settings['userid'];
		
		$this->template['userid'] = $userid;
		$this->plugin->add_action('header', array(&$this, 'header'));
		if (is_null($album))
		{
			//lisitry ny album rehetra
			$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "?kind=album&thumbsize="  . $this->settings['thumbwidth'] . "c";
			
			if (!$albums = $this->cache->get(md5($feedurl), 'palbum'))
			{
				$xml = file_get_contents($feedurl);
				$feed = xmlize($xml);
				$entries = $feed['feed']['#']['entry'];
				$albums = array();
				foreach ($entries as $key => $entry)
				{
					$albums[$key]['id'] = $entry['#']['gphoto:id']['0']['#'];
					$albums[$key]['thumbnail'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['url'];
					$albums[$key]['height'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['height'];
					$albums[$key]['width'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['width'];
					
					$albums[$key]['title'] = $entry['#']['title']['0']['#'];
					$albums[$key]['name'] = $entry['#']['gphoto:name']['0']['#'];
				}
				
				$this->cache->save(md5($feedurl), $albums, 'palbum', $this->settings['ttl'] );
			}
			
			$this->template['albums'] = $albums;
			$this->layout->load($this->template, "index");
		}
		else
		{
			if (is_null($photoid))
			{
				//lisitry nys ary rehetra
				$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "/albumid/" . $album . "?thumbsize="  . $this->settings['thumbwidth'];
				
				$album = array();
				if (!$album = $this->cache->get(md5($feedurl), 'palbum'))
				{
					$xml = file_get_contents($feedurl);
					$feed = xmlize($xml);
					
					$album['name'] = $feed['feed']['#']['gphoto:name']['0']['#'];
					$album['id'] = $feed['feed']['#']['gphoto:id']['0']['#'];
					
					foreach ($feed['feed']['#']['entry'] as $key => $entry)
					{
						$album['photo'][$key]['id'] = $entry['#']['gphoto:id']['0']['#'];
						$album['photo'][$key]['title'] = $entry['#']['title']['0']['#'];
						$album['photo'][$key]['thumbnail'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['url'];
						$album['photo'][$key]['height'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['height'];
						$album['photo'][$key]['width'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['width'];
					}
				
					$this->cache->save(md5($feedurl), $album, 'palbum', $this->settings['ttl'] );
				}
				
				$this->template['album'] = $album;
				
				$this->layout->load($this->template, "album");

			}
			else
			{
				//info momba ny sary iray

				$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "/albumid/" . $album . "?thumbsize=" . $this->settings['thumbwidth'] . "&imgmax="  . $this->settings['maxwidth'];
				
				$album = array();
				if (!$photo = $this->cache->get(md5($feedurl . $photoid), 'palbum'))
				{
					$xml = file_get_contents($feedurl);
					$feed = xmlize($xml);
					$entries = $feed['feed']['#']['entry'];
					
					foreach ($entries as $key => $entry)
					{
						if ($entry['#']['gphoto:id']['0']['#'] == $photoid)
						{
							$photo['id'] = $entry['#']['gphoto:id']['0']['#'];
							$photo['title'] = $entry['#']['title']['0']['#'];
							$photo['url'] = $entry['#']['media:group']['0']['#']['media:content']['0']['@']['url'];
							$photo['height'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['height'];
							$photo['width'] = $entry['#']['media:group']['0']['#']['media:thumbnail']['0']['@']['width'];
							$photo['previd'] = (isset($entries[ $key - 1 ]['#']['gphoto:id']['0']['#'])) ? $entries[ $key - 1 ]['#']['gphoto:id']['0']['#'] : null;
							$photo['nextid'] = (isset($entries[ $key + 1 ]['#']['gphoto:id']['0']['#'])) ? $entries[ $key + 1 ]['#']['gphoto:id']['0']['#'] : null;
							$photo['albumid'] =  $entry['#']['gphoto:albumid']['0']['#'];
							$photo['updated'] = $entry['#']['updated']['0']['#'];
							$photo['credit'] = $entry['#']['media:group']['0']['#']['media:credit']['0']['#'];
							$photo['description'] = $entry['#']['media:group']['0']['#']['media:description']['0']['#'];
						}
					}
					
					$this->cache->save(md5($feedurl . $photoid), $photo, 'palbum', $this->settings['ttl'] );
				}
				$this->template['photo'] = $photo;
				$this->layout->load($this->template, "photo");

			}
		}
		
	}

	function refresh($album = null, $photo = null)
	{
	
		$userid = $this->settings['userid'];
	
		if (is_null($album))
		{
			//lisitry ny album rehetra
			$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "?kind=album&thumbsize="  . $this->settings['thumbwidth'] . "c";

		}
		else
		{
			if (is_null($photo))
			{
				//lisitry nys ary rehetra
				$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "/albumid/" . $album . "?thumbsize="  . $this->settings['thumbwidth'];
			}
			else
			{
				//info momba ny sary iray

				$feedurl = "http://picasaweb.google.com/data/feed/api/user/" . $userid . "/albumid/" . $album . "/photoid/" . $photo . "?imgmax="  . $this->settings['maxwidth'];
			
			}
		}
		
		$this->cache->remove(md5($feedurl), 'palbum');
		redirect ('palbum/index/' . $album . '/' . $photo);
	}	
}