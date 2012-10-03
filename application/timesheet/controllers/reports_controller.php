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
			$prevWeek = 52;
			$prevYear = $week -1;
		}
		else {
			// rollback to previous week
			$prevYear = $year;
			$prevWeek = $week-1;
		}
		$prevLink = '<a href="'.site_url('reports/clarity/'.$prevYear.'/'.$prevWeek).'"><img class="bw-toggle prev-next" src="images/icons/resultset_previous-bw.png" alt="<" title="Previous Week"/></a> ';
		
		// setup next week link
		if ($week >= 52) {
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
}