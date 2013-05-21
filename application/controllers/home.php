<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();

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

        $this->load->view('includes/header');
        $this->load->view('includes/topbar');
        $this->load->view('pages/home');
        $this->load->view('includes/scripts');
        $this->load->view('includes/footer');

    }

}
