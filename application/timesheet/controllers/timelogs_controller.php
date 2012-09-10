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
		// flash data not working at the moment.
		$data['message'] = $this->session->flashdata('message');
		$this->display('timelog/list', $data);
	}
	
	/** 
	 *	Display a form for a new timelog
	 */
	public function add() {
		$this->displayTimelogForm(new Timelog());
	}
	
	/** display a form to edit a timelog
	 * 
	 *	@var $id (int) - id for the timelog to edit
	 */
	public function edit($id) {
		try {
			// get timelog to edit
			$timelog = $this->_timelog_factory->getById($id);

			$this->displayTimelogForm($timelog);
		}
		catch (Exception $e) {
			$this->session->set_flashdata('message', 'Couldn\'t find timelog with id: '.$id);
			redirect('/timelogs/');
		}
	}
	
	/** 
	 *	Attempt to save Timelog from a post request
	 *	Could be a normal timelog form submission, or an ajax submission from sidebar form
	 */
	public function save() {
		try {				
			// chec if Delete button was pressed
			if ($this->input->post('submit') == 'Delete') {
				$obj = $this->input->post('timelog');
				// attempt to delete timelog
				return $this->delete($obj['id']);
			}
			
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
				
				// timelog exists - update it
				if ($timelog->getId()) {
					
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
				if ($this->input->post('sidebar_form')) {
						// update session with timelog
						$_SESSION['current_timelog'] = $timelog;
				}
				
				// check how form was submitted and if we need to reload the form
				switch ($this->input->post('submit')) {
					case 'Cancel': 
						// user pushed the cancel button
						// form will be displayed with new timelog set at start of function
					case 'Start': 
					case 'Stop': 
						$this->displayTimelogForm($timelog, $sidebar_form = $this->input->post('sidebar_form'));
						break;
						
					default: 
						// output 'success' for successful ajax submission
						die('success');
						break;
				}
			}
			else {
				// not ajax request 
				
				// check for anything button other than the cancel button pressed
				if (!$this->input->post('submit') == 'Cancel') {
					$this->session->set_flashdata('message', 'Timelog saved.');
				}
				// redirect back to timelogs page
				redirect('/timelogs/', 'refresh');
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
	 *  Delete timelog 
	 */
	public function delete($id) {
		try {			
			// try delete timelog
			$this->_timelog_factory->delete($id);
		
			// setup confirmation flash message
			$this->session->set_flashdata('message', 'Successfully deleted timelog.');
			
			// redirect user
			redirect('/timelogs/', 'refresh');
		}
		catch (Exception $e) {
			$this->session->set_flashdata('message', 'There was an error deleting timelog: '.$e->getMessage());
			redirect('/timelogs/edit/'.$id, 'refresh');
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
			$this->display('blank', array('body'=>$this->displayTimelogForm($timelog, $sidebar_form=true, $return_html=true)));
		} 
		catch (Exception $e) {
			show_404();
		}
	}
}