<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

  function __construct() {
    parent::__construct();
  }

  public function index($page = 'home')
  {
    if ( ! file_exists('application/views/pages/'.$page.'.php'))
    {
      // Whoops, we don't have a page for that!
      show_404();
    }

    $data['page_content'] = 'pages/home';

    //$this->load->library('firephp');
    //$this->firephp->setEnabled(TRUE);
    //$this->firephp->log($data);

    $this->load->view('templates/base_template', $data);
  }
}
