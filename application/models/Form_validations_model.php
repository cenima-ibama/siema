<?php
class Form_validations_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }	

  //Na atualização: verificar se a ocorrencia existe.
  public function numRegistroExists($numRegistro)
  {
    //Load database and conecte.
    $ocorrenciasDatabase =  $this->load->database("emergencias", TRUE);

    $query = "select nro_ocorrencia FROM ocorrencia where nro_ocorrencia = ?";
    
    $results = $ocorrenciasDatabase->query($query,array($numRegistro));

    if ($results->num_rows() > 0)
      return TRUE;
    else
      return FALSE; 

  }

  //Não atualizar ocorrência marcada como 'Dados Legados'.
  public function dadosLegados($numRegistro)
  {
    //Load database and conecte.
    $ocorrenciasDatabase =  $this->load->database("emergencias", TRUE);

    $query = "select nro_ocorrencia FROM ocorrencia where nro_ocorrencia = ? and legado = TRUE";
    
    $results = $ocorrenciasDatabase->query($query,array($numRegistro));

    if ($results->num_rows() > 0)
      return TRUE;
    else
      return FALSE; 

  }

  //Verificar se o usuário que cadastrou a ocorrência.
  public function userCadastrouOcorrencia($numRegistro, $userName)
  {
    //Load database and conecte.
    $ocorrenciasDatabase =  $this->load->database("emergencias", TRUE);

    $query = "select nro_ocorrencia FROM ocorrencia where nro_ocorrencia = ? and cpf_contato = ?";
    
    $results = $ocorrenciasDatabase->query($query,array($numRegistro,$userName));

    if ($results->num_rows() > 0)
      return TRUE;
    else
      return FALSE; 

  }

}

?>