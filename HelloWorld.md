#HelloWorld module

# Introduction #

This is for yoiu to understand how to create a base module


# Download and install CI-CMS #

Download CI-CMS from here http://code.google.com/p/ci-cms/downloads/list and unzip it in your web directory.

  * Make writable the folders **./media** and **./cache**
  * Go to the folder **application/config** and rename **database.dist.php**  to **database.php** then change it to fit your database configuration.
  * Rename config.dist.php to config.php and adjust it to your needs.
  * Go to http://yoursite/install and follow the installation steps

# Open your site #

Normally you can already open your site http://yoursite/ after installation.

# Create Hello World module #

To create a HelloWorld module

Go to application/modules, and create a folder helloworld

Now, inside that, create these folders: controllers, views, models

Inside controllers create a file setup.xml and helloworld.php

## The setup file ##
The content of setup.xml is like this

**setup.xml**
```
<?xml version="1.0" ?>
<module>
        <name>helloworld</name>
        <date>01/03/2010</date>
        <author>hery</author>
        <email>hery@serasera.org</email>
        <url>http://hery.serasera.org</url>
        <copyright>GNU/GPL License</copyright>
        <version>1.0.0</version>
        <description>HelloWorld module</description>
</module>
```

You can ajust the date, author, email, version, description and url. The name is the module's name, here it is helloworld so you may not change that.

## The controller file ##

This is the file where you keep the action to be called.

The content of helloworld.php is like this

**helloworld.php**

```
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Helloworld extends Controller {

        function Helloworld()
        {
           parent::Controller();
        }

        function index()
        {
           echo "Hello World!";
        }                

}
```

Now you can open

http://yoursite/helloworld

you should see

```
Hello world!
```

As you might see, all action is in the **helloworld.php** file inside **controllers**. So that is our conroller.

Now add another function inside the _Helloworld Class_ let us call that function "again".

```
        function again()
        {
           echo "Hello World Again!";
        }
```

The Helloworld controller becomes like this



```
(...)

class Helloworld extends Controller {

        function Helloworld()
        {
           parent::Controller();
        }

        function index()
        {
           echo "Hello World!";
        }                

        function again()
        {
           echo "Hello World Again!";
        }
}
```


Now, open in your site

http://yoursite/helloworld/again

You should see

```

Hello world again!
```

Now you can create as many function (action) as you want inside that Class and open the action in your site.

## Another controller file ##
Now create another file, still inside the **controller** folder. Let's call it **morning.php** and put this inside.

**morning.php**


```
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Morning extends Controller {

        function Morning()
        {
           parent::Controller();
        }

        function index()
        {
           echo "Hello World, in the morning!";
        }                

}
```

Now you can open http://yoursite/helloworld/morning

And you should see
```
Hello World, in the morning!

```

That means, you do not need to put all the actions inside one file. Each file can be an action inside the controller (here the action is morning).

To make **morning** action work here, you needed to name the file **morning.php** and to create the class **Morning** in it.

If you add another function inside the Morning class like this

```
        function again()
        {
           echo "Hello World, in the morning again!";
        }
```

Then you can open your site http://yoursite/helloworld/morning/again to see
```
Hello World, in the morning again!
```

## Parameters ##

Each function in your controller can have parameters.

Lets' try to modify the again method in your Morning class.

Open morning.php and modify the **again** function like this

```

        function again($username = null)
        {
           echo "Hello World, in the morning again " . $username;
        }


```

Now you can open http://yoursite/helloworld/morning/again/mario

And you should see

```
Hello World, in the morning again mario
```

So every part after the /again/ in uri now can be treated as parameters.

Of course you can have more that one parameter eg.

http://yoursite/helloworld/morning/again/mario/betty/smith

you can call them like this


```

        function again($username1 = null, $username2 = null, $username3 = null)
        {
           echo "Hello World, in the morning again " . $username1 . ", " . $username2 . ", " . $username3;
        }


```

## About views ##