<?php 
/**
 * This file contains a linear script to cull the currently configured database and fill it with dummy data
 */
// setup autoloading
function __autoload($class_name) {
    include $class_name . '.php';
}
// include shared package bootstrap
require('/Users/fabilo/shared_package/bootstrap.php');

// define some constants for the CI config file
define('BASEPATH', 'lol');
define('ENVIRONMENT', 'development');

// get config file
require('../config/database.php');
// setupd db
$db = new PDO('mysql:host='.$db['default']['hostname'].';dbname=admin', $db['default']['username'], $db['default']['password']);

// truncate tables
$db->query('TRUNCATE TABLE timelogs');
$db->query('TRUNCATE TABLE timelog_categories');
$db->query('TRUNCATE TABLE projects');

// insert timelog categories
$timelog_category_factory = new Timelog_Categories_Factory($db); 

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Administration', 
	'department_id' => 1,
	'clarity_reference' => 'General Administration'
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Documentation', 
	'department_id' => 1
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Helping Others', 
	'department_id' => 1
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Investigation', 
	'department_id' => 1
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Maintenance/Bugfixing', 
	'department_id' => 1
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Meetings', 
	'department_id' => 1
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Project Work (Primary)', 
	'department_id' => 1, 
	'clarity_reference' => 'Project Work'
)));

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Project Work (Secondary)', 
	'department_id' => 1, 
	'team_id' => 1
)));

// insert projects
$project_factory = new Project_Factory($db); 

$project_factory->insert(new Project(array(
	'name' => 'Timelog application', 
	'department_id' => 1, 
	'description' => 'Ongoing development on Timelog application'
)));

$project_factory->insert(new Project(array(
	'name' => 'Development Process Improvement', 
	'department_id' => 1, 
	'description' => 'Tasks for improving development processes'
)));

$project_factory->insert(new Project(array(
	'name' => 'PR Website', 
	'department_id' => 1, 
	'description' => 'Tasks for our public relations and marketing website'
)));

$project_factory->insert(new Project(array(
	'name' => 'Productivity mobile app', 
	'department_id' => 1
)));

$project_factory->insert(new Project(array(
	'name' => 'Legacy system rewrite', 
	'department_id' => 1, 
	'description' => 'Replacement of legacy system to release old resources'
)));

$project_factory->insert(new Project(array(
	'name' => 'Server migration', 
	'department_id' => 1, 
	'description' => 'Migrate, test, and launch to new server'
)));