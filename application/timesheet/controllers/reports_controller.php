<?php
class Reports_Controller extends Current_Timelog_Form_Controller {	
	public function __construct() {
		parent::__construct();
		$this->_timelog_report = new Timelog_Report($this->_timelog_factory);
	}
	
	public function index() {
		$this->clarity();
	}
	
	public function clarity($year=null, $week=null) {
		// default year to this year
		if (!$year) $year = Date('Y');
		// default week to this week 
		if (!$week) $week = Date('W');
		
		// setup previous week link
		if ($week <= 2) {
			// rollback to final week of previous year
			$prevWeek = 53;
			$prevYear = $year-1;
		}
		else {
			// rollback to previous week
			$prevYear = $year;
			$prevWeek = $week-1;
		}
		$prevLink = '<a href="'.site_url('reports/clarity/'.$prevYear.'/'.$prevWeek).'"><img class="bw-toggle prev-next" src="images/icons/resultset_previous-bw.png" alt="<" title="Previous Week"/></a> ';
		
		// setup next week link
		if ($week >= 53) {
			// roll forward to first week of next year
			$nextYear = $year +1;
			$nextWeek = 1;
		}
		else {
			// roll forward to next week
			$nextYear = $year;
			$nextWeek = $week +1;
		}
		$nextLink = ' <a href="'.site_url('reports/clarity/'.$nextYear.'/'.$nextWeek).'"><img class="bw-toggle prev-next" src="images/icons/resultset_next-bw.png" alt=">" title="Next Week"/></a> ';
		
		$this->_heading = $prevLink.$year.' clarity Report for week '.$week.$nextLink;
		$this->_body = $this->_timelog_report->getClarityReportForWeekHtml($week, $year);
		$this->display2();
	}
	
	/**
	 * Display report of hours spent on timelogs for a given week
	 */
	public function category($year=null, $week=null) {
		// default year to this year
		if (!$year) $year = Date('Y');
		// default week to this week 
		if (!$week) $week = Date('W');
		
		// setup previous week link
		if ($week <= 2) {
			// rollback to final week of previous year
			$prevWeek = 53;
			$prevYear = $year-1;
		}
		else {
			// rollback to previous week
			$prevYear = $year;
			$prevWeek = $week-1;
		}
		$prevLink = '<a href="'.site_url('reports/category/'.$prevYear.'/'.$prevWeek).'"><img class="bw-toggle prev-next" src="images/icons/resultset_previous-bw.png" alt="<" title="Previous Week"/></a> ';
		
		// setup next week link
		if ($week >= 53) {
			// roll forward to first week of next year
			$nextYear = $year +1;
			$nextWeek = 1;
		}
		else {
			// roll forward to next week
			$nextYear = $year;
			$nextWeek = $week +1;
		}
		$nextLink = ' <a href="'.site_url('reports/category/'.$nextYear.'/'.$nextWeek).'"><img class="bw-toggle prev-next" src="images/icons/resultset_next-bw.png" alt=">" title="Next Week"/></a> ';
		
		$this->_heading = $prevLink.$year.' Category Report for week '.$week.$nextLink;
		$this->_body = $this->_timelog_report->getCategoryReportForWeekHtml($week, $year);
		$this->display2();
	}

	/**
	 * Display report of sum of overtime performed for a period of time
	 */
	public function overtime() {
		$start_date = $this->input->get('start_date');
		if (!$start_date) $start_date = date('Y-m-01');
		$end_date = $this->input->get('end_date');
		if (!$end_date) $end_date = date('Y-m-d', strtotime('yesterday'));
		$user_id = $this->_user->getId();
		$this->_heading = 'Overtime report';
		$this->_body = $this->_timelog_report->getOvertimeReportForDateRangeHtml($start_date, $end_date, $user_id);
		$this->display2();
	}
}