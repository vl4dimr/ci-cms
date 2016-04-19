# Introduction #

This CMS is fully modulable. You can install, activate, deactivate, uninstall module anytime.

Modules are in the module directory in
```
application/modules/
```

# Core modules #

There are few core modules that cannot be uninstalled nor deactivated. They are
  * admin: Not listed in the module table but dealing with all backend administration
  * language: Module for managing language
  * member: Module to manage users
  * page: Page module for content and the homepage.

# Other modules #

All other modules are installable
Here are the modules that are bundled with the release
  * blog (not yet ready)
  * contact (not yet ready)
  * ...

# Install a module #
After you download a module (BE SURE THAT YOU DOWNLOAD IT FROM A SECURE SOURCE) just put it inside the module directory. Go in Admin -> Module settings and normally the module will be listed and ready to be installed. When it is installed, you still need to activate it.

# Create a module #

If you want to create your own module, there are just few things to know

### 1. setup.xml installation file ###
You should have a setup.xml file in the folder of your module. It should have this format

```

<?xml version="1.0" ?>
<module>
	<name>guestbook</name>
	<date>20/06/2008</date>
	<author>hery</author>
	<email>hery@serasera.org</email>
	<url>http://hery.serasera.org</url>
	<copyright>GNU/GPL License</copyright>
	<version>1.0</version>
	<description>Guestbook module</description>
	<install>
		<query>CREATE TABLE....</query>
		<query>INSERT INTO...</query>
	</install>
	<uninstall>
		<query>DROP TABLE....</query>
	</uninstall>
</module>
```

**explanation**

  * **name:** name of the module
  * **date:** creation date
  * **author:**
  * **email:**
  * **url:**
  * **copyright:**
  * **version:**
  * **description:** Module description
  * **install:** Set of installation queries that will be applied into the database
  * **uninstall:** Set of uninstallation queries that will be applied during uninstallation

> ### 2. File structure ###
This is how should be structured your module directory and files

Let's say your module is called _example_

```
 APPPATH
    |__modules
          |__ example
                 |__ controllers
                 |       |__ admin.php (backend page: optional)
                 |       |__ index.php (front-end page of the module)
                 |       |__ ... (other files)
                 |
                 |__ models
                 |       |__ example_model.php (your model file)
                 |                         
                 |__ views
                 |       |__ partials (partial folder: if any)
                 |       |      |__ example_partial_test.php
                 |       | 
                 |       |__ admin.php (backend view) 
                 |       |__ index.php (backend view)
                 |
                 |__ example_install.php
                 |__ example_uninstall.php
                 |__ example_plugins.php
                 |__ example_blocks.php
                 |__ setup.xml

```

### 3. Blocks ###
In the file modulename\_blocks.php you can add functions to set blocks that may be used in template files.

The page\_blocks.php in the page module is like this

```
$this->set('page_latest_pages', 'page_latest_pages');
		
function page_latest_pages($limit = 5)
{
	$this->obj =& get_instance();
	
	$this->obj->load->model('page_model');
	$data['pages'] = $this->obj->page_model->new_pages($limit);
	echo $this->obj->load->view('partials/newpages', $data, true);
}

```

The **$this->set('block\_name', 'function\_callback')** will set the block name and return or echoes the function callback when called in template.

Here, for example, to get the 10 latest pages in the templates, you should add

`$this->blocks->get('page_latest_pages', 10)`

### 4. Plugins ###
Plugins will be used to hook the core. There will be some events that you can interact with.