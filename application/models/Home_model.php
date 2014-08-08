<?php
class Home_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  public function getProfileUser($userName)
  {
  	//
    // Set the default database to be used
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    //
    //Set query.
    $query = "select id_perfil from usuarios where cpf = ?";

    //Query for verify if user is type administrator.
    $res = $ocorrenciasDatabase->query($query, array($userName));
    
    //Return id of perfil of user or "0" case not user returned.
    if ($res->num_rows() > 0)
	   	return $res->row()->id_perfil;
	  else
		  return "0";    
  }
}
