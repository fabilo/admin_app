<?php
class Users_Controller extends Base_CI_Admin_Controller {

	public function __construct() {
		parent::__construct();
		
		// setup Auth lib
		$this->_authLib = new Auth($this->_user_factory);
		
		// set html layout template
		$this->_layout_view = 'layout_simple';
	}

	public function index() {
		redirect('users/login');
	}

	public function login() {
		// username to display in form by default - we'll override this in an unsuccessful login attempt
		$defaultUsername = '';
		// feedback message to display with form - we'll override this in an unsuccessful login attempt
		$message = '';
		
		if ($this->input->post('submit')) {
			// login form submitted - authenticate login attempt
			$result = $this->_authLib->authenticateLogin($this->input->post('username'), $this->input->post('password'));
			if (is_object($result) && get_class($result) == 'User') {
				// user authtentication successful! - Save in session
				$this->_user = $result;
				$this->session->set_userdata('user_id', $this->_user->getId());
				// redirect to home
				redirect('/');			
			}
			else {
				$message = 'Login failed. Please try again';
				$defaultUsername = $this->input->post('username');
			}
		}
		
		/* User either hasn't tried to login yet, or login attempt failed */
		// display login form
		$this->_heading = 'User Login';
		$this->_body = $this->_authLib->getLoginFormHtml($defaultUsername, $message);
		
		$this->display2();
	}
	
	
	public function logout() { 
		// remove user_id from session
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('skipIntro');
		redirect('/');
	}
}