<?php
class Form_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  public function save($form)
  {

  	$ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

   // $this->load->library('firephp');

  	// Mantain the integrity of the DB.
  	// Put TRUE for a test mode (rollback every query, just as a debug mode.)
  	// $this->db->trans_start();
    $ocorrenciasDatabase->trans_start();


  	// Creating the SQL for the new "ocorrencia" entry on the Database

  	$fields = " (";
  	$values = " (";


    $fields = $fields . "informacao_geografica,";
    if (isset($form["semLocalizacao"])) {
      $values = $values . "'N',";
    } else {
      $values = $values . "'S',";

    	// if (isset($form["inputMunicipio"])) {
    	// 	$fields = $fields ."id_municipio,";
    	// 	$values = $values . "'" . $form["inputMunicipio"] . "',";
    	// }
    	// if (isset($form["inputUF"])) {
    	// 	$fields = $fields ."id_uf,";
    	// 	$values = $values . "'" . $form["inputUF"] . "',";
    	// }
    }


    if(!isset($form['semDataObs'])) {
    	if (isset($form["inputDataObs"])) {
    		$fields = $fields ."dt_primeira_obs,";
    		$values = $values . "'" . $form["inputDataObs"] . "',";
    	}
    	if (isset($form["inputHoraObs"])) {
    		$fields = $fields ."hr_primeira_obs,";
    		$values = $values . "'" . $form["inputHoraObs"] . "',";
    	}
      $fields = $fields ."periodo_primeira_obs,";
    	switch($form["PeriodoObs"]) {
    		case "obsMatutino":
  	  		$values = $values . "'M',";
    			break;
    		case "obsVespertino":
  	  		$values = $values . "'V',";
    			break;
    		case "obsNoturno":
  			$values = $values . "'N',";
    			break;
    		case "obsMadrugada":
  	  		$values = $values . "'S',";
    			break;
    	}
    }

    if(!isset($form['semDataInic'])) {
    	if (isset($form["inputDataInic"])) {
    		$fields = $fields ."dt_ocorrencia,";
    		$values = $values . "'" . $form["inputDataInic"] . "',";
    	}
    	if (isset($form["inputHoraInic"])) {
    		$fields = $fields ."hr_ocorrencia,";
    		$values = $values . "'" . $form["inputHoraInic"] . "',";
    	}
      $fields = $fields ."periodo_ocorrencia,";
    	switch($form["PeriodoInic"]) {
    		case "inicMatutino":
          $values = $values . "'M',";
    			break;
    		case "inicVespertino":
  	  		$values = $values . "'V',";
    			break;
    		case "inicNoturno":
  			$values = $values . "'N',";
    			break;
    		case "inicMadrugada":
  	  		$values = $values . "'S',";
    			break;
    	}
    }

    $fields = $fields ."plano_emergencia,";
  	if (isset($form["planoEmergencia"])) {
  		$values = $values . "'S',";
  	} else {
  		$values = $values . "'N',";
  	}

    $fields = $fields ."ocorrencia_oleo,";
    if (isset($form["hasOleo"])) {
      $values = $values . "'S',";
    } else {
      $values = $values . "'N',";
    }

    // Informação compelementar da origem do acidente
    if (isset($form["inputCompOrigem"])) {
      $fields = $fields . "des_complemento_tipo_localizaca,";
      $values = $values . "'" . $form["inputCompOrigem"] . "',";
    }

    // Informação compelementar da instituição atuando no Local
    if (isset($form["inputCompInstituicao"])) {
      $fields = $fields . "des_complemento_instituicao_atu,";
      $values = $values . "'" . $form["inputCompInstituicao"] . "',";
    }

    // Informação compelementar do tipo de evento
    if (isset($form["inputCompEvento"])) {
      $fields = $fields . "des_complemento_tipo_evento,";
      $values = $values . "'" . $form["inputCompEvento"] . "',";
    }

    // Informação compelementar dos danos idenfitifados
    if (isset($form["inputCompDano"])) {
      $fields = $fields . "des_complemento_tipo_dano_ident,";
      $values = $values . "'" . $form["inputCompDano"] . "',";
    }

    if (isset($form["inputDesDanos"])) {
      $fields = $fields . "des_danos,";
      $values = $values . "'" . $form["inputDesDanos"] . "',";
    }
    // $fields = $fields . "des_informacoes_adicionais,";
    // $values = $values . "1,";

    // Plano de Emergencia acionado ou nao
    $fields = $fields . "plano_emergencia_acionado,";
    if (isset($form["planoAcionado"])) {
      $values = $values . "'S',";
    } else {
      $values = $values . "'N',";
    }

    // Procedimentos de Atendimento Adotados
    $fields = $fields ."iniciados_outras_providencias,";
    if (isset($form["outrasMedidas"])) {
      $values = $values . "'S',";

      $fields = $fields . "des_outras_providencias,";
      $values = $values . "'" . $form["inputMedidasTomadas"] . "',";

    } else {
      $values = $values . "'N',";
    }

  	// HARDCODED INFORMATION
    // $fields = $fields . "des_ocorrencia,";
    // $values = $values . "'teste',";
  	// HARDCODED INFORMATION

    // Tipo do Produto

    if (isset($form["inputCausaProvavel"])) {
      $fields = $fields . "des_causa_provavel,";
      $values = $values . "'" . $form["inputCausaProvavel"] . "',";
    }

    $fields = $fields . "situacao_atual_descarga,";
    switch($form["SituacaoDescarga"]) {
      case 1:
        $values = $values . "'P',";
        break;
      case 2:
        $values = $values . "'N',";
        break;
      case 3:
        $values = $values . "'S',";
        break;
      case 4:
        $values = $values . "'A',";
        break;
    }

    // Identificacao do responsavel
    $fields = $fields . "informacao_responsavel,";
    if (isset($form["semResponsavel"])) {
      $values = $values . "'N',";
    } else {
      $values = $values . "'T',";

      $subfields = "insert into responsavel (nome, cpf_cnpj, des_licenca_ambiental) VALUES (";

      if(isset($form["inputResponsavel"])) {
        $subfields = $subfields . "'" . $form["inputResponsavel"] . "',";
      }

      if(isset($form["inputCPFCNPJ"])) {
        $subfields = $subfields . "'" . $form["inputCPFCNPJ"] . "',";
      }

      if(isset($form["slctLicenca"])) {
        $subfields = $subfields . "'" . $form["slctLicenca"] . "'";
      }

      $subfields = $subfields . ");";

      $this->firephp->log($subfields);

      $ocorrenciasDatabase->query($subfields);

      $fields = $fields . "id_usuario,";
      $values = $values . "'" . $ocorrenciasDatabase->insert_id() . "',";

    }

    // Identificação do comunicante
    if (isset($form["inputNomeInformante"])) {
      $fields = $fields . "nome_comunicante,";
      $values = $values . "'" . $form["inputNomeInformante"] . "',";
    }

    if (isset($form["inputTelInformante"])) {
      $fields = $fields . "telefone_contato,";
      $values = $values . "'" . $form["inputTelInformante"] . "',";
    }

    if (isset($form["inputEmailInformante"])) {
      $fields = $fields . "email_comunicante,";
      $values = $values . "'" . $form["inputEmailInformante"] . "',";
    }

    if(isset($form["comunicado"])) {
      $fields = $fields . "nro_ocorrencia,";
      $values = $values . "'" . $form["comunicado"] . "',";
    }

    if (isset($form["inputDesOcorrencia"])) {
      $fields = $fields . "des_ocorrencia,";
      $values = $values . "'" . $form["inputDesOcorrencia"] . "',";
    }

    if(isset($form["inputDesObs"])) {
      $fields = $fields . "des_obs,";
      $values = $values . "'" . $form["inputDesObs"] . "',";
    }
    // NON REQUIRED FIELDS

    // $fields = $fields . "acao_inicial_tomada,";
    // $values = $values . "1,";acionado plano individual de emergencia / sem evidencia de acao
    // $fields = $fields . "des_obs,";
    // $values = $values . "1,";
    // NON REQUIRED FIELDS



    $fields = $fields . "dt_registro";
    $values = $values . "now()";


  	$fields = $fields . ") ";
  	$values = $values . ") ";

  	$sqlOcorrencias =  "insert into ocorrencia" . $fields . " VALUES " . $values . ";";

    // Saves on the Database the new entry
    $ocorrenciasDatabase->query($sqlOcorrencias);



    // Creating the relations on the Form

    $id = $ocorrenciasDatabase->insert_id();


    // Relation R1
    $this->firephp->log("tipoLocalizacao");
    if(isset($form['tipoLocalizacao'])) {
      foreach($form['tipoLocalizacao'] as $tipoLocalizacao) {
        $sql = "insert into r1 (id_ocorrencia, id_tipo_localizacao) VALUES (" .
                $id . "," .  $tipoLocalizacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }



    // Relation R2
    $this->firephp->log("tipoEvento");
    if(isset($form['tipoEvento'])) {
      foreach($form['tipoEvento'] as $tipoEvento) {
        $sql = "insert into r2 (id_ocorrencia, id_tipo_evento ) VALUES (" .
                $id . "," .  $tipoEvento .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }



    // Relation R3
    $this->firephp->log("instituicaoAtuandoLocal");
    if(isset($form['instituicaoAtuandoLocal'])) {
      foreach($form['instituicaoAtuandoLocal'] as $instituicaoAtuandoLocal) {
        $sql = "insert into r3 (id_ocorrencia, id_instituicao_atuando_local ) VALUES (" .
                $id . "," .  $instituicaoAtuandoLocal .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }



    // Relation R4
    $this->firephp->log("tipoDanoIdentificado");
    if(isset($form['tipoDanoIdentificado'])) {
      foreach($form['tipoDanoIdentificado'] as $tipoDanoIdentificado) {
        $sql = "insert into r4 (id_ocorrencia, id_tipo_dano_identificado ) VALUES (" .
                $id . "," .  $tipoDanoIdentificado .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }



    // Relation R5
    $this->firephp->log("tipoFonteInformacao");
    if(isset($form['tipoFonteInformacao'])) {
      foreach($form['tipoFonteInformacao'] as $tipoFonteInformacao) {
        $sql = "insert into r5 (id_ocorrencia, id_tipo_fonte_informacao ) VALUES (" .
                $id . "," .  $tipoFonteInformacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }

    // Inserting informations about the shipment, related to the oil form
    if(isset($form['inputNomeNavio'])) {
      $sql = "insert into detalhamento_ocorrencia (id_ocorrencia, des_navio, des_instalacao, des_funcao_comunicante ) VALUES (" .
              $id . "," . $form['inputNomeNavio'] . $form['inputNomeInstalacao'] . $form['inputFuncaoNavio'];

      $this->firephp->log($sql);

    }

  	$ocorrenciasDatabase->trans_complete();

  }

  public function convertDBtoForm($dbResult) {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $this->load->helper('date');

    // Localizacao
    // $form['inputLat'] = $dbResult['id_lat'];
    // $form['inputLng'] = $dbResult['id_lng'];
    // $form['inputEPSG'] = $dbResult['id_epsg'];
    // $form['inputMunicipio'] = $dbResult['id_municipio'];
    // $form['inputUF'] = $dbResult['id_uf'];
    // $form['inputEndereco'] = $dbResult['id_endereco'];

    // Data e Hora do Acidente
    date_default_timezone_set('America/Sao_Paulo');
    $form['inputDataObs'] = date('d/m/Y', strtotime($dbResult['dt_primeira_obs']));
    $form['inputHoraObs'] = $dbResult['hr_primeira_obs'];
    switch ($dbResult['periodo_primeira_obs']) {
      case 'M':
        $form['PeriodoObs'] = 'obsMatutino';
        break;
      case 'V':
        $form['PeriodoObs'] = 'obsMatutino';
        break;
      case 'N':
        $form['PeriodoObs'] = 'obsMatutino';
        break;
      case 'S':
        $form['PeriodoObs'] = 'obsMatutino';
        break;
    }
    $form['inputDataInic'] = date('d/m/Y', strtotime($dbResult['dt_ocorrencia']));
    $form['inputHoraInic'] = $dbResult['hr_ocorrencia'];
    switch ($dbResult['periodo_ocorrencia']) {
      case 'M':
        $form['PeriodoInic'] = 'inicMatutino';
        break;
      case 'V':
        $form['PeriodoInic'] = 'inicVespertino';
        break;
      case 'N':
        $form['PeriodoInic'] = 'inicNoturno';
        break;
      case 'S':
        $form['PeriodoInic'] = 'inicMadrugada';
        break;
    }

    // Origem do Acidente
    $query = "select r1.id_tipo_localizacao from r1 where r1.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoLocalizacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoLocalizacao'], $row['id_tipo_localizacao']);
    }
    $form['inputCompOrigem'] = $dbResult['des_complemento_tipo_localizaca'];

    // Tipo de Evento
    $query = "select r2.id_tipo_evento from r2 where r2.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoEvento'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoEvento'], $row['id_tipo_evento']);
    }
    $form['inputCompEvento'] = $dbResult['des_complemento_tipo_evento'];

    // Detalhes do Acidente
    $form['inputCausaProvavel'] = $dbResult['des_causa_provavel'];
    $form['SituacaoDescarga'] = $dbResult['situacao_atual_descarga'];

    // Danos Identificados
    $query = "select r4.id_tipo_dano_identificado from r4 where r4.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoDanoIdentificado'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoDanoIdentificado'], $row['id_tipo_dano_identificado']);
    }
    $form['inputCompDano'] = $dbResult['des_complemento_tipo_dano_ident'];

    // Identificacao Empresa/Orgao Responsavel
    $form['inputResponsavel'] = $dbResult['nome'];
    $form['inputCPFCNPJ'] = $dbResult['cpf_cnpj'];
    $form['slctLicen'] = $dbResult['des_licenca_ambiental'];

    // Instituição/Empresa Atuando no Local
    $query = "select r3.id_instituicao_atuando_local from r3 where r3.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoInstituicaoAtuando'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoInstituicaoAtuando'], $row['id_instituicao_atuando_local']);
    }
    $form['inputCompInstituicao'] = $dbResult['des_complemento_instituicao_atu'];

    // Procedimentos de Atendimento Adotados
    if ($dbResult['plano_emergencia'] == "S") {
      $form['planoEmergencia'] = "1";
    } else {
      $form['planoEmergencia'] = "0";
    }
    if ($dbResult['plano_emergencia_acionado'] == "S") {
      $form['planoAcionado'] = "on";
    }
    if ($dbResult['iniciados_outras_providencias'] == "S") {
      $form['planoAcionado'] = "on";
      $form['inputMedidasTomadas'] = $dbResult['des_outras_providencias'];
    }

    // Informações sobre o Informante
    // $form['AAAAAAAAAAAQQQQQQQQQQQQQQQQQUEEEEEEEEEEEEEEEEEEEEEEEEEEE'] = 'AAAAAAAAAAAQQQQQQQQQQQQQQQQQUEEEEEEEEEEEEEEEEEEEEEEEEEEE';

    // Informações gerais sobre a Ocorrência


    // Fonte de Informação
    $query = "select r5.id_tipo_fonte_informacao from r5 where r5.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoFonteInformacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoFonteInformacao'], $row['id_tipo_fonte_informacao']);
    }

    return $form;
  }

  public function load($nro_ocorrencia) {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $query = "select * from ocorrencia join responsavel as res on (res.id_responsavel = ocorrencia.id_usuario) where nro_ocorrencia='" . $nro_ocorrencia . "';";

    $res = $ocorrenciasDatabase->query($query);

    // return $res->row_array();
    return $this->convertDBtoForm($res->row_array());
  }
}
