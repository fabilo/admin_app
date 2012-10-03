<?php
class Categories_Controller extends Current_Timelog_Form_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->_timelog_categories_factory = new Timelog_Categories_Factory($this->_admin_db);
		$this->_department_factory = new Department_Factory($this->_admin_db);
		$this->_team_factory = new Team_Factory($this->_admin_db);
	}
	
	public function index() {
		$heading = 'Categories';
		// add 'Add New' button
		$this->_heading = 'Categories <a class="button" href="'.site_url('categories/add').'"><img src="images/icons/add.png" title="Add New Category"/> Add new</a>';
		$this->_body = $this->_timesheet->getCategoryTableHtml();
		
		$this->display2();
	}

	public function add() {
		$this->_heading = 'New Category';
		$this->_body = $this->_timesheet->getCategoryFormHtml();
		$this->display2();
	}

	public function edit($id) {
		$this->_heading = 'Edit Category';
		// get project to edit
		$category = $this->_timelog_categories_factory->getById($id);
		if (!$category) throw new Exception('Category doesn\'t exist');
		$this->_body = $this->_timesheet->getCategoryFormHtml($category);
		$this->display2();
	}
	
	public function save() {
		try {
			// get post obj
			$obj = new Timelog_Category($this->input->post('category'));
			$obj->validate();			
			
			if ($obj->getId()) {
				$this->_timelog_categories_factory->update($obj);
			}
			else {
				// insert new obj
				$id = $this->_timelog_categories_factory->insert($obj);
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
			if(get_class($obj) != 'Timelog_Category') $obj = new Timelog_Category();
			
			die($e->getMessage());
		}
	}
}