<?php
require_once('libraries/Current_Timelog_Form_Controller.php');

class Projects_Controller extends Current_Timelog_Form_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->_project_factory = new Project_Factory($this->_admin_db);
		$this->_department_factory = new Department_Factory($this->_admin_db);
		$this->_team_factory = new Team_Factory($this->_admin_db);
	}
	
	public function index() {
		$data = array(
			'heading' => 'Projects',
			'projects' => $this->_user->getVisibleProjects($this->_project_factory, 1)
		);
		$this->display('projects/list', $data);
	}
	
	public function add() {
		$data = array(
			'project' => new Project(), 
			'heading' => 'New Project',
			'departments' => $this->_user->getVisibleDepartments($this->_department_factory),
			'teams' => $this->_user->getVisibleTeams($this->_team_factory)
		);
		$this->display('projects/form', $data);
	}
	
	public function edit($id) {
		// get project to edit
		$project = $this->_project_factory->getById($id);
		if (!$project) throw new Exception('Project doesn\'t exist');

		$this->display('projects/form', array(
				'heading' => 'Edit Project',
				'project' => $project,
				'departments' => $this->_user->getVisibleProjects($this->_project_factory), 
				'teams' => $this->_user->getVisibleTeams($this->_team_factory)
		));
	}
	
	public function save() {
		try {
			// get post obj
			$obj = new Project($this->input->post('project'));
			$obj->validate();			
			
			if ($obj->getId()) {
				
				$this->_project_factory->update($obj);
			}
			else {
				// insert new obj
				$id = $this->_project_factory->insert($obj);
				$obj->setId($id);				
			}
			
			// check for submit action
			if ($this->input->post('save_and_new')) {
				redirect('/'.$this->_uri_segment.'/add/', 'refresh');
			}
			elseif ($this->input->post('save_and_done')) {
				redirect('/'.$this->_uri_segment.'/', 'refresh');
			}
			
			// default redirect back to edit form	
			redirect('/'.$this->_uri_segment.'/edit/'.$obj->getId(), 'refresh');
		}
		catch (Exception $e) {
			// reassign timelog for form if it's invalid
			if(get_class($obj) != 'Project') $obj = new Project();
			
			// save failed - redisplay form
			$this->display('/'.$this->_uri_segment.'/form', array(
					'project' => $obj,
					'departments' => $this->_user->getVisibleProjects($this->_project_factory),
					'teams' => $this->_user->getVisibleTeams($this->_team_factory),
					'error' => 'Error saving project: '.$e->getMessage()
			));
		}
	}
}