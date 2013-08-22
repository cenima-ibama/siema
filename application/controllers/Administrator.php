<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        if($this->authldap->is_authenticated()) {
            $data['name'] = $this->session->userdata('cn');
            $data['username'] = $this->session->userdata('username');
            $data['logged_in'] = TRUE;
        } else {
            $data['logged_in'] = FALSE;
        }
        
        if (! file_exists('application/views/templates/administrator.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        // Enable firebug
        // $this->load->library('firephp');
        // $this->firephp->setEnabled(TRUE);
    }

    public function index()
    {

        $data['page'] = "administrator";

        $this->load->view('templates/administrator', $data);

    }

    public function config()
    {

        $data['page'] = "configuracoes";

        $this->load->view('templates/administrator', $data);

    }

    public function valid()
    {

        $data['page'] = "validacao";

        $this->load->view('templates/administrator', $data);

    }
}
