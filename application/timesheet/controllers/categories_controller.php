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
		$this->_heading = 'Categories <a class="button" href="categories/add"><img src="images/icons/add.png" title="Add New Category"/> Add new</a>';
		$this->_body = $this->_timesheet->getCategoryTableHtml();
		
		$this->display2();
	}
}