<?php
require_once('libraries/Current_Timelog_Form_Controller.php');

class Timelogs_Controller extends Current_Timelog_Form_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Display list of timelogs
	 */
	public function index() {
		$data = array(); // data for view 
		
		$data['startDate'] = $start_date = date('Y-01-01');
		$data['endDate'] = $end_date = date('Y-m-d');
		if ($data['timelogs'] = $this->_timelog_factory->getDayTotalsByDateRange($start_date, $end_date)) {
			$data['first_expaned_row_html'] = $this->day(array_shift($data['timelogs'])->getDate(), true);
		}
		$this->display('timelog/list', $data);
	}

	/** 
	 *	Display form for a timelog
	 *	@var $timelog (timelog class) - timelog to put into the form
	 */
	private function displayForm(timelog $timelog) {
		$data = array(
			'timelog' => $timelog,
			'projects' => $this->_user->getVisibleProjects(new Project_Factory($this->_admin_db)), 
			'categories' => $this->_user->getVisibleTimelogCategories(new Timelog_Categories_Factory($this->_admin_db))
		);
		$this->display('timelog/form', $data);
	}
	
	/** 
	 *	Display a form for a new timelog
	 */
	public function add() {
		$this->displayForm(new Timelog());
	}
	
	/** display a form to edit a timelog
	 * 
	 *	@var $id (int) - id for the timelog to edit
	 */
	public function edit($id) {
		// get timelog to edit
		$timelog = $this->_timelog_factory->getById($id);

		$this->displayForm($timelog);
	}
	
	/** 
	 *	Attempt to save Timelog from a post request
	 *	Could be a normal timelog form submission, or an ajax submission from sidebar form
	 */
	public function save() {
		try {
			// check if cancel button pressed
			if ($this->input->post('submit') == 'Cancel') {
				// cancel saving timelog in form, just display form for a new timelog
				$timelog = new Timelog();
			}
			else {
				// save the timelog in the form as per normal
				
				// get post obj
				$obj = $this->input->post('timelog');
				// update user_id 
				$obj['user_id'] = $this->_user->getId();
				$timelog = new Timelog($obj);
			
				// check if start now button has been pushed
				if ($this->input->post('submit') == 'Start') {
					$timelog->setDate(date('Y-m-d'));
					$timelog->setStartTime(date('H:i'));
				}
			
				if ($timelog->getId()) {
					// update timelog
					if ($this->input->post('submit') == 'Stop') {
						// set end time to now
						$timelog->setEndTime(Date('H:i'));
					}
				
					$timelog->validate();
				
					$this->_timelog_factory->update($timelog);
				}
				else {
					// insert new timelog
					$timelog->validate();
					$id = $this->_timelog_factory->insert($timelog);
					$timelog->setId($id);				
				}
			} // end check if cancel button pressed
			
			// check for ajax request
			if ($this->_isAjax) { 
				// update session with timelog
				 $_SESSION['current_timelog'] = $timelog;
				
				// check how form was submitted and if we need to reload the form
				switch ($this->input->post('submit')) {
					case 'Cancel':
					case 'Close': 
						// user pushed the cancel button, display form with new timelog
						$timelog = new Timelog(); 
					case 'Start': 
					case 'Stop': 
						$this->displayForm($timelog);
						break;
						
					default: 
						// output 'success' for successful ajax submission
						die('success');
						break;
				}
			}
			else {
				// not ajax request 
				
				// check for cancel button pressed
				if ($this->input->post('cancel')) {
					redirect('/timelogs/', 'refresh');
				}

				// default redirect back to timelogs list	
				redirect('/timelogs/edit/'.$timelog->getId(), 'refresh');
			}
		}
		catch (Exception $e) {
			// reassign timelog for form if it's invalid
			if(get_class($timelog) != 'Timelog')	$timelog = new Timelog();
			
			// save failed - redisplay form
			$this->display('timelog/form', array(
					'timelog' => $timelog,
					'error' => 'Error saving timelog: '.$e->getMessage()
			));
		}
	}

	/** 
	 *	Get all timelogs for day and output 
	 *	Should be called via ajax
	 */
	public function day($date, $return_html=false) {
		$data = array(
				'timelogs' => $this->_timelog_factory->getForDate($date),
				'totals' => array_pop($this->_timelog_factory->getDayTotalsByDateRange($date, $date))
		);
		
		// return html
		if ($return_html) return $this->display('timelog/partial/day', $data, array('return_html'=>true));

		// otherwise display
		$this->display('timelog/partial/day', $data);
	}
	
	/**
	 *	Get notes for a timelog and echo them
	 *	Should be called via Ajax
	 */
	public function getNotes($id) {
		// get timelog to view note
		$timelog = $this->_timelog_factory->getById($id);
		die($timelog->getNotes());
	}
	
	/**
	 * 	Change current timelog in session and return html for sidebar form for that timelog
	 */
	public function changeSidebarFormTimelog($timelog_id) {
		try {
			// get timelog 
			if (!$timelog = $this->_timelog_factory->getById($timelog_id)) 
				throw new Exception('Timelog not found for id: '.$timelog_id);

			// update timelog in session
			$_SESSION['current_timelog'] = $timelog;
			// display timelog form html
			$this->display('blank', array('body'=>$this->getTimelogSidebarFormHtml($timelog)));
		} 
		catch (Exception $e) {
			show_404();
		}
	}
}