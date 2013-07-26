<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        // Enable firebug
        // $this->load->library('firephp');
        // $this->firephp->setEnabled(TRUE);
    }

    public function index()
    {
        if (! file_exists('application/views/templates/home.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }

        if($this->authldap->is_authenticated()) {
            $data['name'] = $this->session->userdata('cn');
            $data['username'] = $this->session->userdata('username');
            $data['logged_in'] = TRUE;
        } else {
            $data['logged_in'] = FALSE;
        }

        $this->load->view('templates/home', $data);

    }
}
