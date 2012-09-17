<?php
require_once('libraries/Current_Timelog_Form_Controller.php');

class Timelogs_Controller extends Current_Timelog_Form_Controller {
	protected $_timesheet; 
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Display list of timelogs
	 */
	public function index() {
		$this->week();
	}
	
	/** 
	 *	Display a form for a new timelog
	 */
	public function add() {
		$this->_body = $this->_timesheet->getTimelogFormHtml(new Timelog());
		$this->display2();
	}
	
	/** 
	 *	display a form to edit a timelog
	 *	@param int $id - id for the timelog to edit
	 */
	public function edit($id) {
		try {
			// get timelog to edit
			$timelog = $this->_timelog_factory->getById($id);
			$this->_heading = 'Edit timelog';
			$this->_body = $this->_timesheet->getTimelogFormHtml($timelog);
			$this->display2();
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
						die($this->_timesheet->getTimelogFormHtml($timelog, array('sidebar_form'=>true)));
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
			$this->flash_message('Failed saving timelog: '.$e->getMessage());

			// reassign timelog for form if it's invalid
			if(isset($timelog) && (get_class($timelog) == 'Timelog')) {
				redirect('/timelogs/edit/'.$timelog->getId(), 'refresh');
			}
			else {
				redirect('/timelogs/add', 'refresh');
			}
			
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
	public function day($date) {
		$html = $this->_timesheet->getTotalHoursTableRowHtmlForDate($date, true);
		die($html);
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
			if ($timelog_id == 'start-new') {
				// create new timelog and default times to now
				$timelog = new Timelog(array(
					'date' => date('Y-m-d'),
					'start_time' => date('H:i'),
					'user_id' => $this->_user->getId()
				));
				// insert new timelog into db
				$timelog->validate();
				$id = $this->_timelog_factory->insert($timelog);
				$timelog->setId($id);
			}
			else {
				// get timelog 
				if (!$timelog = $this->_timelog_factory->getById($timelog_id)) 
					throw new Exception('Timelog not found for id: '.$timelog_id);
			}

			// update timelog in session
			$_SESSION['current_timelog'] = $timelog;
			
			// display timelog form html
			die($this->_timesheet->getTimelogFormHtml($timelog, array('sidebar_form'=>true)));
		} 
		catch (Exception $e) {
			die($e->getMessage());
		}
	}
	
	/**
	 * Display timelogs for a week
	 */
	public function week($year=null, $week=null) {		
		// flag to display timelog table rows for first row
		$expand_first_rows_timelogs = false;
		// year not set - default to this year
		if (!$year) $year = Date('Y');
		// week not set - default to this week
		if (!$week) $week = Date('W');

		// expand first row's timelogs if this week
		if ($week == Date('W')) $expand_first_rows_timelogs = true;
		
		// setup prev nav button html
		$prev_year = ($week <= 1) ? $year-1 : $year;
		$prev_week = ($week <= 1) ? 52 : $week-1;
		$prev_button = '<a href="'.site_url('/timelogs/week/'.$prev_year.'/'.$prev_week).'"><img class="bw-toggle prev-next" src="images/icons/resultset_previous-bw.png" title="View Previous Week"/></a>';
		
		// setup next nav button html
		$next_year = ($week >= 52) ? $year+1 : $year;
		$next_week = ($week >= 52) ? 1 : $week+1;
		$next_button = '<a href="'.site_url('/timelogs/week/'.$next_year.'/'.$next_week).'"><img class="bw-toggle prev-next"  src="images/icons/resultset_next-bw.png" title="View Next Week"/></a>';
		
		// setup start new button html
		$start_new_button = '<a id="StartNewButton" class="showInSidebar button" href="'.site_url('/timelogs/changeSidebarFormTimelog/start-new').'"><img src="images/icons/add.png" title="Start New Timelog Now"/> Start New</a>';
		
		// defining heading bar
		$this->_heading = $prev_button.' '.$year.' timelogs for week '.$week.' '.$next_button.' '.$start_new_button;
		
		$this->_body = $this->_timesheet->getTotalHoursTableRowHtmlForWeek($week, $year, $expand_first_rows_timelogs);
		$this->_javascript_includes[]= 'timelog_list';
		$this->display2();
	}
	
	/**
	 * get hours for a week 
	 *	@param string $yearWeek - the year and week separated by a dash -
	 *	@return float total of hours for the week
	 */
	public function weekHours($yearWeek) {
		list($year, $week) = explode('-', $yearWeek);		
		$hours = $this->_timelog_factory->getTotalHoursForWeek($year, $week);
			die($hours);
	}
}