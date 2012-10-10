<?php
class Projects_Controller extends Current_Timelog_Form_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->_project_factory = new Project_Factory($this->_admin_db);
		$this->_department_factory = new Department_Factory($this->_admin_db);
		$this->_team_factory = new Team_Factory($this->_admin_db);
	}
	
	public function index() {
		$this->_heading = 'Projects';
		// add 'Add New' button
		$this->_heading .= ' <a class="button" href="'.site_url('projects/add').'"><img src="images/icons/add.png" title="Add New Project"/> Add new</a>';
		$this->_body = $this->_timesheet->getProjectTableHtml();
		$this->display2();
	}
	
	public function add() {
		$this->_heading = 'New Project';
		$this->_body = $this->_timesheet->getProjectFormHtml();
		$this->display2();
	}
	
	public function edit($id) {
		$this->_heading = 'Edit Project';
		// get project to edit
		$project = $this->_project_factory->getById($id);
		if (!$project) throw new Exception('Project doesn\'t exist');
		$this->_body = $this->_timesheet->getProjectFormHtml($project);
		$this->display2();
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
			
			$this->_heading = 'Save Project';
			$this->_message = $e->getMessage();
			$this->_body = $this->_timesheet->getProjectFormHtml($obj);
			$this->display2();
		}
	}
}