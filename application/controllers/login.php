<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper('form');
        $this->load->library('Form_validation');
        $this->load->library('AuthLDAP');
        $this->load->helper('url');
        $this->load->library('table');

        // Enable firebug
        $this->load->library('firephp');
        $this->firephp->setEnabled(TRUE);
    }

    public function index()
    {
        if (! file_exists('application/views/pages/home.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }

        $this->load->view('includes/header_login');
        $this->load->view('pages/login');
        $this->load->view('includes/footer');

    }

}
