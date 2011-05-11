# HyperLivre

* Version: 0.1 (in active, but early development)
* FuelPHP Version: 1.0-RC2

## Hyperlivre uses Fuel

Fuel is a fast, lightweight PHP 5.3 framework. HyperLivre is a Fuel-powered web application that tries to stay regularly updated with the latest Fuel changes. For more info about fuel, please visit :

* [Website](http://fuelphp.com/)
* [Documentation](http://fuelphp.com/docs) and in its own [git repo](https://github.com/fuel/docs)
* [Bugtracker & feature requests](http://dev.fuelphp.com)
* [Forums](http://fuelphp.com/forums) for comments, discussion and community support

## Description

The purpose of HyperLivre is to allow users to contribute on writing short notes, or short pieces of knowledge. This is not quite a blog, not a CMS, nor a concurrent editing application. HyperLivre should remain simple, without fancy staff build into it. It still borrows similar common notions from those platforms, and can be seen in some way as a lightweight collaborative blog or wiki.

Pieces of knowledge, called notes, must stay concise and precise, going straight to the point. They may contain interesting pieces of work (code for instance), provide real and working examples, or explore a general concept. At a first glance, HyperLivre looks like a blog. But, notes can be written by groups of people, their material need to be structured in some way, and their subjects bound to pre-created concepts.

Searchers in the language field were interested to try an alternative writing method. Using short notes to progress, they wanted to guide and assess the knowledge built in this way. The HyperLivre project was initially created for this purpose. With semantic hyperlinks there is a way to organize ideas, or more helpfully some concepts emerge in the graph as central ideas. By electing a supervisor user which can edit concepts, it is then possible to guide knowledge exploration.

## Actual Status

The initial HyperLivre project, needed to be massively refactored. As developers, we wanted to try the young FuelPHP framework which looks really awesome, and give it a good try. We recycled some of the original requirements and added some we found interesting to have. The initial HyperLivre relied also on an other project (Lazy) which was used on the backend. Something we wanted HyperLivre to possess on its own.

The refactoring main purposes were to achieve better usability, portability and modularity. We wanted to keep things as simple and clean as possible on both ends, and still improve the frontend user experience.

This brand new project is actually under active development. Right now, only a few basic functionalities are available, compared to the initial HyperLivre project. But, stay tuned !

## Developers

* Alex Bulla -- Developer
* Michael Gumowski -- Developer, Designer

## Requirements

HyperLivre actually needs MySQL and at least PHP 5.3 to run. Please use a local host to tweak some configuration details before going live.

## Downloading HyperLivre

Use git to clone the HyperLivre project, and obtain regular updates from our GitHub repository. You can also use the download link above if you don't have git. To get a fresh update of the Fuel framework, please separately download the latest archive from the [Fuel website](http://fuelphp.com/) and try to update HyperLivre to reflect eventual changes.

## Cloning HyperLivre

You can use git to clone the HyperLivre repository :

    git clone git://github.com/dream4ge/HyperLivre.git
	
## Cloning Fuel

Fuel uses submodules for things like the **core** folder.  After you clone the repository you will need to init and update the submodules.

Here is the basic usage:

    git clone --recursive git://github.com/fuel/fuel.git

The above command is the same as running:

    git clone git://github.com/fuel/fuel.git
    cd fuel/
    git submodule init
    git submodule update

You can also shorten the last two commands to one:

    git submodule update --init

## Installation
 
After cloning HyperLivre using the steps above, you will do a few things to get HyperLivre up and running:

* Import the **hyperlivre_database.sql** to your database
* Make sure your existing tables match the .sql file specification
* Change the database details in _/fuel/app/config/db.php_
* Change the table name in _/fuel/packages/auth/config/simpleauth.php_

	``'table_name' => 'users'``

If you want to use mod\_rewrite, do the following changes in _/fuel/app/config/config.php_:

	'index_file' => false,

## Special thanks

Thanks to the FuelPHP Team for their great work, and to [stationwagon](https://github.com/abdelm/stationwagon) which helped us a lot at the beggining.

## Contribute

If you are interested in adding more features to HyperLivre, fork the repository and make sure you make a pull request after you pushed changes to your HyperLivre fork.

To report any bugs or problems, create a new issue here in our GitHub repository.
