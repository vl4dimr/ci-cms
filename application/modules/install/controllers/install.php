<?php 

class Install extends Controller 
{
	function Install()
	{
		parent::Controller();
		$this->load->database();
	}
	
	function index()
	{
		$query = $this->db->query("SHOW TABLE STATUS LIKE '" . $this->db->dbprefix('settings') . "' ");
		if($query->num_rows() > 0)
		{
			redirect('install/update');
			exit();
		}

		echo "<p>You are about to install CI-CMS</p>";
		echo "<p>Before you continue, <ol><li>check that you have a file config.php and database.php in your configuration folder and all values are ok.</li><li>make writable the <b>media</b> folder</li></p>";
		echo "<p>If you get a database error in the next step then your database.php file is not ok</p>";
		echo "<p>" . anchor('install/step1', 'Click here to continue') . "</p>";

	}
	
	function step1()
	{
		$folders = array(
			'./media/images', 
			'./media/images/o', 
			'./media/images/m', 
			'./media/images/s',
			'./media/files',
			'./media/captcha'
		);
		
		foreach ($folders as $f)
		{
			if( @mkdir($f))
			{
				echo "Folder $f created<br />";
			}
			else
			{
				echo "<span style='color: red'>ERROR: folder $f not created.</span> Please create it manually.<br />";
			}
		}
			
		echo "<p>" . anchor('install/step2', 'Click here to continue') . "</p>";
		
	}
	
	
	function step2()
	{
	
		$this->load->dbforge();	

		
		
		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'code' => array(
				'type' => 'CHAR',
				'constraint' => 5
			 ),
			'name' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
					 'default' => ''
			  ),
			 'ordering' => array(
				'type' => 'INT',
				'constraint' => 5,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'default' => array(
					 'type' => 'TINYINT',
					 'constraint' => '2',
					'default' => 0
			  )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('code');
		$this->dbforge->create_table('languages', TRUE);
		
		$query = $this->db->get('languages');
		
		if($query->num_rows() == 0)
		{
		$this->db->query("INSERT INTO " . $this->db->dbprefix('languages') . " (`id`, `code`, `name`, `ordering`, `active`, `default`) VALUES (1, 'en', 'English', 1, 1, 1), (2, 'fr', 'Fran&ccedil;ais', 2, 1, 0),  (3, 'it', 'Italiano', 3, 1, 0)");
		}
		

  
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'parent_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'g_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '20',
					 'default' => '0'
			  ),
			'uri' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
			  )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('active');
		$this->dbforge->add_key('weight');
		$this->dbforge->add_key('parent_id');
		$this->dbforge->create_table('navigation', TRUE);
		
		$query = $this->db->get('navigation');
		
		if($query->num_rows() == 0)
		{
			$this->db->query("INSERT IGNORE INTO " . $this->db->dbprefix('navigation') . " (id, parent_id, title, uri, lang) VALUES (19, 0, 'leftmenu', '', 'en'), (1, 19, 'Menu', '', 'en'),  ( 1, 19, 'Menu', '', 'en'), ( 2, 1, 'Home', 'home', 'en'), ( 3, 1, 'About', 'about', 'en'), ( 20, 0, 'leftmenu', '', 'fr'), ( 4, 20, 'Menu', '', 'fr'), ( 5, 4, 'Accueil', 'accueil', 'fr'), ( 6, 4, 'A propos', 'a-propos', 'fr'), ( 21, 0, 'leftmenu', '', 'it'), ( 7, 21, 'Menu', '', 'it'), ( 8, 7, 'Home', 'home', 'it'), ( 9, 7, 'About', 'about', 'it'), ( 10, 0, 'topmenu', '', 'en'), ( 11, 10, 'Contact us', 'contact-us', 'en'), ( 12, 10, 'Google', 'http://google.com', 'en'), ( 13, 0, 'topmenu', '', 'fr'), ( 14, 13, 'Contact us', 'contact-us', 'fr'), ( 15, 13, 'Google', 'http://google.com', 'fr'), ( 16, 0, 'topmenu', '', 'it'), ( 17, 16, 'Contact us', 'contact-us', 'it'), ( 18, 16, 'Google', 'http://google.com', 'it') ");
		}


		
		//users
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'username' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'password' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 ),
			 'status' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'default' => 'active'
			),
			 'lastvisit' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			 'registered' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			 'online' => array(
				'type' => 'INT',
				'constraint' => 1,
				'default' => 0
			),
			 'activation' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('username');
		$this->dbforge->add_key('status');
		$this->dbforge->create_table('users', TRUE);
		
		
		echo "<p>Step 2 completed.</p>";
		
		$query = $this->db->get('users');
		
		if($query->num_rows() == 0)
		{
			echo "<p> Now we need some information for the super admin.</p><form method='post' action='" . site_url('install/step3') . "'>
			<label for='username' style='width: 150px; font-weight: bold;'>Username : </label><input type='text' name='username' value='admin' style='width: 200px'/><br />
			<label for='password' style='width: 150px; font-weight: bold;'>Password : </label><input type='password' name='password' value='' style='width: 200px'/><br />
			<label for='email' style='width: 150px; font-weight: bold;'>Email : </label><input type='text' name='email' value='' style='width: 200px'/><br />
			<input type='submit' value='  Continue... ' />
			</form>";
		}
		else
		{
			echo "<p>Step 3 not needed, " . anchor('install/step4', 'Click here to continue') . "</p>";
		}

	
	}
	
	function step3()
	{
			
		$query = $this->db->get('users');
		
		if($query->num_rows() == 0)
		{

			if(($username = $this->input->post('username')) && ($password = $this->input->post('password')) && ($email = $this->input->post('email')))
			{
			$this->load->library('encrypt');
			$data	= 	array(
							'username'	=> $username,
							'password'	=> $this->encrypt->sha1($password.$this->config->item('encryption_key')),
							'email'		=> $email,
							'status'	=> 'active',
							'registered'=> mktime()
						);
			$this->db->insert('users', $data);
				$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('admins' ) . " ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `module` varchar(100) NOT NULL default '', `level` tinyint(4) NOT NULL default '0', PRIMARY KEY (`id`), KEY `username` (`username`) ) ");
				
				$this->db->insert('admins', array('username' => $username, 'module' => 'admin', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'page', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'module', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'news', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'member', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'language', 'level' => 4));
				
				
				
				
				
				echo "<p>Step 3 completed, " . anchor('install/step4', 'Click here to continue') . "</p>";
				
			}
			else
			{
			echo "<p> Please fill all fields.</p><form method='post' action='" . site_url('install/step3') . "'>
			<label for='username' style='width: 150px; font-weight: bold;'>Username : </label><input type='text' name='username' value='" . $this->input->post('username') . "' style='width: 200px'/><br />
			<label for='password' style='width: 150px; font-weight: bold;'>Password : </label><input type='password' name='password' value='" . $this->input->post('password') . "' style='width: 200px'/><br />
			<label for='email' style='width: 150px; font-weight: bold;'>Email : </label><input type='text' name='email' value='" . $this->input->post('username') . "' style='width: 200px'/><br />
			<input type='submit' value='  Try again... ' />
			</form>";
				return;
			}
		}
		else
		{
			echo "<p>Step 3 completed, " . anchor('install/step4', 'Click here to continue') . "</p>";
		}
	
	}
	
	function step4()
	{
		$this->load->dbforge();	
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'parent_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'uri' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					 'default' => 'en'
			  ),
			'meta_keywords' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'meta_description' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'body' => array(
					 'type' => 'LONGTEXT',
					 'null' => true
			  ),
			'hit' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					'default' => 0
			  ),
			'options' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'email' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			 'date' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					 'default' => mktime()
			  ),
			'username' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'g_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '20',
					 'default' => '0'
			  ),

		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('uri');
		$this->dbforge->add_key('active');
		$this->dbforge->create_table('pages', TRUE);


		$query = $this->db->get('pages');
		
		if($query->num_rows() == 0)
		{
		$data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'en', 'body' => '<p>This is how it looks in <b>English</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);
		$data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'en', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);

		$data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'fr', 'body' => '<p>This is how it looks in <b>French</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);
		$data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'fr', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);

		$data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'it', 'body' => '<p>This is how it looks in <b>Italian</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);
		$data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'it', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
		$this->db->insert('pages', $data);
		
		}
		//page comments
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'status' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'author' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'website' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'website' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'body' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'email' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'ip' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			 'date' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					 'default' => mktime()
			  )

		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('page_id');
		$this->dbforge->add_key('date');
		$this->dbforge->create_table('page_comments', TRUE);		
		
		
  		$fields = array(
			'session_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => 40,
					 'default' => '0'
			  ),
			 'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 16,
				'default' => '0'
			 ),
			'user_agent' => array(
					 'type' => 'VARCHAR',
					 'constraint' => 50,
					 'default' => ''
			  ),
			'last_activity' => array(
					 'type' => 'INT',
					 'constraint' => '10',
					'default' => 0
			  ),
			'user_data' => array(
					 'type' => 'TEXT'
			  ),
		);
		
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table('sessions', TRUE);
		//settings
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => '0'
			 ),
			 'value' => array(
				'type' => 'TEXT',
				'default' => ''
			 )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('settings', TRUE);
		
		$query = $this->db->get('settings');
		
		if($query->num_rows() == 0)
		{
		$data = array('name' => 'site_name', 'value' => 'CI-CMS');
		$this->db->insert('settings', $data);
		
		$data = array('name' => 'meta_keywords', 'value' => 'CI-CMS');
		$this->db->insert('settings', $data);
		$data = array('name' => 'meta_description', 'value' => 'CI-CMS, another content managment system');
		$this->db->insert('settings', $data);
		$data = array('name' => 'cache', 'value' => '0');
		$this->db->insert('settings', $data);
		$data = array('name' => 'cache_time', 'value' => '300');
		$this->db->insert('settings', $data);
		$data = array('name' => 'theme', 'value' => 'default');
		$this->db->insert('settings', $data);
		$data = array('name' => 'template', 'value' => 'index');
		$this->db->insert('settings', $data);
		$data = array('name' => 'page_home', 'value' => 'home');
		$this->db->insert('settings', $data);
		$data = array('name' => 'debug', 'value' => '0');
		$this->db->insert('settings', $data);
		$data = array('name' => 'version', 'value' => '0.9.2.1');
		$this->db->insert('settings', $data);
		$data = array('name' => 'page_approve_comments', 'value' => '1');
		$this->db->insert('settings', $data);
		$data = array('name' => 'page_notify_admin', 'value' => '1');
		$this->db->insert('settings', $data);
		$data = array('name' => 'news_settings', 'value' => serialize(array('allow_comments' => 1,'approve_comments' => 1)));
		$this->db->insert('settings', $data);
		
		}
		//modules
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'with_admin' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			 'version' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			 'ordering' => array(
				'type' => 'INT',
				'constraint' => 4,
				'default' => 0
			),
			 'info' => array(
				'type' => 'TEXT'
				),
			 'description' => array(
				'type' => 'TEXT'
			)
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('modules', TRUE);
		
		$query = $this->db->get('modules');
		
		if($query->num_rows() == 0)
		{
		$this->db->query("INSERT INTO " . $this->db->dbprefix('modules') . " (id, name, with_admin, version, status, ordering, info, description) VALUES (1, 'admin', 0, '1.2.0', 1, 5, '', 'Admin core module'), (2, 'module', 0, '1.0.0', 1, 20, '', 'Module core module'), (3, 'page', 1, '1.2.0', 1, 60, '', 'Page core module'), (4, 'language', 1, '1.1.0', 1, 10, '', 'Language core module'), (5, 'member', 1, '1.0.0', 1, 30, '', 'Member core module'), (6, 'search', 0, '1.0.0', 1, 50, '', 'Search core module'), (7, 'news', 1, '1.3.0', 1, 101, '', 'News module')");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('group_members') . " ( id int(11) NOT NULL auto_increment, g_user varchar(255) NOT NULL default '', g_id varchar(20) NOT NULL default '', g_from int(11) NOT NULL default '0', g_to int(11) NOT NULL default '0', g_level int(1) NOT NULL default  1, g_date int(11) NOT NULL default '0', PRIMARY KEY (id), KEY g_user (g_user,g_id) )");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('groups') . " ( id int(11) NOT NULL auto_increment, g_id varchar(20) NOT NULL default '', g_owner varchar(20) NOT NULL default '', g_name varchar(255) NOT NULL default '', g_desc text NOT NULL, g_date int(11) NOT NULL default '0', g_info text NOT NULL, PRIMARY KEY (id), UNIQUE KEY g_id (g_id,g_name) )");
		
		$this->db->query("INSERT INTO " . $this->db->dbprefix('groups') . " (g_id, g_name, g_desc) VALUES ('0', 'Everybody', 'This is everybody who visits the site including non members')");

		$this->db->query("INSERT INTO " . $this->db->dbprefix('groups') . " (g_id, g_name, g_desc) VALUES ('1', 'Members Only', 'This is everybody who is member of the site')");

		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('search_results') . " ( id int(11) NOT NULL auto_increment, s_rows text NOT NULL, s_tosearch varchar(255) NOT NULL default '', s_date int(11) NOT NULL default '0', PRIMARY KEY (id) )");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('images') . " ( `id` int(11) NOT NULL auto_increment, `module` varchar(100) NOT NULL default '', `file` varchar(255) NOT NULL default '', `src_id` int(11) NOT NULL default '0', `ordering` tinyint(4) NOT NULL default '0', `info` text NOT NULL, PRIMARY KEY (`id`) )") ;
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('captcha') . " (	captcha_id bigint( 13 ) unsigned NOT NULL AUTO_INCREMENT , 	captcha_time int( 10 ) unsigned NOT NULL , 	ip_address varchar( 16 ) default '0' NOT NULL , 	word varchar( 20 ) NOT NULL , 	PRIMARY KEY ( captcha_id ) , 	KEY ( word ) )");
 		}
		echo "<p>Step 4 completed. " . anchor('install/step5', 'Click here to continue') . "</p>";
		
	}
	
	function step5()
	{
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news') . " ( `id` INT NOT NULL AUTO_INCREMENT , `cat` INT NOT NULL DEFAULT '0', `title` VARCHAR( 255 ) NOT NULL , `uri` VARCHAR( 255 ) NOT NULL , `lang` VARCHAR( 255 ) NOT NULL , `body` TEXT NOT NULL , `allow_comments` tinyint(1) NOT NULL DEFAULT '1', `comments` int(4) NOT NULL, `status` INT(3) NOT NULL DEFAULT '0', `date` INT NOT NULL , `author`VARCHAR( 255 ) NOT NULL , `email` VARCHAR( 255 ) NOT NULL , `notify` TINYINT NOT NULL , `hit` INT(11) NOT NULL DEFAULT '0', `ordering` INT(11) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) , INDEX ( `title` ) )"); 

		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news_comments') . " (   `id` int(11) NOT NULL auto_increment,   `news_id` int(11) NOT NULL,   `status` int(2) NOT NULL DEFAULT '0',   `date` int(11) NOT NULL,   `author` varchar(50) NOT NULL,   `email` varchar(100) NOT NULL,   `website` varchar(150) NOT NULL,   `body` text NOT NULL,   `ip` varchar(150) NOT NULL,     PRIMARY KEY  (`id`),   KEY `news_id` (`news_id`),   KEY `date` (`date`),   KEY `status` (`status`) )");

		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('captcha') . " ( captcha_id bigint( 13 ) unsigned NOT NULL AUTO_INCREMENT , captcha_time int( 10 ) unsigned NOT NULL , ip_address varchar( 16 ) default '0' NOT NULL , word varchar( 20 ) NOT NULL , PRIMARY KEY ( captcha_id ) , KEY ( word ) )");

		$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news_cat') . "  ( `id` int(11) NOT NULL auto_increment, `pid` int(11) NOT NULL default '0', `title` varchar(255) NOT NULL default '', `icon` varchar(255) NOT NULL default '', `desc` text NOT NULL, `date` int(11) NOT NULL default '0', `username` varchar(20) NOT NULL default '', `lang` char(5) NOT NULL default '', `weight` int(11) NOT NULL default '0', `status` int(5) NOT NULL default '1', `acces` varchar(20) NOT NULL default '0', `uri` varchar(100) NOT NULL default '', PRIMARY KEY  (`id`), KEY `title` (`title`) )");

$fields = array(
	'id' => array(
			 'type' => 'INT',
			 'constraint' => 5,
			 'unsigned' => TRUE,
			 'auto_increment' => TRUE
	  ),
	'tag' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'uri' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'news_id' => array(
			 'type' => 'INT',
			 'constraint' => '5',
	  )
);
$this->load->dbforge();
$this->dbforge->add_field($fields); 
$this->dbforge->add_key('id', TRUE);
$this->dbforge->add_key('tag');
$this->dbforge->create_table('news_tags', TRUE);

		
		$query = $this->db->get('news');
		
		if($query->num_rows() == 0)
		{
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-en', 'lang' => 'en', 'body' => 'This news is supposed to be in English but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-fr', 'lang' => 'fr', 'body' => 'This news is supposed to be in French but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-it', 'lang' => 'it', 'body' => 'This news is supposed to be in Italian but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		}
		echo "<p>Step 5 completed. " . anchor('install/step6', 'Click here to continue') . "</p>";
		
	}
	
	function step6()
	{
		echo "Installation done. <br />To go to admin interface ". anchor('admin', 'click here') . "<br/>Now you can visit your site " . anchor('', 'here') ;

	}


}
