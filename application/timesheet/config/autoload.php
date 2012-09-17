<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Helper files
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packges
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

// set_include_path(get_include_path().'/Users/fabilo/shared_package/');	

// helper 
require_once('helpers/View_Helper.php');
// include interfaces
require_once('interfaces/Project_Factory_Interface.php');
require_once('interfaces/Timelog_Category_Factory_Interface.php');
require_once('interfaces/User_Interface.php');
// include libraries
require_once('libraries/base/Exceptions.php');
require_once('libraries/base/Base_Render_Library.php');
require_once('libraries/base/AbstractEntity.php');
// require_once('libraries/base/Base_Auth_Controller.php'); // not included in time, must be included in individual controller files
require_once('libraries/base/Base_Domain_Model.php');
require_once('libraries/base/Base_PDO_Factory.php');
require_once('libraries/Timesheet.php');
// include domain model classes
require_once('domain_models/Team.php');
require_once('domain_models/Department.php');
require_once('domain_models/Project.php');
require_once('domain_models/Timelog.php');
require_once('domain_models/Timelog_Category.php');
require_once('domain_models/User.php');
// include factory classes
require_once('factories/Department_Factory.php');
require_once('factories/Project_Factory.php');
require_once('factories/Team_Factory.php');
require_once('factories/Timelog_Factory.php');
require_once('factories/Timelog_Categories_Factory.php');

$autoload['packages'] = array('/Users/fabilo/shared_package');

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/

$autoload['libraries'] = array('session');


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

$autoload['helper'] = array('url');


/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

$autoload['model'] = array();


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */