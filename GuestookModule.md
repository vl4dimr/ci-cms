#Creating Guestbook module

# Introduction #

With this example, we will create a Guestbook module to help you understand how to create your own module.


# File structure #

We will create a directory called **guestbook** and put it inside **modules**. At the end, we will get files like this but let us take it one by one:

```

          |__ guestbook
                 |__ controllers
                 |       |__ admin.php (backend page: optional)
                 |       |__ index.php (front-end page of the module)
                 |
                 |__ models
                 |       |__ guestbook_model.php (your model file)
                 |                         
                 |__ views
                 |       |__ admin.php (backend view) 
                 |       |__ index.php (backend view)
                 |
                 |__ guestbook_install.php
                 |__ guestbook_uninstall.php
                 |__ guestbook_plugins.php
                 |__ guestbook_blocks.php
                 |__ setup.xml
```

# setup.xml #
The setup.xml file will make the module installable. So let's create it first.

This is its content.

**setup.xml**
```
<?xml version="1.0" ?>
<module>
        <name>guestbook</name>
        <date>01/03/2010</date>
        <author>hery</author>
        <email>hery@serasera.org</email>
        <url>http://hery.serasera.org</url>
        <copyright>GNU/GPL License</copyright>
        <version>1.0.0</version>
        <description>Guestbook module</description>
</module>

```
There is nothing special, just to define the module.

# Database Structure #

Let us then create first the database structure. We will create 2 tables, one for the guestbook itself and another one for settings. We will let the module installation file to deal with that so we put the creation script inside **guestbook\_install.php**

We create the file **guestbook\_install.php** and we put it in the guestbook directory (as shown in the file structure above)

content of guestbook\_install.php

**guestbook\_install.php**
```

<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('guestbook_posts') . " (
  id int(11) NOT NULL auto_increment,
  g_id varchar(20) NOT NULL default '',
  g_name varchar(255) NOT NULL default '',
  g_site varchar(255) NOT NULL default '',
  g_email varchar(255) NOT NULL default '',
  g_title varchar(255) NOT NULL default '',
  g_ip varchar(255) NOT NULL default '',
  g_msg text NOT NULL,
  g_daty int(11) NOT NULL default '0',
  g_country varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY g_id (g_id)
);
"

$this->db->query($query);

$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('guestbook_settings')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 100 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `username` )
);";

$this->db->query($query);


```

# Create the model #
My next step is to create the model, ie the way to interact with tables.

I created a quite standard model that should work for all module. Just need to change the class name, the file name and the constructor.

here it is

**guestbook\_model.php**
```
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Guestbook_model extends Model {

	var $fields = array();
	function Guestbook_model()
	{
		parent::Model();
		$this->table = 'guestbook_posts';
		$this->fields = array(
			$this->table => array(
				'g_id'  => '',
				'g_name'  => '',
				'g_site'  => '',
				'g_email' => '',
				'g_date' => mktime(),
				'g_ip' => $this->input->ip_address(),
				'g_country' => '',
				'g_msg' => '',
			)
		);

	}


	function get($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => 1,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get' . $hash, $this->table))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit(1);
			//$this->db->select('');
			$this->db->from($this->table);

			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->row_array();
			}

			$this->cache->save('get' . $hash, $result, $this->table, 0);
		}

		return $result;


	}

	function get_list($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => null,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_list' . $hash, $this->table))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit($params['limit'], $params['start']);
			//$this->db->select('');
			$this->db->from($this->table);

			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->result_array();
			}

			$this->cache->save('get_list' . $hash, $result, $this->table, 0);
		}

		return $result;


	}

	function get_total($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => null,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_total' . $hash, $this->table))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);

			$this->db->select('count(id) as cnt');
			$this->db->from($this->table);

			$query = $this->db->get();

			$row = $query->row_array();

			$result = $row['cnt'];

			$this->cache->save('get_total' . $hash, $result, $this->table, 0);
		}

		return $result;

	}

	function delete($params = array())
	{
		$this->db->where($params['where']);
		$this->db->delete($this->table);
		$this->cache->remove_group($this->table);
	}

	function save($data = array())
	{
		$this->db->set($data);
		$this->db->insert($this->table);
		$this->cache->remove_group($this->table);
	}

	function update($where = array(), $data = array(), $escape = true)
	{
		$this->db->where($where);
		$this->db->set($data, null, $escape);
		$this->db->update($this->table);
		$this->cache->remove_group($this->table);
	}

	function get_params($id)
	{
		if($params = $this->cache->get($id, 'search_' . $this->table))
		{
			return $params;
		}
		else
		{
			return false;
		}
	}

	function save_params($params)
	{
		$id = md5($params);
		if($this->cache->get($id, 'search_' . $this->table))
		{
			return $id;
		}
		else
		{

			$this->cache->save($id, $params, 'search_' . $this->table, 0);
			return $id;
		}
	}


}

```

Consideration for **guestbook\_model.php** :
**I already use caching inside model. So access to database is only done when really needed.**

# Controllers #

Now lets' go to the controllers. What would be needed?

**Admin: listing of posts** Admin: edit posts
**Admin: Delete posts** Admin: settings
**Frontend: list posts** Frontend: sign guestbook
