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
require(dirname($_SERVER['SCRIPT_NAME']).'/../config/database.php');
// setupd db
$db = new PDO('mysql:host='.$db['default']['hostname'].';dbname='.$db['default']['database'], $db['default']['username'], $db['default']['password']);

// truncate tables
$db->query('TRUNCATE TABLE timelogs');
$db->query('TRUNCATE TABLE timelog_categories');
$db->query('TRUNCATE TABLE projects');
$db->query('TRUNCATE TABLE users');

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

$timelog_category_factory->insert(new Timelog_Category(array(
	'name' => 'Break (15 mins)', 
	'department_id' => 1
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

// insert user
$q = "INSERT INTO users (username, password, department_id, team_id) VALUES ('demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 2)";
$db->query($q);


/** insert Timelogs **/

$timelog_factory = new Timelog_Factory($db, 1);
// setup some dummy timelogs
$dummyTimelogs = array(
	array(
		'start_time' => '8:30', 
		'end_time' => '9:00', 
		'category_id' => 1,
		'notes' => 'Email, tasking & project management'
		, 'user_id' => 1
	)
);

// loop last 30 days
for ($i=1; $i<=60; $i++) {
	$time = strtotime("-$i day");
	// echo date('Y-m-d D', $time).' '.(date('N',$time) < 6)."\n";
	if (date('N',$time) < 6) {
		// day we're looping is a weekday - insert timelogs		
			
		// insert morning timelogs
		switch (rand(1,3)) {
			case 3: 
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '8:30', 
					'end_time' => '9:00', 
					'category_id' => 1,
					'notes' => 'Email, tasking & project management'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '9:00', 
					'end_time' => '11:30', 
					'category_id' => 6,
					'notes' => 'Team Meeting'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$projects_ids = array(1,3,4);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '11:30', 
					'end_time' => '12:30', 
					'category_id' => 8,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Styling pages'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				break;
				
			case 2:
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '8:00', 
					'end_time' => '8:30', 
					'category_id' => 1,
					'notes' => 'Email and tasking'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$documentation_reasons = array(
					'Documenting new production deployment system',
					'Documenting infrastructure server map', 
					'Documenting new development guidelines'
				);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '8:30', 
					'end_time' => '12:30', 
					'category_id' => 2,
					'project_id' => 2,
					'notes' => $documentation_reasons[array_rand($documentation_reasons)]
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				break;
				
			default: 
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '7:45', 
					'end_time' => '8:15', 
					'category_id' => 1,
					'notes' => 'Checking email'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '8:15', 
					'end_time' => '8:30', 
					'category_id' => 9,
					'notes' => ''
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$projects_ids = array(1,3,4, 6);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '8:30', 
					'end_time' => '12:30', 
					'category_id' => 7,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Implementing new feature'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				break;
		}
		
		// insert afternoon timelogs
		switch (rand(1,3)) {
			case 3: 
				$projects_ids = array(1,3,4,5);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '13:30', 
					'end_time' => '14:15', 
					'category_id' => 5,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Stupid thing broke again. Fixing corner case issue. Implemented fix, documented and commit to repo.'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '14:15', 
					'end_time' => '15:30', 
					'category_id' => 6,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Project status & review meeting with crucial stake holders.'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '15:30', 
					'end_time' => '15:45', 
					'category_id' => 9
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$projects_ids = array(1,3,4, 6);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '15:45', 
					'end_time' => '17:00', 
					'category_id' => 7,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Implementing newest shiny feature'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				break;
				
			case 2:
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '13:45', 
					'end_time' => '14:00', 
					'category_id' => 1,
					'notes' => 'More email'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$documentation_reasons = array(
					'Documenting new production deployment system',
					'Documenting infrastructure server map', 
					'Documenting new development guidelines'
				);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '14:00', 
					'end_time' => '15:00', 
					'category_id' => 2,
					'project_id' => 2,
					'notes' => $documentation_reasons[array_rand($documentation_reasons)]
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '15:00', 
					'end_time' => '16:30', 
					'category_id' => 1,
					'notes' => 'Reviewing team reports'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				
				$projects_ids = array(1,3,4, 6);
				$tl = new Timelog(array(
					'date' => date('Y-m-d', $time),
					'start_time' => '16:30', 
					'end_time' => '17:15', 
					'category_id' => 7,
					'project_id' => $projects_ids[array_rand($projects_ids)],
					'notes' => 'Task for next milestone'
					, 'user_id' => 1
				));
				$tl->validate();
				$timelog_factory->insert($tl);
				break;
				
			default:
					$projects_ids = array(1,3,4,5);
					$tl = new Timelog(array(
						'date' => date('Y-m-d', $time),
						'start_time' => '13:30', 
						'end_time' => '15:45', 
						'category_id' => 4,
						'project_id' => $projects_ids[array_rand($projects_ids)],
						'notes' => 'R&D proof of concept for new feature request'
						, 'user_id' => 1
					));
					$tl->validate();
					$timelog_factory->insert($tl);

					$tl = new Timelog(array(
						'date' => date('Y-m-d', $time),
						'start_time' => '15:45', 
						'end_time' => '16:00', 
						'category_id' => 9
						, 'user_id' => 1
					));
					$tl->validate();
					$timelog_factory->insert($tl);
					
					$tl = new Timelog(array(
						'date' => date('Y-m-d', $time),
						'start_time' => '16:00', 
						'end_time' => '16:30', 
						'category_id' => 1,
						'notes' => 'Emailing'
						, 'user_id' => 1
					));
					$tl->validate();
					$timelog_factory->insert($tl);
					break;
				break;
		}
	}
}
die('Finished populating data.');