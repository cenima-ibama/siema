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

    // $this->firephp->log($form);


    $fields = $fields . "informacao_geografica,";
    if (isset($form["semLocalizacao"])) {
      $values = $values . "'N',";
    } else {
      $values = $values . "'S',";
    }


    if(!isset($form['semDataObs'])) {
    	if (isset($form["inputDataObs"])) {
    		$fields = $fields ."dt_primeira_obs,";
    		$values = $values . "'" . $form["inputDataObs"] . "',";
    	}
    	if (!empty($form["inputHoraObs"])) {
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

    if(!isset($form['semDataInci'])) {
    	if (isset($form["inputDataInci"])) {
    		$fields = $fields ."dt_ocorrencia,";
    		$values = $values . "'" . $form["inputDataInci"] . "',";
    	}
    	if (!empty($form["inputHoraInci"])) {
    		$fields = $fields ."hr_ocorrencia,";
    		$values = $values . "'" . $form["inputHoraInci"] . "',";
    	}
      $fields = $fields ."periodo_ocorrencia,";
    	switch($form["PeriodoInci"]) {
    		case "inciMatutino":
          $values = $values . "'M',";
    			break;
    		case "inciVespertino":
  	  		$values = $values . "'V',";
    			break;
    		case "inciNoturno":
  			$values = $values . "'N',";
    			break;
    		case "inciMadrugada":
  	  		$values = $values . "'S',";
    			break;
    	}
    }

    $fields = $fields ."plano_emergencia,";
  	if ($form["planoEmergencia"] == '1') {
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
    $this->firephp->log($form["SituacaoDescarga"]);
    switch($form["SituacaoDescarga"]) {
      case '1':
        $this->firephp->log("P");
        $values = $values . "'P',";
        break;
      case '2':
        $this->firephp->log("N");
        $values = $values . "'N',";
        break;
      case '3':
        $this->firephp->log("S");
        $values = $values . "'S',";
        break;
      case '4':
        $this->firephp->log("A");
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

    $fields = $fields . "dt_registro";
    $values = $values . "now()";


  	$fields = $fields . ") ";
  	$values = $values . ") ";

  	$sqlOcorrencias =  "insert into ocorrencia" . $fields . " VALUES " . $values . ";";

    // Saves on the Database the new entry
    $ocorrenciasDatabase->query($sqlOcorrencias);

    $this->firephp->log($sqlOcorrencias);

    // Creating the relations on the Form

    $id = $ocorrenciasDatabase->insert_id();

    // Creating the Geographic point of the form
    if (isset($form["inputLat"]) and isset($form["inputLng"])) {
      $subfields = "insert into ocorrencia_pon (id_ocorrencia, shape) values (";
      $subfields = $subfields . "" . $id . "," . "ST_SetSRID(ST_MakePoint(" . $form["inputLng"] . "," . $form["inputLat"] . "), ";
      $epsg = isset($form["inputEPSG"]) ? $form["inputEPSG"] : "4674";
      $subfields = $subfields . $epsg . "));";

      $ocorrenciasDatabase->query($subfields);

      $this->firephp->log($subfields);
    }

    //Saving vectors on database, linking it to the "ocorrencia"
    $fields = "select * from tmp_lin;";
    $line = $ocorrenciasDatabase->query($fields);

    $fields = "select * from tmp_pol;";
    $polygon = $ocorrenciasDatabase->query($fields);

    $this->firephp->log($polygon);
    $this->firephp->log($polygon->num_rows());

    if($line->num_rows() > 0 || $polygon->num_rows() > 0){

      $sql = " update tmp_lin set id_ocorrencia='" . $id . "';";
      $sql = $sql . "update tmp_pol set id_ocorrencia='" . $id . "';";

      $fields = " insert into ocorrencia_lin " .
                  " (id_ocorrencia_lin, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_lin_id_ocorrencia_lin_seq'), " .
                          "id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_lin; ";
      $fields = $fields . " insert into ocorrencia_pol " .
                  " (id_ocorrencia_pol, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_pol_id_ocorrencia_pol_seq'), " .
                          "id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_pol; ";


      $fields = $fields . " delete from tmp_lin; delete from tmp_pol;";

      $this->firephp->log($sql);
      $this->firephp->log($fields);

      $ocorrenciasDatabase->query($sql);
      $teste = $ocorrenciasDatabase->query("select * from tmp_pol;");
      $this->firephp->log($teste->row_array());
      $ocorrenciasDatabase->query($fields);
    }

    // Relation R1
    // $this->firephp->log("tipoLocalizacao");
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
    // $this->firephp->log("tipoEvento");
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
    // $this->firephp->log("instituicaoAtuandoLocal");
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
    // $this->firephp->log("tipoDanoIdentificado");
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
    // $this->firephp->log("tipoFonteInformacao");
    if(isset($form['tipoFonteInformacao'])) {
      foreach($form['tipoFonteInformacao'] as $tipoFonteInformacao) {
        $sql = "insert into r5 (id_ocorrencia, id_tipo_fonte_informacao ) VALUES (" .
                $id . "," .  $tipoFonteInformacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }

    // SAVING PRODUTS! NEEDS TO BE TESTED!!

    // Verifies if there is any fields on the tmp to be
    // saved (in case it's a create form)
    $sql = "select * from tmp_ocorrencia_produto;";

    $res = $ocorrenciasDatabase->query($sql);

    if($res->num_rows() > 0){

      // Retrieve rows from tmp_ocorrencia_produto
      $sql = "select * from tmp_ocorrencia_produto;";

      $res = $ocorrenciasDatabase->query($sql);

      $this->firephp->log($res->result_array());

      // Copy rows from tmp_ocorrencia_produto to ocorrencia_produto

      $sql = "";

      foreach ($res->result_array() as $key => $row) {

        $this->firephp->log($row);

        $sql = $sql .
               " insert into ocorrencia_produto " .
               " (id_ocorrencia,id_produto,quantidade,unidade_medida) values " .
               " ('" . $id . "','" . $row['id_produto'] . "','" . $row['quantidade'] . "','" . $row['unidade_medida'] . "');";
      }

      $this->firephp->log($sql);

      $res = $ocorrenciasDatabase->query($sql);



      // Clean tmp_ocorrencia_produto
      $sql = "delete from tmp_ocorrencia_produto;";

      $res = $ocorrenciasDatabase->query($sql);

      $this->firephp->log($sql);
    }


    // Inserting informations about the shipment, related to the oil form
    if(isset($form['inputNomeNavio'])) {
      $sql = "insert into detalhamento_ocorrencia (id_ocorrencia, des_navio, des_instalacao, des_funcao_comunicante ) VALUES ('" .
              $id . "','" . $form['inputNomeNavio'] . "','" . $form['inputNomeInstalacao'] . "','"  . $form['inputFuncaoNavio'] . "');";

      $ocorrenciasDatabase->query($sql);

      $this->firephp->log($sql);

    }

  	$ocorrenciasDatabase->trans_complete();

  }

  public function update($form)
  {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

   // $this->load->library('firephp');

    // Mantain the integrity of the DB.
    // Put TRUE for a test mode (rollback every query, just as a debug mode.)
    // $this->db->trans_start();
    $ocorrenciasDatabase->trans_start();


    $fields = "select * from ocorrencia where nro_ocorrencia='" . $form['comunicado'] . "';";

    $oldOcorrencia = $ocorrenciasDatabase->query($fields)->row_array();

    // Creating the SQL for the new "ocorrencia" entry on the Database

    $fields = "";


    $fields = $fields . "informacao_geografica=";
    if (isset($form["semLocalizacao"])) {
      $fields = $fields . "'N'";
    } else {
      $fields = $fields . "'S'";
    }


    if(!isset($form['semDataObs'])) {
      if (isset($form["inputDataObs"])) {
        $fields = $fields .",dt_primeira_obs='" . $form["inputDataObs"] . "187'";
      }
      if (!empty($form["inputHoraObs"])) {
        $fields = $fields .",hr_primeira_obs='" . $form["inputHoraObs"] . "'";
      }
      $fields = $fields .",periodo_primeira_obs=";
      switch($form["PeriodoObs"]) {
        case "obsMatutino":
          $fields = $fields . "'M'";
          break;
        case "obsVespertino":
          $fields = $fields . "'V'";
          break;
        case "obsNoturno":
        $fields = $fields . "'N'";
          break;
        case "obsMadrugada":
          $fields = $fields . "'S'";
          break;
      }
    }

    if(!isset($form['semDataInci'])) {
      if (isset($form["inputDataInci"])) {
        $fields = $fields .",dt_ocorrencia='" . $form["inputDataInci"] . "'";
      }
      if (!empty($form["inputHoraInci"])) {
        $fields = $fields .",hr_ocorrencia='" . $form["inputHoraInci"] . "'";
      }
      $fields = $fields .",periodo_ocorrencia=";
      switch($form["PeriodoInci"]) {
        case "inciMatutino":
          $fields = $fields . "'M'";
          break;
        case "inciVespertino":
          $fields = $fields . "'V'";
          break;
        case "inciNoturno":
        $fields = $fields . "'N'";
          break;
        case "inciMadrugada":
          $fields = $fields . "'S'";
          break;
      }
    }

    $fields = $fields .",plano_emergencia=";
    if ($form["planoEmergencia"] == '1') {
      $fields = $fields . "'S'";
    } else {
      $fields = $fields . "'N'";
    }

    $fields = $fields .",ocorrencia_oleo=";
    if (isset($form["hasOleo"])) {
      $fields = $fields . "'S'";
    } else {
      $fields = $fields . "'N'";
    }

    // Informação compelementar da origem do acidente
    if (isset($form["inputCompOrigem"])) {
      $fields = $fields . ",des_complemento_tipo_localizaca='" . $form["inputCompOrigem"] . "'";
    }

    // Informação compelementar da instituição atuando no Local
    if (isset($form["inputCompInstituicao"])) {
      $fields = $fields . ",des_complemento_instituicao_atu='" . $form["inputCompInstituicao"] . "'";
    }

    // Informação compelementar do tipo de evento
    if (isset($form["inputCompEvento"])) {
      $fields = $fields . ",des_complemento_tipo_evento='" . $form["inputCompEvento"] . "'";
    }

    // Informação compelementar dos danos idenfitifados
    if (isset($form["inputCompDano"])) {
      $fields = $fields . ",des_complemento_tipo_dano_ident='" . $form["inputCompDano"] . "'";
    }

    if (isset($form["inputDesDanos"])) {
      $fields = $fields . ",des_danos='" . $form["inputDesDanos"] . "'";
    }

    // Plano de Emergencia acionado ou nao
    $fields = $fields . ",plano_emergencia_acionado=";
    if (isset($form["planoAcionado"])) {
      $fields = $fields . "'S'";
    } else {
      $fields = $fields . "'N'";
    }

    // Procedimentos de Atendimento Adotados
    $fields = $fields .",iniciados_outras_providencias=";
    if (isset($form["outrasMedidas"])) {
      $fields = $fields . "'S'";

      $fields = $fields . ",des_outras_providencias='" . $form["inputMedidasTomadas"] . "'";

    } else {
      $fields = $fields . "'N'";
    }

    // Tipo do Produto

    if (isset($form["inputCausaProvavel"])) {
      $fields = $fields . ",des_causa_provavel='" . $form["inputCausaProvavel"] . "'";
    }

    $fields = $fields . ",situacao_atual_descarga=";
    switch($form["SituacaoDescarga"]) {
      case '1':
        $fields = $fields . "'P'";
        break;
      case '2':
        $fields = $fields . "'N'";
        break;
      case '3':
        $fields = $fields . "'S'";
        break;
      case '4':
        $fields = $fields . "'A'";
        break;
    }

    // Identificacao do responsavel
    $fields = $fields . ",informacao_responsavel=";
    if (isset($form["semResponsavel"])) {
      $fields = $fields . "'N'";
    } else {
      $fields = $fields . "'T'";

      if (!empty($oldOcorrencia['id_usuario'])) {

        $subfields = "update responsavel set ";

        if(isset($form["inputResponsavel"])) {
          $subfields = $subfields . "nome='" . $form["inputResponsavel"] . "'";
        }

        if(isset($form["inputCPFCNPJ"])) {
          $subfields = $subfields . ",cpf_cnpj='" . $form["inputCPFCNPJ"] . "'";
        }

        if(isset($form["slctLicenca"])) {
          $subfields = $subfields . "des_licenca_ambiental='" . $form["slctLicenca"] . "'";
        }


        $subfields = $subfields . " where id_responsavel='" . $oldOcorrencia['id_usuario'] . "'";

        $ocorrenciasDatabase->query($subfields);

      } else {

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

        $ocorrenciasDatabase->query($subfields);

        $fields = $fields . ",id_usuario='" . $ocorrenciasDatabase->insert_id() . "'";
      }

    }

    // Identificação do comunicante
    if (isset($form["inputNomeInformante"])) {
      $fields = $fields . ",nome_comunicante='" . $form["inputNomeInformante"] . "'";
    }

    if (isset($form["inputTelInformante"])) {
      $fields = $fields . ",telefone_contato='" . $form["inputTelInformante"] . "'";
    }

    if (isset($form["inputEmailInformante"])) {
      $fields = $fields . ",email_comunicante='" . $form["inputEmailInformante"] . "'";
    }

    if(isset($form["comunicado"])) {
      $fields = $fields . ",nro_ocorrencia='" . $form["comunicado"] . "'";
    }

    if (isset($form["inputDesOcorrencia"])) {
      $fields = $fields . ",des_ocorrencia='" . $form["inputDesOcorrencia"] . "'";
    }

    if(isset($form["inputDesObs"])) {
      $fields = $fields . ",des_obs='" . $form["inputDesObs"] . "'";
    }

    // DEVERIA???
    // $fields = $fields . ",dt_registro=now()";

    $sqlOcorrencias =  "update ocorrencia set " . $fields . " where nro_ocorrencia='" . $form['comunicado'] . "';";

    // Saves on the Database the new entry
    $ocorrenciasDatabase->query($sqlOcorrencias);

    $this->firephp->log($sqlOcorrencias);

    // Creating the relations on the Form

    $id = $oldOcorrencia['id_ocorrencia'];

    $fields = "select * from ocorrencia_pon where id_ocorrencia='" . $oldOcorrencia['id_ocorrencia'] . "';";

    $oldPon = $ocorrenciasDatabase->query($fields);

    if ($oldPon->num_rows() > 0) {
      // Updating the Geographic point of the form
      if (isset($form["inputLat"]) and isset($form["inputLng"])) {

        $epsg = isset($form["inputEPSG"]) ? $form["inputEPSG"] : "4674";

        $subfields = " update ocorrencia_pon set " .
                        " shape=ST_SetSRID(ST_MakePoint(" . $form["inputLng"] . "," . $form["inputLat"] . "), " . $epsg . ")" .
                     " where id_ocorrencia='" . $id . "';";

        $ocorrenciasDatabase->query($subfields);

        $this->firephp->log($subfields);
      }
    } else {
      // Creating the Geographic point of the form
      if (isset($form["inputLat"]) and isset($form["inputLng"])) {

        $epsg = isset($form["inputEPSG"]) ? $form["inputEPSG"] : "4674";

        $subfields = " insert into ocorrencia_pon (id_ocorrencia, shape) " .
                     " values " .
                     "(" . $id . "," . "ST_SetSRID(ST_MakePoint(" . $form["inputLng"] . "," . $form["inputLat"] . "), " . $epsg . "));";

        $ocorrenciasDatabase->query($subfields);

        $this->firephp->log($subfields);
      }
    }

    // Relation R1
    // Clean all relations before insert the new ones
    $fields = "delete from r1 where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
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
    // Clean all relations before insert the new ones
    $fields = "delete from r2 where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
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
    // Clean all relations before insert the new ones
    $fields = "delete from r3 where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
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
    // Clean all relations before insert the new ones
    $fields = "delete from r4 where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
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
    // Clean all relations before insert the new ones
    $fields = "delete from r5 where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['tipoFonteInformacao'])) {
      foreach($form['tipoFonteInformacao'] as $tipoFonteInformacao) {
        $sql = "insert into r5 (id_ocorrencia, id_tipo_fonte_informacao ) VALUES (" .
                $id . "," .  $tipoFonteInformacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }

    // Prdutcs

    // Verifies if there is any fields on the tmp to be
    // saved (in case it's a create form). The Load Form updates automatically
    // the products
    $sql = "select * from tmp_ocorrencia_produto;";

    $res = $ocorrenciasDatabase->query($sql);

    if($res->num_rows() > 0){

      // Retrieve rows from tmp_ocorrencia_produto
      $sql = "select * from tmp_ocorrencia_produto;";

      $res = $ocorrenciasDatabase->query($sql);

      $this->firephp->log($res->result_array());

      // Copy rows from tmp_ocorrencia_produto to ocorrencia_produto

      $sql = "";

      foreach ($res->result_array() as $key => $row) {

        $this->firephp->log($row);

        $sql = $sql .
               " insert into ocorrencia_produto " .
               " (id_ocorrencia,id_produto,quantidade,unidade_medida) values " .
               " ('" . $id . "','" . $row['id_produto'] . "','" . $row['quantidade'] . "','" . $row['unidade_medida'] . "');";
      }

      $this->firephp->log($sql);

      $res = $ocorrenciasDatabase->query($sql);



      // Clean tmp_ocorrencia_produto
      $sql = "delete from tmp_ocorrencia_produto;";

      $res = $ocorrenciasDatabase->query($sql);

      $this->firephp->log($sql);
    }


    if(isset($form['inputNomeNavio'])) {

      // Verifies if the form already have a entry for informations about the shipment
      $fields = "select * from detalhamento_ocorrencia where id_ocorrencia='" . $id . "';";

      $oldShipment =  $ocorrenciasDatabase->query($fields);

      if ($oldShipment->num_rows() > 0) {
        // Updating informations about the shipment, related to the oil form
        $sql = " update detalhamento_ocorrencia set " .
                  " des_navio='" . $form['inputNomeNavio'] . "'," .
                  " des_instalacao='" . $form['inputNomeInstalacao'] . "'," .
                  " des_funcao_comunicante='" . $form['inputFuncaoNavio'] . "'" .
               " where id_ocorrencia='" . $id . "';";

        $ocorrenciasDatabase->query($sql);

        $this->firephp->log($sql);

      } else {
        // Inserting informations about the shipment, related to the oil form
        $sql = " insert into detalhamento_ocorrencia " .
                    "(id_ocorrencia, des_navio, des_instalacao, des_funcao_comunicante) " .
                 "values ( " .
                    "'" . $id . "'," .
                    "'" . $form['inputNomeNavio'] . "'," .
                    "'" . $form['inputNomeInstalacao'] . "'," .
                    "'" . $form['inputFuncaoNavio'] . "');";

        $ocorrenciasDatabase->query($sql);

        $this->firephp->log($sql);
      }
    }


    $ocorrenciasDatabase->trans_complete();

  }

  public function convertDBtoForm($dbResult) {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $this->load->helper('date');

    // $this->firephp->log($dbResult);

    // Informations about the oil form
    if($dbResult['ocorrencia_oleo']) {
      $form['hasOleo'] = $dbResult['ocorrencia_oleo'];

      $query = "select * from detalhamento_ocorrencia where id_ocorrencia='" . $dbResult['id_ocorrencia'] . "';";
      $infoOil = $ocorrenciasDatabase->query($query)->row_array();
    }

    // Localizacao
    $form['inputLat'] = $dbResult['inputlat'];
    $form['inputLng'] = $dbResult['inputlng'];
    $form['inputEPSG'] = $dbResult['inputepsg'];
    $form['inputMunicipio'] = $dbResult['inputmunicipio'];
    $form['inputUF'] = $dbResult['inputuf'];
    $form['inputEndereco'] = $dbResult['endereco_ocorrencia'];

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
    $form['inputDataInci'] = date('d/m/Y', strtotime($dbResult['dt_ocorrencia']));
    $form['inputHoraInci'] = $dbResult['hr_ocorrencia'];
    switch ($dbResult['periodo_ocorrencia']) {
      case 'M':
        $form['PeriodoInci'] = 'inciMatutino';
        break;
      case 'V':
        $form['PeriodoInci'] = 'inciVespertino';
        break;
      case 'N':
        $form['PeriodoInci'] = 'inciNoturno';
        break;
      case 'S':
        $form['PeriodoInci'] = 'inciMadrugada';
        break;
    }

    // Origem do Acidente
    $query = "select r1.id_tipo_localizacao from r1 where r1.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoLocalizacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoLocalizacao'], $row['id_tipo_localizacao']);
    }
    $form['inputCompOrigem'] = $dbResult['des_complemento_tipo_localizaca'];

    $form['inputNomeNavio'] = $infoOil['des_navio'];
    $form['inputNomeInstalacao'] = $infoOil['des_instalacao'];

    // Tipo de Evento
    $query = "select r2.id_tipo_evento from r2 where r2.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoEvento'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoEvento'], $row['id_tipo_evento']);
    }
    $form['inputCompEvento'] = $dbResult['des_complemento_tipo_evento'];

    // Detalhes do Acidente
    if ($dbResult['des_causa_provavel']) {
      $form['inputCausaProvavel'] = $dbResult['des_causa_provavel'];
    }

    if($dbResult['situacao_atual_descarga']) {
      switch($dbResult['situacao_atual_descarga']) {
        case 'P':
          $form['SituacaoDescarga'] = '1';
          break;
        case 'N':
          $form['SituacaoDescarga'] = '2';
          break;
        case 'S':
          $form['SituacaoDescarga'] = '3';
          break;
        case 'A':
          $form['SituacaoDescarga'] = '4';
          break;
      }
    }
    // $form['SituacaoDescarga'] = $dbResult['situacao_atual_descarga'];

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

    if($dbResult['nro_ocorrencia']) {
      $form['comunicado'] = $dbResult['nro_ocorrencia'];
    }

    // Informações sobre o Informante
    if ($dbResult['nome_comunicante']) {
      $form['inputNomeInformante'] = $dbResult['nome_comunicante'];
    }
    if ($infoOil['des_funcao_comunicante']) {
      $form['inputFuncaoNavio'] = $infoOil['des_funcao_comunicante'];
    }
    if ($dbResult['telefone_contato']) {
      $form['inputTelInformante'] = $dbResult['telefone_contato'];
    }
    if ($dbResult['email_comunicante']) {
      $form['inputEmailInformante'] = $dbResult['email_comunicante'];
    }

    // Informações gerais sobre a Ocorrência
    if($dbResult['des_ocorrencia']) {
      $form['inputDesOcorrencia'] = $dbResult['des_ocorrencia'];
    }
    if($dbResult['des_obs']) {
      $form['inputDesObs'] = $dbResult['des_obs'];
    }

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

    $query = " select ocorrencia.*, " .
                " ST_X(shape) as inputLng, " .
                " ST_Y(shape) as inputLat, " .
                " ST_SRID(shape) as inputEPSG, " .
                " estado as inputMunicipio, " .
                " sigla as inputUF " .
             " from ocorrencia " .
                " left join responsavel as res on (res.id_responsavel = ocorrencia.id_usuario) " .
                " left join ocorrencia_pon on (ocorrencia_pon.id_ocorrencia = ocorrencia.id_ocorrencia) " .
                " left join uf on (uf.id_uf = ocorrencia.id_uf) " .
             " where nro_ocorrencia='" . $nro_ocorrencia . "';";

    $res = $ocorrenciasDatabase->query($query);

    // $this->firephp->log($res->row_array());

    // return $res->row_array();

    if($res->num_rows() > 0)
      return $this->convertDBtoForm($res->row_array());
    else
      return "";
  }
}