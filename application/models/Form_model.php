<?php
class Form_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  public function save($form)
  {
    //
    // Setting the default database
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

  	// Starting database transaction: mantaining the integrity of the DB.
  	// Put TRUE for a test mode (rollback every query, just as a debug mode.)
    $ocorrenciasDatabase->trans_start();


  	// Creating the SQL for the new "ocorrencia" entry on the Database
  	$fields = " (";
  	$values = " (";


    //
    // Type of Form (oil or not)
    //
    $fields = $fields ."ocorrencia_oleo,";
    if (isset($form["hasOleo"])) {
      $values = $values . "'S',";
    } else {
      $values = $values . "'N',";
    }


    //
    // Número da Ocorrência
    //
    if(isset($form["comunicado"])) {
      $fields = $fields . "nro_ocorrencia,";
      $values = $values . "'" . $form["comunicado"] . "',";
    }


    //
    // 1. Localização
    //
    $fields = $fields . "informacao_geografica,";
    // if (isset($form["semLocalizacao"])) {
    //   $values = $values . "'N',";
    // } else {
      $values = $values . "'S',";

           if (isset($form['oceano'])) {
        $fields = $fields . "id_bacia_sedimentar,";

        if ($form['dropdownBaciaSedimentar']){
        $values = $values . "'" . $form['dropdownBaciaSedimentar'] . "',";
    	}else{
        $values = $values . "null,";
    	}
      }
      
      if(isset($form['dropdownUF'])) {
        $fields = $fields . "id_uf,";
        $values = $values . $form['dropdownUF'] . ",";
      }
      if(isset($form['dropdownMunicipio'])) {
        $fields = $fields . "id_municipio,";
        $values = $values . $form['dropdownMunicipio'] . ",";
      }
      if(isset($form['inputEndereco'])) {
        $fields = $fields . "endereco_ocorrencia,";
        $values = $values . "'" . pg_escape_string($form['inputEndereco']) . "',";
      }
    // }


    //
    // 2. Data e Hora do Acidente
    //
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

      if (isset($form['dtFeriado']) and $form['dtFeriado'] == 'on') {
        $fields = $fields . "dt_ocorrencia_feriado,";
        $values = $values . "TRUE,";
      } else {
        $fields = $fields . "dt_ocorrencia_feriado,";
        $values = $values . "FALSE,";
      }
    }


    //
    // 3. Origem do Acidente
    //
    // Informação compelementar da origem do acidente
    if (isset($form["inputCompOrigem"])) {
      $fields = $fields . "des_complemento_tipo_localizaca,";
      $values = $values . "'" . pg_escape_string($form["inputCompOrigem"]) . "',";
    }


    //
    // 4. Tipo de Evento
    //
    // Informação compelementar do tipo de evento
    if (isset($form["inputCompEvento"])) {
      $fields = $fields . "des_complemento_tipo_evento,";
      $values = $values . "'" . pg_escape_string($form["inputCompEvento"]) . "',";
    }


    //
    // 5. Tipo de Produto
    //
    if (!isset($form['semProduto'])) {
      if (isset($form['produtoNaoPerigoso']) and $form['produtoNaoPerigoso'] == 'on') {
        $fields = $fields . "produto_perigoso,";
        $values = $values . "TRUE,";
      } else {
        $fields = $fields . "produto_perigoso,";
        $values = $values . "FALSE,";
      }
      if (isset($form['produtoNaoAplica']) and $form['produtoNaoAplica'] == 'on') {
        $fields = $fields . "produto_nao_se_aplica,";
        $values = $values . "TRUE,";
      } else {
        $fields = $fields . "produto_nao_se_aplica,";
        $values = $values . "FALSE,";
      }
      if (isset($form['produtoNaoEspecificado']) and $form['produtoNaoEspecificado'] == 'on') {
        $fields = $fields . "produto_nao_especificado,";
        $values = $values . "TRUE,";
      } else {
        $fields = $fields . "produto_nao_especificado,";
        $values = $values . "FALSE,";
      }
    }

    if (isset($form["hasOleo"])) {
        if (isset($form['inputTipoSubstancia'])) {
          $fields = $fields . "tipo_substancia,";
          $values = $values . "'" . pg_escape_string($form['inputTipoSubstancia']) . "',";
        }
        if (isset($form['inputVolumeEstimado'])) {
          $fields = $fields . "volume_estimado,";
          //$inputVolumeEstimado = \str_replace(".", "", $form['inputVolumeEstimado']);
          $inputVolumeEstimado = \str_replace(",", ".", $form['inputVolumeEstimado']);
          //$inputVolumeEstimado = (double)number_format($inputVolumeEstimado, 5, '.', '');
          $values = $values . "'" . $inputVolumeEstimado . "',";
        }
    }

    //
    // 6. Detalhes do Acidente
    //
    if(!isset($form['semCausa'])) {
      if (isset($form["inputCausaProvavel"])) {
        $fields = $fields . "des_causa_provavel,";
        $values = $values . "'" . pg_escape_string($form["inputCausaProvavel"]) . "',";
      }
    }

    if (isset($form["hasOleo"])) {

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
    }

    //
    // 7. Danos Identificados
    //
    // Informação compelementar dos danos idenfitifados
    if(!isset($form['semDanos'])) {
      if (isset($form["inputCompDano"])) {
        $fields = $fields . "des_complemento_tipo_dano_ident,";
        $values = $values . "'" . pg_escape_string($form["inputCompDano"]) . "',";
      }
      // if (isset($form["inputDesDanos"])) {
      //   $fields = $fields . "des_danos,";
      //   $values = $values . "'" . $form["inputDesDanos"] . "',";
      // }
    }


    //
    // 8. Identificação Empresa/Órgão Responsável
    //
    $fields = $fields . "informacao_responsavel,";
    if (isset($form["semResponsavel"])) {
      $values = $values . "'N',";
    } else {
      $values = $values . "'T',";

      $subfields = "insert into responsavel (nome, cpf_cnpj, des_licenca_ambiental) VALUES (";

      if(isset($form["inputResponsavel"])) {
        $subfields = $subfields . "'" . pg_escape_string($form["inputResponsavel"]) . "',";
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

      $fields = $fields . "id_responsavel,";
      $values = $values . "'" . $ocorrenciasDatabase->insert_id() . "',";
    }


    //
    // 9. Instituição/Empresa Atuando no Local
    //
    // Informação compelementar da instituição atuando no Local
    if (!isset($form['semInstituicao'])) {
      if (isset($form["inputInfoInstituicaoNome"])) {
        $fields = $fields . "nome_instituicao_atuando,";
        $values = $values . "'" . pg_escape_string($form["inputInfoInstituicaoNome"]) . "',";
      }
      if (isset($form["inputInfoInstituicaoTelefone"])) {
        $fields = $fields . "telefone_instituicao_atuando,";
        $values = $values . "'" . $form["inputInfoInstituicaoTelefone"] . "',";
      }
      if (isset($form["inputCompInstituicao"])) {
        $fields = $fields . "des_complemento_instituicao_atu,";
        $values = $values . "'" . pg_escape_string($form["inputCompInstituicao"]) . "',";
      }
    }


    //
    // 10. Ações Iniciais Tomadas
    //
    if (!isset($form['semProcedimentos'])) {
      $fields = $fields . "plano_emergencia,";

      if ($form["planoEmergencia"] == '1') {
        $values = $values . "'S',";
      } else if ($form["planoEmergencia"] == '0'){
        $values = $values . "'N',";
      } else {
          $values = $values . "'I',";
      }

      $fields = $fields . "plano_emergencia_acionado,";
      if (isset($form["planoAcionado"])) {
        $values = $values . "'S',";
      } else {
        $values = $values . "'N',";
      }

      $fields = $fields ."iniciados_outras_providencias,";
      if (isset($form["outrasMedidas"])) {
        $values = $values . "'S',";

        $fields = $fields . "des_outras_providencias,";
        $values = $values . "'" . pg_escape_string($form["inputMedidasTomadas"]) . "',";
      } else {
        $values = $values . "'N',";
      }
    } else  {
      $fields = $fields . "plano_emergencia,";
      $values = $values . "'N',";
      $fields = $fields . "plano_emergencia_acionado,";
      $values = $values . "'N',";
      $fields = $fields ."iniciados_outras_providencias,";
      $values = $values . "'N',";
    }


    //
    // 11. Informações Gerais Sobre a Ocorrência
    //
    if(isset($form["inputDesObs"])) {
      $fields = $fields . "des_obs,";
      $values = $values . "'" . pg_escape_string($form["inputDesObs"]) . "',";
    }


    //
    // 12.Identificação do Comunicante
    //
    if (isset($form["inputNomeInformante"])) {
      $fields = $fields . "nome_comunicante,";
      $values = $values . "'" . pg_escape_string($form["inputNomeInformante"]) . "',";
    }

    if (isset($form['inputInstEmp'])) {
      $fields = $fields . "des_instituicao_empresa,";
      $values = $values . "'" . pg_escape_string($form['inputInstEmp']) . "',";
    }

    if (isset($form['inputCargoFunc'])) {
      $fields = $fields . "des_funcao_comunicante,";
      $values = $values . "'" . pg_escape_string($form['inputCargoFunc']) . "',";
    }

    if (isset($form["inputTelInformante"])) {
      $fields = $fields . "telefone_contato,";
      $values = $values . "'" . $form["inputTelInformante"] . "',";
    }

    if (isset($form["inputEmailInformante"])) {
      $fields = $fields . "email_comunicante,";
      $values = $values . "'" . pg_escape_string($form["inputEmailInformante"]) . "',";
    }


    //
    // Informations about the person who is sending the form
    //
    if ($this->session->userdata('logged_in')) {
      // getting the user's cpf
      $fields = $fields . "cpf_contato,";
      $values = $values . "'" . $this->session->userdata('username') . "',";

      // getting the user's ip
      $fields = $fields . "ip_contato,";
      $values = $values . "'" . $_SERVER['REMOTE_ADDR'] . "',";
    }


    //
    // Date of registry creation
    //
    $fields = $fields . "dt_registro";
    $values = $values . "now()";


    //
    // Finishing sql query
    //
  	$fields = $fields . ") ";
  	$values = $values . ") ";



    // Executing query on database
    $sqlOcorrencias =  "insert into ocorrencia" . $fields . " VALUES " . $values . ";";


    // Saves on the Database the new entry
    $ocorrenciasDatabase->query($sqlOcorrencias);

    // Saving the ID of the newly created "ocorrencia"
    $id = $ocorrenciasDatabase->insert_id();



    //                                          //
    // Creating the relations on the Form_model //
    //                                          //


    //
    //Saving vectors on database, linking it to the "ocorrencia"
    //
    $fields = "select * from tmp_pon;";
    $point = $ocorrenciasDatabase->query($fields);

    $fields = "select * from tmp_lin;";
    $line = $ocorrenciasDatabase->query($fields);

    $fields = "select * from tmp_pol;";
    $polygon = $ocorrenciasDatabase->query($fields);

    if($line->num_rows() > 0 || $polygon->num_rows() > 0 || $point->num_rows() > 0)
    {
      $fields = " insert into ocorrencia_lin " .
                  " (id_ocorrencia_lin, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_lin_id_ocorrencia_lin_seq') as id_ocorrencia_lin, " .
                          $id . " as id_ocorrencia," .
                          "descricao, " .
                          "shape " .
                  " from tmp_lin where nro_ocorrencia=" . $form['comunicado'] . "; ";
      $fields = $fields . " insert into ocorrencia_pol " .
                  " (id_ocorrencia_pol, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_pol_id_ocorrencia_pol_seq') as id_ocorrencia_pol, " .
                          $id . " as id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_pol where nro_ocorrencia=" . $form['comunicado'] . "; ";
      $fields = $fields . " insert into ocorrencia_pon " .
                  " (id_ocorrencia_pon, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_pon_id_ocorrencia_pon_seq') as id_ocorrencia_pon, " .
                          $id . " as id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_pon where nro_ocorrencia=" . $form['comunicado'] . "; ";


      // Deleted temporary information on tmp tables
      $fields = $fields . " delete from tmp_lin; delete from tmp_pol; delete from tmp_pon;";

      $this->firephp->log($fields);
      $ocorrenciasDatabase->query($fields);
    }


    //
    // Relation ocorrencia_tipo_localizacao
    //
    if(isset($form['tipoLocalizacao'])) {
      foreach($form['tipoLocalizacao'] as $tipoLocalizacao) {
        $sql = "insert into ocorrencia_tipo_localizacao (id_ocorrencia, id_tipo_localizacao) VALUES (" .
                $id . "," .  $tipoLocalizacao .
                ");";

        $this->firephp->log($sql);
        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_evento
    //
    if(isset($form['tipoEvento'])) {
      foreach($form['tipoEvento'] as $tipoEvento) {
        $sql = "insert into ocorrencia_tipo_evento (id_ocorrencia, id_tipo_evento ) VALUES (" .
                $id . "," .  $tipoEvento .
                ");";

        $this->firephp->log($sql);
        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_instituicao_atuando_local
    //
    if(isset($form['instituicaoAtuandoLocal'])) {
      foreach($form['instituicaoAtuandoLocal'] as $instituicaoAtuandoLocal) {
        $sql = "insert into ocorrencia_instituicao_atuando_local (id_ocorrencia, id_instituicao_atuando_local ) VALUES (" .
                $id . "," .  $instituicaoAtuandoLocal .
                ");";

        $this->firephp->log($sql);
        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_dano_identificado
    //
    if(isset($form['tipoDanoIdentificado'])) {
      foreach($form['tipoDanoIdentificado'] as $tipoDanoIdentificado) {
        $sql = "insert into ocorrencia_tipo_dano_identificado (id_ocorrencia, id_tipo_dano_identificado ) VALUES (" .
                $id . "," .  $tipoDanoIdentificado .
                ");";

        $this->firephp->log($sql);
        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_fonte_informacao
    //
    if(isset($form['tipoFonteInformacao'])) {

      foreach($form['tipoFonteInformacao'] as $tipoFonteInformacao) {

        $descOutrasFontInfo = "null";

        //ID == 5 -> Outras fontes de informação.
        if (isset($form['inputDescOutrasFontInfo']) && $tipoFonteInformacao == 5)
          $descOutrasFontInfo = "'". pg_escape_string($form['inputDescOutrasFontInfo']) . "'" ;

        $sql = "insert into ocorrencia_tipo_fonte_informacao (id_ocorrencia, id_tipo_fonte_informacao, desc_outras_fontes) VALUES (" .
                $id . "," .  $tipoFonteInformacao . "," . $descOutrasFontInfo . ");";

        $this->firephp->log($sql);
        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_produto
    //
    // Verifies if there is any fields on the tmp to be saved (in case it's a create form)
    $sql = "select * from tmp_ocorrencia_produto;";
    $res = $ocorrenciasDatabase->query($sql);

    if(($res->num_rows() > 0) and (!isset($form['semProduto'])))
    {
      // Retrieve rows from tmp_ocorrencia_produto
      $sql = "select * from tmp_ocorrencia_produto where nro_ocorrencia='" . $form["comunicado"] . "';";
      $res = $ocorrenciasDatabase->query($sql);
      $this->firephp->log($res->result_array());

      // Copy rows from tmp_ocorrencia_produto to ocorrencia_produto
      $sql = "";

      foreach ($res->result_array() as $key => $row)
      {
        $this->firephp->log($row);
        if (isset($row['id_produto_onu'])) {
          $sql = $sql .
               " insert into ocorrencia_produto " .
               " (id_ocorrencia,id_produto_onu,quantidade,unidade_medida) values " .
               " ('" . $id . "','" . $row['id_produto_onu'] . "','" . $row['quantidade'] . "','" . $row['unidade_medida'] . "');";
        } else if (isset($row['id_produto_outro'])) {
          $sql = $sql .
               " insert into ocorrencia_produto " .
               " (id_ocorrencia,id_produto_outro,quantidade,unidade_medida) values " .
               " ('" . $id . "','" . $row['id_produto_outro'] . "','" . $row['quantidade'] . "','" . $row['unidade_medida'] . "');";
        }

      }

      $this->firephp->log($sql);
      $res = $ocorrenciasDatabase->query($sql);
    }
    // Clean tmp_ocorrencia_produto
    $sql = "delete from tmp_ocorrencia_produto where nro_ocorrencia='" . $form["comunicado"] . "';";
    $res = $ocorrenciasDatabase->query($sql);
    $this->firephp->log($sql);



    // Inserting informations about the shipment, related to the oil form
    // Table responsavel
    if(isset($form['inputNomeNavio']) or isset($form['inputNomeInstalacao']))
    {
      // $funcNavio = isset($form['inputFuncaoNavio']) ? $form['inputFuncaoNavio'] :  "";
      // $nomeNavio = isset($form['inputNomeNavio']) ? "'" . $form['inputNomeNavio'] . "'" :  "NULL";
      // $nomeInstalacao = isset($form['inputNomeInstalacao']) ? "'" . $form['inputNomeInstalacao'] . "'" : "NULL";

      $fields = "id_ocorrencia";
      $values = $id;

      if(isset($form['inputNomeNavio']))  {
        $fields = $fields . ",des_navio";
        $values = $values . ",'" . pg_escape_string($form['inputNomeNavio']) . "'";
      } else if(isset($form['inputNomeInstalacao']))  {
        $fields = $fields . ",des_instalacao";
        $values = $values . ",'" . pg_escape_string($form['inputNomeInstalacao']) . "'";
      }

      $sql = "insert into detalhamento_ocorrencia (" . $fields . ") VALUES (" . $values . ")";

      // $sql = "insert into detalhamento_ocorrencia (id_ocorrencia, des_navio, des_instalacao, des_funcao_comunicante ) VALUES ('" .
      //         $id . "'," . $nomeNavio . "," . $nomeInstalacao . ",'"  . $funcNavio . "');";

      $ocorrenciasDatabase->query($sql);
      $this->firephp->log($sql);
    }


    //
    // Finishing database transaction
    //
  	$ocorrenciasDatabase->trans_complete();

  }

  public function update($form)
  {

    //
    // Setting the default database
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);


    // Starting database transaction: mantaining the integrity of the DB.
    // Put TRUE for a test mode (rollback every query, just as a debug mode.)
    $ocorrenciasDatabase->trans_start();


    //
    // Retrieving values previously saved on the database for comparisson
    //
    $fields = "select * from ocorrencia where nro_ocorrencia='" . $form['comunicado'] . "';";
    $oldOcorrencia = $ocorrenciasDatabase->query($fields)->row_array();


    //
    // Creating the SQL for the new "ocorrencia" entry on the Database
    //
    $fields = "";


    //
    // Type of Form (oil or not)
    //
    $fields = $fields ."ocorrencia_oleo=";
    if (isset($form["hasOleo"])) {
      $fields = $fields . "'S'";
    } else {
      $fields = $fields . "'N'";
    }


    //
    // Número da Ocorrêcia
    //
    $fields = $fields . ",nro_ocorrencia='" . $form["comunicado"] . "'";


    //
    // 1. Localização
    //
    $fields = $fields . ",informacao_geografica=";
    // if (isset($form["semLocalizacao"])) {
    //   $fields = $fields . "'N'";
    // } else {
      $fields = $fields . "'S'";

      if (isset($form['oceano'])) {
        $fields = $fields . ",id_bacia_sedimentar='" . $form['dropdownBaciaSedimentar'] . "'";
      } else {
        $fields = $fields . ",id_bacia_sedimentar=NULL";
      }
      if(isset($form['dropdownUF'])) {
        $fields = $fields . ",id_uf=" . $form['dropdownUF'];
      }
      if(isset($form['dropdownMunicipio'])) {
        $fields = $fields . ",id_municipio=" . pg_escape_string($form['dropdownMunicipio']);
      }

      if(isset($form['inputEndereco'])) {
        $fields = $fields . ",endereco_ocorrencia='" . $form['inputEndereco'] . "'";
      }
    // }


    //
    // 2. Data e Hora do Acidente
    //
    if(!isset($form['semDataObs'])) {
      if (isset($form["inputDataObs"])) {
        $fields = $fields .",dt_primeira_obs='" . $form["inputDataObs"] . "'";
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
    } else {
      $fields = $fields .",dt_primeira_obs=NULL";
      $fields = $fields .",hr_primeira_obs=NULL";
      $fields = $fields .",periodo_primeira_obs=NULL";
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

      if (isset($form['dtFeriado']) and $form['dtFeriado'] == 'on') {
        $fields = $fields . ",dt_ocorrencia_feriado=TRUE";
      } else {
        $fields = $fields . ",dt_ocorrencia_feriado=FALSE";
      }
    } else {
      $fields = $fields .",dt_ocorrencia=NULL";
      $fields = $fields .",hr_ocorrencia=NULL";
      $fields = $fields .",periodo_ocorrencia=NULL";
    }


    //
    // 3. Origem do Acidente
    //
    if (!isset($form['semOrigem'])) {
      if (isset($form["inputCompOrigem"])) {
        $fields = $fields . ",des_complemento_tipo_localizaca='" . pg_escape_string($form["inputCompOrigem"]) . "'";
      }
    } else {
      $fields = $fields . ",des_complemento_tipo_localizaca=''";
    }


    //
    // 4. Tipo de Evento
    //
    if (!isset($form['semEvento'])) {
      if (isset($form["inputCompEvento"])) {
        $fields = $fields . ",des_complemento_tipo_evento='" . pg_escape_string($form["inputCompEvento"]) . "'";
      }
    } else {
      $fields = $fields . ",des_complemento_tipo_evento=''";
    }


    //
    // 5.Tipo de Produto
    //
    if (!isset($form['semProduto'])) {
      if (isset($form["produtoNaoPerigoso"])) {
        $fields = $fields . ",produto_perigoso='t'";
      } else {
        $fields = $fields . ",produto_perigoso='f'";
      }

      if (isset($form["produtoNaoAplica"])) {
        $fields = $fields . ",produto_nao_se_aplica='t'";
      }else {
        $fields = $fields . ",produto_nao_se_aplica='f'";
      }

      if (isset($form["produtoNaoEspecificado"])) {
        $fields = $fields . ",produto_nao_especificado='t'";
      } else {
        $fields = $fields . ",produto_nao_especificado='f'";
      }

    } else {
      $fields = $fields . ",produto_perigoso='f'";
      $fields = $fields . ",produto_nao_se_aplica='f'";
      $fields = $fields . ",produto_nao_especificado='f'";
    }


    if (isset($form['hasOleo'])) {
        if (isset($form["inputTipoSubstancia"])) {
          $fields = $fields . ",tipo_substancia='" . pg_escape_string($form["inputTipoSubstancia"]) . "'";
        } else {
          $fields = $fields . ",tipo_substancia=''";
        }
        if (isset($form["inputVolumeEstimado"])) {
          //$inputVolumeEstimado = \str_replace(".", "", $form['inputVolumeEstimado']);
          $inputVolumeEstimado = \str_replace(",", ".", $form['inputVolumeEstimado']);
          //$inputVolumeEstimado = (double)number_format($inputVolumeEstimado, 5, '.', '');
          $fields = $fields . ",volume_estimado='" . $inputVolumeEstimado . "'";
        } else {
          $fields = $fields . ",volume_estimado='0'";

        }
    }


    //
    // 6. Detalhes do Acidente
    //
    if (!isset($form['semCausa'])) {
      if (isset($form["inputCausaProvavel"])) {
        $fields = $fields . ",des_causa_provavel='" . pg_escape_string($form["inputCausaProvavel"]) . "'";
      }
    } else {
      $fields = $fields . ",des_causa_provavel=''";
    }

    if (isset($form["hasOleo"]))
    {
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
          default:
            $fields = $fields . "NULL";
        }
    }

    //
    // 7. Danos Identificados
    //
    if (!isset($form['semDanos'])) {
      if (isset($form["inputCompDano"])) {
        $fields = $fields . ",des_complemento_tipo_dano_ident='" . pg_escape_string($form["inputCompDano"]) . "'";
      }

      // if (isset($form["inputDesDanos"])) {
      //   $fields = $fields . ",des_danos='" . $form["inputDesDanos"] . "'";
      // }
    } else {
      $fields = $fields . ",des_complemento_tipo_dano_ident=''";
      $fields = $fields . ",des_danos=''";
    }


    //
    // 8. Identificação Empresa/Orgão Responsável
    //
    $fields = $fields . ",informacao_responsavel=";
    if (isset($form["semResponsavel"])) {
      $fields = $fields . "'N'";

      // Remove the old responsible from the database, in case it has one
      if (!empty($oldOcorrencia['id_responsavel'])) {
        $subquery = "delete * from responsavel where id_responsavel='" . $oldOcorrencia['id_responsavel'] . "'";
        $ocorrenciasDatabase->query($subquery);
      }
    } else {
      $fields = $fields . "'T'";

      if (!empty($oldOcorrencia['id_responsavel'])) {
        $subfields = "update responsavel set ";

        if(isset($form["inputResponsavel"])) {
          $subfields = $subfields . "nome='" . pg_escape_string($form["inputResponsavel"]) . "'";
        }
        if(isset($form["inputCPFCNPJ"])) {
          $subfields = $subfields . ",cpf_cnpj='" . $form["inputCPFCNPJ"] . "'";
        }
        if(isset($form["slctLicenca"])) {
          $subfields = $subfields . ",des_licenca_ambiental='" . $form["slctLicenca"] . "'";
        }

        $subfields = $subfields . " where id_responsavel='" . $oldOcorrencia['id_responsavel'] . "'";

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

        $fields = $fields . ",id_responsavel='" . $ocorrenciasDatabase->insert_id() . "'";
      }

    }


    //
    // 9. Instituição/Empresa Atuando no Local
    //
    if (!isset($form['semInstituicao'])) {
      if (isset($form["inputInfoInstituicaoNome"])) {
        $fields = $fields . ",nome_instituicao_atuando='" . pg_escape_string($form["inputInfoInstituicaoNome"]) . "'";
      }
      if (isset($form["inputInfoInstituicaoTelefone"])) {
        $fields = $fields . ",telefone_instituicao_atuando='" . pg_escape_string($form["inputInfoInstituicaoTelefone"]) . "'";
      }
      if (isset($form["inputCompInstituicao"])) {
        $fields = $fields . ",des_complemento_instituicao_atu='" . pg_escape_string($form["inputCompInstituicao"]) . "'";
      }
    } else {
      $fields = $fields . ",nome_instituicao_atuando=''";
      $fields = $fields . ",telefone_instituicao_atuando=''";
      $fields = $fields . ",des_complemento_instituicao_atu=''";
    }


    //
    // 10. Ações Iniciais Tomadas
    //
    if (!isset($form['semProcedimentos'])) {
      $fields = $fields . ",plano_emergencia=";
      if ($form["planoEmergencia"] == '1') {
        $fields = $fields . "'S'";
      } else if ($form["planoEmergencia"] == '0') {
        $fields = $fields . "'N'";
      } else {
          $fields = $fields . "'I'";
      }

      $fields = $fields . ",plano_emergencia_acionado=";
      if (isset($form["planoAcionado"])) {
        $fields = $fields . "'S'";
      } else {
        $fields = $fields . "'N'";
      }
      $fields = $fields . ",iniciados_outras_providencias=";
      if (isset($form["outrasMedidas"])) {
        $fields = $fields . "'S'";

        $fields = $fields . ",des_outras_providencias='" . pg_escape_string($form["inputMedidasTomadas"]) . "'";
      } else {
        $fields = $fields . "'N'";
      }
    } else {
      $fields = $fields . ",plano_emergencia=NULL";
      $fields = $fields . ",plano_emergencia_acionado='N'";
      $fields = $fields . ",iniciados_outras_providencias='N'";
      $fields = $fields . ",des_outras_providencias=''";
    }


    //
    // 11. Informações Gerais Sobre a Ocorrência
    //
    if(isset($form["inputDesObs"])) {
      $fields = $fields . ",des_obs='" . pg_escape_string($form["inputDesObs"]) . "'";
    }


    //
    // 12. Identificação do Comunicante
    //
    if (isset($form["inputNomeInformante"])) {
      $fields = $fields . ",nome_comunicante='" . pg_escape_string($form["inputNomeInformante"]) . "'";
    }
    if (isset($form['inputInstEmp'])) {
      $fields = $fields . ",des_instituicao_empresa='" . pg_escape_string($form["inputInstEmp"]) . "'";
    }
    if (isset($form['inputCargoFunc'])) {
      $fields = $fields . ",des_funcao_comunicante='" . pg_escape_string($form['inputCargoFunc'])  . "'";
    }
    if (isset($form["inputTelInformante"])) {
      $fields = $fields . ",telefone_contato='" . $form["inputTelInformante"] . "'";
    }
    if (isset($form["inputEmailInformante"])) {
      $fields = $fields . ",email_comunicante='" . pg_escape_string($form["inputEmailInformante"]) . "'";
    }

    //
    // Campo que identifica se ocorrência já foi validada ou não
    //
    $fields = $fields . ",validado=";
    if (isset($form["validado"])) {
      $fields = $fields . "'S'";
    } else {
      $fields = $fields . "'N'";
    }


    //
    // Complete the query
    //
    $sqlOcorrencias =  "update ocorrencia set " . $fields . " where nro_ocorrencia='" . $form['comunicado'] . "';";


    // Saves on the Database the new entry
    $ocorrenciasDatabase->query($sqlOcorrencias);



    //                                    //
    // Creating the relations on the Form //
    //                                    //


    // Saving the id of the new registry
    $id = $oldOcorrencia['id_ocorrencia'];


    //
    //Saving vectors on database, linking it to the "ocorrencia"
    //
    $fields = "select * from tmp_pon where nro_ocorrencia=" . $form['comunicado'] . ";";
    $point = $ocorrenciasDatabase->query($fields);

    $fields = "select * from tmp_lin where nro_ocorrencia=" . $form['comunicado'] . ";";
    $line = $ocorrenciasDatabase->query($fields);

    $fields = "select * from tmp_pol where nro_ocorrencia=" . $form['comunicado'] . ";";
    $polygon = $ocorrenciasDatabase->query($fields);

    if($line->num_rows() > 0 || $polygon->num_rows() > 0 || $point->num_rows() > 0){

      // delete the relations already done with the edited 'ocorrencia'
      $fields = "delete from ocorrencia_lin where id_ocorrencia=" . $id .";";
      $this->firephp->log($fields);
      $ocorrenciasDatabase->query($fields);

      $fields = "delete from ocorrencia_pol where id_ocorrencia=" . $id .";";
      $this->firephp->log($fields);
      $ocorrenciasDatabase->query($fields);

      $fields = "delete from ocorrencia_pon where id_ocorrencia=" . $id .";";
      $this->firephp->log($fields);
      $ocorrenciasDatabase->query($fields);

      $this->firephp->log($line->num_rows() . " " . $polygon->num_rows() . " " . $point->num_rows() );

      $fields = " insert into ocorrencia_lin " .
                  " (id_ocorrencia_lin, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_lin_id_ocorrencia_lin_seq') as id_ocorrencia_lin, " .
                          $id . " as id_ocorrencia," .
                          "descricao, " .
                          "shape " .
                  " from tmp_lin where nro_ocorrencia=" . $form['comunicado'] . "; ";
      $fields = $fields . " insert into ocorrencia_pol " .
                  " (id_ocorrencia_pol, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_pol_id_ocorrencia_pol_seq') as id_ocorrencia_pol, " .
                          $id . " as id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_pol where nro_ocorrencia=" . $form['comunicado'] . "; ";
      $fields = $fields . " insert into ocorrencia_pon " .
                  " (id_ocorrencia_pon, id_ocorrencia, descricao, shape)" .
                  " select nextval('ocorrencia_pon_id_ocorrencia_pon_seq') as id_ocorrencia_pon, " .
                          $id . " as id_ocorrencia, " .
                          "descricao, " .
                          "shape " .
                  " from tmp_pon where nro_ocorrencia=" . $form['comunicado'] . "; ";


      // Deleted temporary information on tmp tables
      $fields = $fields . " delete from tmp_lin; delete from tmp_pol; delete from tmp_pon;";

      // $this->firephp->log($sql);
      $this->firephp->log($fields);

      // $ocorrenciasDatabase->query($sql);
      $ocorrenciasDatabase->query($fields);
    }


    //
    // Relation ocorrencia_tipo_localizacao
    // Clean all relations before insert the new ones
    $fields = "delete from ocorrencia_tipo_localizacao where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['tipoLocalizacao'])) {
      foreach($form['tipoLocalizacao'] as $tipoLocalizacao) {
        $sql = "insert into ocorrencia_tipo_localizacao (id_ocorrencia, id_tipo_localizacao) VALUES (" .
                $id . "," .  $tipoLocalizacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_evento
    // Clean all relations before insert the new ones
    $fields = "delete from ocorrencia_tipo_evento where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['tipoEvento'])) {
      foreach($form['tipoEvento'] as $tipoEvento) {
        $sql = "insert into ocorrencia_tipo_evento (id_ocorrencia, id_tipo_evento ) VALUES (" .
                $id . "," .  $tipoEvento .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_instituicao_atuando_local
    // Clean all relations before insert the new ones
    $fields = "delete from ocorrencia_instituicao_atuando_local where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['instituicaoAtuandoLocal'])) {
      foreach($form['instituicaoAtuandoLocal'] as $instituicaoAtuandoLocal) {
        $sql = "insert into ocorrencia_instituicao_atuando_local (id_ocorrencia, id_instituicao_atuando_local ) VALUES (" .
                $id . "," .  $instituicaoAtuandoLocal .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_dano_identificado
    // Clean all relations before insert the new ones
    $fields = "delete from ocorrencia_tipo_dano_identificado where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['tipoDanoIdentificado'])) {
      foreach($form['tipoDanoIdentificado'] as $tipoDanoIdentificado) {
        $sql = "insert into ocorrencia_tipo_dano_identificado (id_ocorrencia, id_tipo_dano_identificado ) VALUES (" .
                $id . "," .  $tipoDanoIdentificado .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation ocorrencia_tipo_fonte_informacao
    // Clean all relations before insert the new ones
    $fields = "delete from ocorrencia_tipo_fonte_informacao where id_ocorrencia='" . $id . "';";
    $ocorrenciasDatabase->query($fields);

    // Insert new relations
    if(isset($form['tipoFonteInformacao'])) {

      foreach($form['tipoFonteInformacao'] as $tipoFonteInformacao) {

          $descOutrasFontInfo = "null";

        //ID == 5 -> Outras fontes de informação.
        if (isset($form['inputDescOutrasFontInfo']) && $tipoFonteInformacao == 5)
          $descOutrasFontInfo = "'". pg_escape_string($form['inputDescOutrasFontInfo']) . "'" ;

        $sql = "insert into ocorrencia_tipo_fonte_informacao (id_ocorrencia, id_tipo_fonte_informacao, desc_outras_fontes ) VALUES (" .
                $id . "," .  $tipoFonteInformacao . "," . $descOutrasFontInfo  . ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation responsavel (in case it is a oil form)
    // Table responsavel
    if (!isset($form['semNavioInstalacao'])) {
      if(isset($form['inputNomeNavio']) or isset($form['inputNomeInstalacao'])) {
        // Verifies if the form already have a entry for informations about the shipment
        $fields = "select * from detalhamento_ocorrencia where id_ocorrencia='" . $id . "';";
        $oldShipment =  $ocorrenciasDatabase->query($fields);

        // $funcNavio = isset($form['inputFuncaoNavio']) ? "'" . $form['inputFuncaoNavio'] . "'" : "";
        // $nomeNavio = isset($form['inputNomeNavio']) ? "'" . $form['inputNomeNavio'] . "'" : NULL;
        // $nomeInstalacao = isset($form['inputNomeInstalacao']) ? "'" . $form['inputNomeInstalacao'] . "'" : NULL;

        if ($oldShipment->num_rows() > 0) {
          // Updating informations about the shipment, related to the oil form
          $sql = " update detalhamento_ocorrencia set ";

          if(isset($form['inputNomeNavio'])) {
            $sql = $sql . " des_navio='" . $form['inputNomeNavio'] . "'";
          } else if (isset($form['inputNomeInstalacao'])) {
            $sql = $sql . " des_instalacao='" . $form['inputNomeInstalacao'] . "'";
          }

          $sql = $sql . " where id_ocorrencia='" . $id . "';";

          $ocorrenciasDatabase->query($sql);
        } else {
          // Inserting informations about the shipment, related to the oil form

          // $funcNavio = isset($form['inputFuncaoNavio']) ? $form['inputFuncaoNavio'] :  "";
          // $nomeNavio = isset($form['inputNomeNavio']) ? "'" . $form['inputNomeNavio'] . "'" :  "NULL";
          // $nomeInstalacao = isset($form['inputNomeInstalacao']) ? "'" . $form['inputNomeInstalacao'] . "'" : "NULL";

          $fields = "id_ocorrencia";
          $values = $id;

          if(isset($form['inputNomeNavio']))  {
            $fields = $fields . ",des_navio";
            $values = $values . ",'" . $form['inputNomeNavio'] . "'";
          } else if(isset($form['inputNomeInstalacao']))  {
            $fields = $fields . ",des_instalacao";
            $values = $values . ",'" . $form['inputNomeInstalacao'] . "'";
          }

          $sql = "insert into detalhamento_ocorrencia (" . $fields . ") VALUES (" . $values . ")";

          // $sql = " insert into detalhamento_ocorrencia " .
          //             "(id_ocorrencia, des_navio, des_instalacao, des_funcao_comunicante) " .
          //          "values ( " .
          //             "'" . $id . "'," .
          //             "" . $nomeNavio . "," .
          //             "" . $nomeInstalacao . "," .
          //             "'" . $funcNavio . "');";

          $ocorrenciasDatabase->query($sql);
        }
      }
    } else {
      $sql = " delete from detalhamento_ocorrencia where id_ocorrencia='" . $id . "';";

      $ocorrenciasDatabase->query($sql);
    }

    //
    // Finishing the database transaction
    //
    $ocorrenciasDatabase->trans_complete();

  }

  public function convertDBtoForm($dbResult)
  {
    $this->firephp->log('convertDBtoForm');
    //
    // Setting up the default database
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    // Loading the Date Helper, from CodeIgniter
    $this->load->helper('date');

    //
    // Retrieving info on the type of form
    //
    if($dbResult['ocorrencia_oleo']) {
      $form['hasOleo'] = $dbResult['ocorrencia_oleo'];

      $query = "select * from detalhamento_ocorrencia where id_ocorrencia='" . $dbResult['id_ocorrencia'] . "';";
      $infoOil = $ocorrenciasDatabase->query($query)->row_array();
    }


    //
    // Retrieving the "Numero da Ocorrência"
    //
    if($dbResult['nro_ocorrencia']) {
      $form['comunicado'] = $dbResult['nro_ocorrencia'];
    }


    //
    // 1. Localizacao
    //
    list($form['inputLat'], $form['inputLng']) = explode(" ",$dbResult['coordinate']);
    $form['inputEPSG'] = $dbResult['inputepsg'];
    if(isset($dbResult['id_bacia_sedimentar'])) {
      $form['dropdownBaciaSedimentar'] = $dbResult['id_bacia_sedimentar'];
      $form['oceano'] = "on";
    }
    $form['dropdownMunicipio'] = $dbResult['dropdownmunicipio'];
    $form['dropdownUF'] = $dbResult['dropdownuf'];
    $form['inputEndereco'] = $dbResult['endereco_ocorrencia'];


    //
    // 2. Data e Hora do Acidente
    //
    // Setting up the default timezone
    date_default_timezone_set('America/Sao_Paulo');
    if ($dbResult['dt_primeira_obs'] != NULL)
      $form['inputDataObs'] = date('d/m/Y', strtotime($dbResult['dt_primeira_obs']));
    else
      $form['inputDataObs'] = "";
    $form['inputHoraObs'] = $dbResult['hr_primeira_obs'];
    switch ($dbResult['periodo_primeira_obs']) {
      case 'M':
        $form['PeriodoObs'] = 'obsMatutino';
        break;
      case 'V':
        $form['PeriodoObs'] = 'obsVespertino';
        break;
      case 'N':
        $form['PeriodoObs'] = 'obsNoturno';
        break;
      case 'S':
        $form['PeriodoObs'] = 'obsMadrugada';
        break;
    }

    //Data e hora da primeira observação não foi informada.
    if(!isset($dbResult['dt_primeira_obs']))
       $form['semDataObs'] = 'checked';

    if ($dbResult['dt_ocorrencia'] != NULL)
      $form['inputDataInci'] = date('d/m/Y', strtotime($dbResult['dt_ocorrencia']));
    else
      $form['inputDataInci'] = "";
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

    //Data e hora da ocorrencia não foi informada.
    if(!isset($dbResult['dt_ocorrencia']))
       $form['semDataInci'] = 'checked';

    if (isset($dbResult['dt_ocorrencia_feriado']) && $dbResult['dt_ocorrencia_feriado'] == 't') {
      $form['dtFeriado'] = 'checked';
    }

    //
    // 3. Origem do Acidente
    //
    $query = "select ocorrencia_tipo_localizacao.id_tipo_localizacao from ocorrencia_tipo_localizacao where ocorrencia_tipo_localizacao.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoLocalizacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoLocalizacao'], $row['id_tipo_localizacao']);
    }
    $form['inputCompOrigem'] = $dbResult['des_complemento_tipo_localizaca'];

    //Sem Origem do Acidente.
    if (sizeof($form['tipoLocalizacao']) == 0)
        $form["semOrigem"] = "checked";

    if($dbResult['ocorrencia_oleo']) {
      if ($infoOil['des_navio'] == NULL) {
        $form['inputNomeInstalacao'] = $infoOil['des_instalacao'];
        $form['typeOfOrigin'] = 'instalacao';
      } else {
        $form['inputNomeNavio'] = $infoOil['des_navio'];
        $form['typeOfOrigin'] = 'navio';
      }

      //Sem informação do navio ou da instalação.
      if(!isset($infoOil['des_navio']) && !isset($infoOil['des_instalacao']))
         $form['semNavioInstalacao'] = 'checked';

    }


    //
    // 4. Tipo de Evento
    //
    $query = "select ocorrencia_tipo_evento.id_tipo_evento from ocorrencia_tipo_evento where ocorrencia_tipo_evento.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoEvento'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoEvento'], $row['id_tipo_evento']);
    }
    $form['inputCompEvento'] = $dbResult['des_complemento_tipo_evento'];

    //Sem Tipo de Evento.
    if (sizeof($form['tipoEvento']) == 0)
        $form["semEvento"] = "checked";

    //
    // 5. Tipo do produto
    //
    //Ver se há produtos cadastrados.
    $query = "select ocorrencia_produto.id_ocorrencia from ocorrencia_produto where ocorrencia_produto.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $hasProducts = ($ocorrenciasDatabase->query($query)->num_rows() > 0);

    $form['produtoNaoPerigoso'] = $dbResult['produto_perigoso'];
    $form['produtoNaoAplica'] = $dbResult['produto_nao_se_aplica'];
    $form['produtoNaoEspecificado'] = $dbResult['produto_nao_especificado'];

    $prodNaoPerigoso = isset($form['produtoNaoPerigoso']) && !empty($form['produtoNaoPerigoso']) && $form['produtoNaoPerigoso'] == 't';
    $prodNaoAplica = isset($form['produtoNaoAplica']) && !empty($form['produtoNaoAplica']) && $form['produtoNaoAplica'] == 't';
    $prodNaoEspecificado = isset($form['produtoNaoEspecificado']) && !empty($form['produtoNaoEspecificado']) && $form['produtoNaoEspecificado'] == 't';

    $statusProdSetado = ($prodNaoPerigoso || $prodNaoAplica || $prodNaoEspecificado)? true : false;

    $this->firephp->log($hasProducts);

    if (!$hasProducts && !$statusProdSetado)
      $form['semProduto'] = 'checked';


    if($dbResult['ocorrencia_oleo']) {
      $form['inputTipoSubstancia'] = $dbResult['tipo_substancia'];
      $form['inputVolumeEstimado'] = $dbResult['volume_estimado'];

      //Substância não informada.
      if (!isset($form['inputTipoSubstancia']) || empty($form['inputTipoSubstancia']))
        $form['semSubstancia'] =  'checked';

    }


    //
    // 6. Detalhes do Acidente
    //
    if ($dbResult['des_causa_provavel']) {
      $form['inputCausaProvavel'] = $dbResult['des_causa_provavel'];
    }
    else
    {
        //Sem causa provável.
        $form['semCausa'] = 'checked';
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
        default:
          $form['SituacaoDescarga'] = '0';
      }
    }
    else
    {
      $form['SituacaoDescarga'] = '0';
    }


    //
    // 7. Danos Identificados
    //
    $query = "select ocorrencia_tipo_dano_identificado.id_tipo_dano_identificado from ocorrencia_tipo_dano_identificado where ocorrencia_tipo_dano_identificado.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoDanoIdentificado'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoDanoIdentificado'], $row['id_tipo_dano_identificado']);
    }
    // $form['inputDesDanos'] = $dbResult['des_danos'];
    $form['inputCompDano'] = $dbResult['des_complemento_tipo_dano_ident'];

    if(sizeof($form['tipoDanoIdentificado']) == 0)
        $form['semDanos'] = 'checked';

    //
    // 8. Identificacao Empresa/Orgao Responsavel
    //
    $form['inputResponsavel'] = $dbResult['nome'];
    $form['inputCPFCNPJ'] = $dbResult['cpf_cnpj'];
    $form['slctLicenca'] = $dbResult['des_licenca_ambiental'];

    $semReponsavel = (!isset($form['inputResponsavel']));

    if($semReponsavel)
      $form['semResponsavel'] = 'checked';


    //
    // 9. Instituição/Empresa Atuando no Local
    //
    $query = "select ocorrencia_instituicao_atuando_local.id_instituicao_atuando_local from ocorrencia_instituicao_atuando_local where ocorrencia_instituicao_atuando_local.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['instituicaoAtuandoLocal'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['instituicaoAtuandoLocal'], $row['id_instituicao_atuando_local']);
    }
    $form['inputInfoInstituicaoNome'] = $dbResult['nome_instituicao_atuando'];
    $form['inputInfoInstituicaoTelefone'] = $dbResult['telefone_instituicao_atuando'];
    $form['inputCompInstituicao'] = $dbResult['des_complemento_instituicao_atu'];

    if (sizeof($form['instituicaoAtuandoLocal']) == 0)
        $form['semInstituicao'] = 'checked';

    //
    // 10. Ações Iniciais Tomadas
    //
    if ($dbResult['plano_emergencia'] == "S") {
      $form['planoEmergencia'] = "1";
    } else if ($dbResult['plano_emergencia'] == "N") {
      $form['planoEmergencia'] = "0";
    } else {
      $form['planoEmergencia'] = "-1";
    }
    if ($dbResult['plano_emergencia_acionado'] == "S") {
      $form['planoAcionado'] = "on";
    }
    if ($dbResult['iniciados_outras_providencias'] == "S") {
      $form['outrasMedidas'] = "on";
      $form['inputMedidasTomadas'] = $dbResult['des_outras_providencias'];
    }

    if (!isset($dbResult['plano_emergencia']))
       $form['semProcedimentos'] = 'checked';


    //
    // 11. Informações gerais sobre a Ocorrência
    //
    if($dbResult['des_obs']) {
      $form['inputDesObs'] = $dbResult['des_obs'];
    }


    //
    // 12. Informações sobre o Informante
    //

    $form['inputNomeInformante'] = "";
    if ($dbResult['nome_comunicante']) {
      $form['inputNomeInformante'] = $dbResult['nome_comunicante'];
    }

    $form['inputInstEmp'] = "";
    if ($dbResult['des_instituicao_empresa']) {
      $form['inputInstEmp'] = $dbResult['des_instituicao_empresa'];
    }

    $form['inputCargoFunc'] = "";
    if ($dbResult['des_funcao_comunicante']) {
      $form['inputCargoFunc'] = $dbResult['des_funcao_comunicante'];
    }

    $form['inputTelInformante'] = "";
    if ($dbResult['telefone_contato']) {
      $form['inputTelInformante'] = $dbResult['telefone_contato'];
    }

    $form['inputEmailInformante'] = "";
    if ($dbResult['email_comunicante']) {
      $form['inputEmailInformante'] = $dbResult['email_comunicante'];
    }


    //
    // Campo para identificar se ocorrência foi validada ou não
    //
    if ($dbResult['validado']) {
      $form['validado'] = $dbResult['validado'];
    }

    //
    // 13. Fonte de Informação
    //
    $query = "select ocorrencia_tipo_fonte_informacao.id_tipo_fonte_informacao, ocorrencia_tipo_fonte_informacao.desc_outras_fontes from ocorrencia_tipo_fonte_informacao where ocorrencia_tipo_fonte_informacao.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoFonteInformacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {

      array_push($form['tipoFonteInformacao'], $row['id_tipo_fonte_informacao']);
      $form['desc_outras_fontes'] = $row['desc_outras_fontes'];

    }


    //
    //q Return the form newly created from the database
    //
    return $form;
  }

  public function load($nro_ocorrencia)
  {
    //
    // Set the default database to be used
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $query = " select ocorrencia.*, " .
                " ST_AsLatLonText(shape, 'D°M''S.SSS') as coordinate, " .
                " ST_SRID(shape) as inputEPSG, " .
                " ocorrencia.id_municipio as dropdownMunicipio, " .
                " uf.id_uf as dropdownUF, " .
                " res.* " .
             " from ocorrencia " .
                " left join responsavel as res on (res.id_responsavel = ocorrencia.id_responsavel) " .
                " left join ocorrencia_pon on (ocorrencia_pon.id_ocorrencia = ocorrencia.id_ocorrencia) " .
                " left join uf on (uf.id_uf = ocorrencia.id_uf) " .
             " where nro_ocorrencia='" . $nro_ocorrencia . "' " .
             " limit 1;";
    $res = $ocorrenciasDatabase->query($query);

    $this->firephp->log($query);

    //
    // In case the registry exists, calls the function that loads a form from the database
    //
    if($res->num_rows() > 0) {
      return $this->convertDBtoForm($res->row_array());
    } else {
      return "";
    }
  }

  // Returns the "municipios" stored on the database
  public function getMunicipios($id_uf)
  {

    $array = array();
    $array += array (
      '0' => 'Sem Município'
    );

    if (!empty($id_uf)) {

      $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

      $result = $ocorrenciasDatabase->query("select cod_ibge as id, nome as value from municipio where id_uf=" . $id_uf . " order by value;");

      foreach ($result->result_array() as $key => $value) {
        $array += array (
          $value['id']  =>  $value['value']
        );
      }
    }

    return $array;
  }

  // Returns the "UF's" stored on the database
  public function getUFs()
  {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $result = $ocorrenciasDatabase->query("select id_uf as id, sigla as value from uf order by value;");

    $array = array();
    $array += array (
      '0' => 'Sem UF'
    );
    foreach ($result->result_array() as $key => $value) {
      $array += array (
        $value['id']  =>  $value['value']
      );
    }

    return $array;
  }

  // Returns the "UF's" stored on the database
  public function getBaciasSed()
  {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $result = $ocorrenciasDatabase->query("select id_bacia_sedimentar as id, nome as value from bacia_sedimentar order by value;");

    $array = array(
      '' => ''
    );

    foreach ($result->result_array() as $key => $value) {
      $array += array (
        $value['id']  =>  $value['value']
      );
    }

    return $array;
  }


  // Returns the "UF's" stored on the database
  public function generatePdfData($nro_ocorrencia)
  {
    //
    // Setting up the default database
    //
    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $query = " select ocorrencia.*, " .
                " ST_AsLatLonText(shape, 'D°M''S.SSS') as coordinate, " .
                " ST_SRID(shape) as inputEPSG, " .
                " uf.estado as uf_nome, " .
                " municipio.nome as municipio_nome,".
                " res.* " .
             " from ocorrencia " .
                " left join responsavel as res on (res.id_responsavel = ocorrencia.id_responsavel) " .
                " left join ocorrencia_pon on (ocorrencia_pon.id_ocorrencia = ocorrencia.id_ocorrencia) " .
                " left join municipio on (ocorrencia.id_municipio = municipio.cod_ibge)".
                " left join uf on (uf.id_uf = ocorrencia.id_uf) " .
             " where nro_ocorrencia='" . $nro_ocorrencia . "' " .
             " limit 1;";
    $res = $ocorrenciasDatabase->query($query);

    $dbResult = $res->row_array();

    // Loading the Date Helper, from CodeIgniter
    $this->load->helper('date');

    //
    // Retrieving info on the type of form
    //
    if($dbResult['ocorrencia_oleo']) {
      $form['ocorrencia_oleo'] = $dbResult['ocorrencia_oleo'];
      $query = "select * from detalhamento_ocorrencia where id_ocorrencia='" . $dbResult['id_ocorrencia'] . "';";
      $form['infoOil'] = $ocorrenciasDatabase->query($query)->row_array();
    }


    //
    // Retrieving the "Numero da Ocorrência"
    //
    if($dbResult['nro_ocorrencia']) {
      $form['comunicado'] = $dbResult['nro_ocorrencia'];
    }

    //
    // Retrieving the "Data do registro"
    //
    if($dbResult['dt_registro']) {
      $form['dt_registro'] = $dbResult['dt_registro'];
    }

    //
    // Campo para identificar se ocorrência foi validada ou não
    //
    if ($dbResult['validado']) {
      $form['validado'] = $dbResult['validado'];
    }


    //
    // 1. Localizacao
    //
    list($form['inputLat'], $form['inputLng']) = explode(" ",$dbResult['coordinate']);
    $form['inputEPSG'] = $dbResult['inputepsg'];
    if(isset($dbResult['id_bacia_sedimentar'])) {
      $query = "select * from bacia_sedimentar where id_bacia_sedimentar='" . $dbResult['id_bacia_sedimentar'] . "';";
      $infoBacia= $ocorrenciasDatabase->query($query)->row_array();

      $form['bacia_nome'] = $infoBacia['nome'];
      $form['oceano'] = "on";
    } else {
      $form['oceano'] = "off";
    }
    $form['municipio_nome'] = $dbResult['municipio_nome'];
    $form['uf_nome'] = $dbResult['uf_nome'];
    $form['endereco'] = $dbResult['endereco_ocorrencia'];


    //
    // 2. Data e Hora do Acidente
    //
    // Setting up the default timezone

    date_default_timezone_set('America/Sao_Paulo');

    //Data e hora da primeira observação não foi informada.
    if(!isset($dbResult['dt_primeira_obs'])) {
       $form['semDataObs'] = 'checked';
    } else {
      $form['semDataObs'] = '';
      $form['dataObs'] = date('d/m/Y', strtotime($dbResult['dt_primeira_obs']));
      $form['horaObs'] = $dbResult['hr_primeira_obs'];
      switch ($dbResult['periodo_primeira_obs']) {
        case 'M':
          $form['PeriodoObs'] = 'Matutino';
          break;
        case 'V':
          $form['PeriodoObs'] = 'Vespertino';
          break;
        case 'N':
          $form['PeriodoObs'] = 'Noturno';
          break;
        case 'S':
          $form['PeriodoObs'] = 'Madrugada';
          break;
      }

      //Dia da semana por extenso para o incidente
       $diaObsSemana = array(
            '' => 'Data Inválida',
            '0' => 'Domingo',
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado'
        );
       $numSemana = date("w", strtotime($dbResult['dt_primeira_obs']));
       $form['diaObsSemana'] = $diaObsSemana["$numSemana"];
    }

    //Data e hora da ocorrencia não foi informada.
    if(!isset($dbResult['dt_ocorrencia'])) {
       $form['semDataInci'] = 'checked';
    } else {
      $form['semDataInci'] = '';
      $form['dataInci'] = date('d/m/Y', strtotime($dbResult['dt_ocorrencia']));
      $form['horaInci'] = $dbResult['hr_ocorrencia'];
      switch ($dbResult['periodo_ocorrencia']) {
        case 'M':
          $form['PeriodoInci'] = 'Matutino';
          break;
        case 'V':
          $form['PeriodoInci'] = 'Vespertino';
          break;
        case 'N':
          $form['PeriodoInci'] = 'Noturno';
          break;
        case 'S':
          $form['PeriodoInci'] = 'Madrugada';
          break;
      }

      //Dia da semana por extenso para o incidente
       $diaInciSemana = array(
            '' => 'Data Inválida',
            '0' => 'Domingo',
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado'
        );
       $numSemana = date("w", strtotime($dbResult['dt_ocorrencia']));
       $form['diaInciSemana'] = $diaInciSemana["$numSemana"];

      if (isset($dbResult['dt_ocorrencia_feriado']) && $dbResult['dt_ocorrencia_feriado'] == 't') {
        $form['feriado'] = 'checked';
      } else {
        $form['feriado'] = '';
      }
    }

    //
    // 3. Origem do Acidente
    //
    $query = "select tipo_localizacao.des_tipo_localizacao from ocorrencia_tipo_localizacao " .
             "left join tipo_localizacao on (tipo_localizacao.id_tipo_localizacao = ocorrencia_tipo_localizacao.id_tipo_localizacao) " .
             "where ocorrencia_tipo_localizacao.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoLocalizacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoLocalizacao'], $row['des_tipo_localizacao']);
    }
    $form['origem_comp'] = $dbResult['des_complemento_tipo_localizaca'];

    //Sem Origem do Acidente.
    if (sizeof($form['tipoLocalizacao']) == 0) {
        $form["semOrigem"] = "checked";
    } else {
        $form["semOrigem"] = "";
    }

    if($dbResult['ocorrencia_oleo']) {
      if ($form['infoOil']['des_navio'] == NULL) {
        $form['instalacao_nome'] = $form['infoOil']['des_instalacao'];
      } else {
        $form['navio_nome'] = $form['infoOil']['des_navio'];
      }

      //Sem informação do navio ou da instalação.
      if(!isset($form['infoOil']['des_navio']) && !isset($form['infoOil']['des_instalacao']))
         $form['semNavioInstalacao'] = 'checked';

    }


    //
    // 4. Tipo de Evento
    //
    $query = "select tipo_evento.nome from ocorrencia_tipo_evento " .
             "left join tipo_evento on (tipo_evento.id_tipo_evento = ocorrencia_tipo_evento.id_tipo_evento) " .
             "where ocorrencia_tipo_evento.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoEvento'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoEvento'], $row['nome']);
    }
    $form['evento_comp'] = $dbResult['des_complemento_tipo_evento'];

    //Sem Tipo de Evento.
    if (sizeof($form['tipoEvento']) == 0){
        $form["semEvento"] = "checked";
    } else {
        $form["semEvento"] = "";
      }

    //
    // 5. Tipo do produto
    //
    //Ver se há produtos cadastrados.
    $query = "select produto_onu.num_onu, produto_onu.nome, quantidade, " .
               " CASE WHEN unidade_medida='l' THEN 'L' WHEN unidade_medida='m3' THEN 'M³' WHEN unidade_medida='kg' THEN 'Kg' WHEN unidade_medida='t' THEN 'T' END as unidade_medida " .
             "from ocorrencia_produto " .
             "left join produto_onu on (produto_onu.id_produto_onu = ocorrencia_produto.id_produto_onu) " .
             "where ocorrencia_produto.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "' AND produto_onu.nome IS NOT NULL ";
    $produtos_onu = $ocorrenciasDatabase->query($query);
    $hasProductsOnu = ($produtos_onu->num_rows() > 0);

    //Ver se há produtos não onu cadastrados.
    $query = "select produto_outro.nome, quantidade, " .
               " CASE WHEN unidade_medida='l' THEN 'L' WHEN unidade_medida='m3' THEN 'M³' WHEN unidade_medida='kg' THEN 'Kg' WHEN unidade_medida='t' THEN 'T' END as unidade_medida " .
             "from ocorrencia_produto " .
             "left join produto_outro on (produto_outro.id_produto_outro = ocorrencia_produto.id_produto_outro) " .
             "where ocorrencia_produto.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "' AND produto_outro.nome IS NOT NULL ";
    $produtos_outros = $ocorrenciasDatabase->query($query);
    $hasProductsOutros = ($produtos_outros->num_rows() > 0);


    $form['produtoNaoPerigoso'] = $dbResult['produto_perigoso'];
    $form['produtoNaoAplica'] = $dbResult['produto_nao_se_aplica'];
    $form['produtoNaoEspecificado'] = $dbResult['produto_nao_especificado'];

    // $prodNaoPerigoso = isset($form['produtoNaoPerigoso']) && !empty($form['produtoNaoPerigoso']) && ($form['produtoNaoPerigoso'] == 't');
    // $prodNaoAplica = isset($form['produtoNaoAplica']) && !empty($form['produtoNaoAplica']) && ($form['produtoNaoAplica'] == 't');
    // $prodNaoEspecificado = isset($form['produtoNaoEspecificado']) && !empty($form['produtoNaoEspecificado']) && ($form['produtoNaoEspecificado'] == 't');

    $form['statusProdSetado'] = ( ($form['produtoNaoPerigoso'] == 't') || ($form['produtoNaoAplica'] == 't') || ($form['produtoNaoEspecificado'] == 't') ) ? true : false;


    if (!$hasProductsOnu && !$hasProductsOutros && !$form['statusProdSetado']){
      $form['semProduto'] = 'checked';
    } else {
      $form['semProduto'] = '';
      $form['infoProd'] = $produtos_onu->result_array();
      $form['infoProdOutros'] = $produtos_outros->result_array();
    }


    if($dbResult['ocorrencia_oleo']) {
      $form['tipo_substancia'] = $dbResult['tipo_substancia'];
      $form['volume_estimado'] = $dbResult['volume_estimado'];

      //Substância não informada.
      if (!isset($form['inputTipoSubstancia']) || empty($form['inputTipoSubstancia'])) {
        $form['semSubstancia'] =  'checked';
      } else {
        $form['semSubstancia'] =  '';
      }

    }


    //
    // 6. Detalhes do Acidente
    //
    if ($dbResult['des_causa_provavel']) {
      $form['semCausa'] = '';
      $form['causa_provavel'] = $dbResult['des_causa_provavel'];
    } else {
      //Sem causa provável.
      $form['semCausa'] = 'checked';
    }

    if($dbResult['situacao_atual_descarga']) {
      switch($dbResult['situacao_atual_descarga']) {
        case 'P':
          $form['situacao_descarga'] = 'Paralisada';
          break;
        case 'N':
          $form['situacao_descarga'] = 'Não foi paralisada';
          break;
        case 'S':
          $form['situacao_descarga'] = 'Sem condições de informar';
          break;
        case 'A':
          $form['situacao_descarga'] = 'Não se aplica';
          break;
      }
    }


    //
    // 7. Danos Identificados
    //
    $query = "select tipo_dano_identificado.nome  " .
         "from ocorrencia_tipo_dano_identificado  " .
         "left join tipo_dano_identificado on (tipo_dano_identificado.id_tipo_dano_identificado = ocorrencia_tipo_dano_identificado.id_tipo_dano_identificado) " .
         "where ocorrencia_tipo_dano_identificado.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";

    $form['tipoDanoIdentificado'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoDanoIdentificado'], $row['nome']);
    }
    // $form['inputDesDanos'] = $dbResult['des_danos'];
    $form['dano_comp'] = $dbResult['des_complemento_tipo_dano_ident'];

    if(sizeof($form['tipoDanoIdentificado']) == 0) {
      $form['semDanos'] = 'checked';
    } else {
      $form['semDanos'] = '';
    }

    //
    // 8. Identificacao Empresa/Orgao Responsavel
    //
    $form['responsavel'] = $dbResult['nome'];
    $form['cpf_cnpj'] = $dbResult['cpf_cnpj'];

    switch ($dbResult['des_licenca_ambiental']) {
      case '0':
        $form['licenca_ambiental'] = 'Sem informação';
        break;
      case '1':
        $form['licenca_ambiental'] = 'Licença ambiental federal';
        break;
      case '2':
        $form['licenca_ambiental'] = 'Licença ambiental estadual';
        break;
      case '3':
        $form['licenca_ambiental'] = 'Licença ambiental municipal';
        break;
    }

    $semReponsavel = (!isset($dbResult['nome']));

    if($semReponsavel) {
      $form['semResponsavel'] = 'checked';
    } else {
      $form['semResponsavel'] = '';
    }


    //
    // 9. Instituição/Empresa Atuando no Local
    //
    $query = "select instituicao_atuando_local.nome " .
             "from ocorrencia_instituicao_atuando_local " .
             "left join instituicao_atuando_local on (instituicao_atuando_local.id_instituicao_atuando_local = ocorrencia_instituicao_atuando_local.id_instituicao_atuando_local) " .
             "where ocorrencia_instituicao_atuando_local.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['instituicaoAtuandoLocal'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['instituicaoAtuandoLocal'], $row['nome']);
    }
    $form['instituicao_nome'] = $dbResult['nome_instituicao_atuando'];
    $form['instituicao_telefone'] = $dbResult['telefone_instituicao_atuando'];
    $form['instituicao_comp'] = $dbResult['des_complemento_instituicao_atu'];

    if (sizeof($form['instituicaoAtuandoLocal']) == 0) {
        $form['semInstituicao'] = 'checked';
    } else {
        $form['semInstituicao'] = '';
    }

    //
    // 10. Ações Iniciais Tomadas
    //
    if ($dbResult['plano_emergencia'] == "S") {
      $form['plano_emergencia'] = "Sim";
    } else if ($dbResult['plano_emergencia'] == "N") {
      $form['plano_emergencia'] = "Não";
    } else {
      $form['plano_emergencia'] = "Sem informação";
    }

    if ($dbResult['plano_emergencia_acionado'] == "S") {
      $form['plano_acionado'] = "on";
    } else {
      $form['plano_acionado'] = "off";
    }

    if ($dbResult['iniciados_outras_providencias'] == "S") {
      $form['outras_medidas'] = "on";
      $form['medidas_tomadas'] = $dbResult['des_outras_providencias'];
    } else {
      $form['outras_medidas'] = "off";
    }

    if (!isset($dbResult['plano_emergencia'])) {
       $form['semProcedimentos'] = 'checked';
    } else {
       $form['semProcedimentos'] = '';
     }


    //
    // 11. Informações gerais sobre a Ocorrência
    //
    if($dbResult['des_obs']) {
      $form['des_obs'] = $dbResult['des_obs'];
    }


    //
    // 12. Informações sobre o Informante
    //

    if ($dbResult['nome_comunicante']) {
      $form['nome_comunicante'] = $dbResult['nome_comunicante'];
    } else {
      $form['nome_comunicante'] = "";
    }

    if ($dbResult['des_instituicao_empresa']) {
      $form['instituicao_empresa'] = $dbResult['des_instituicao_empresa'];
    } else {
      $form['instituicao_empresa'] = "";
    }

    if ($dbResult['des_funcao_comunicante']) {
      $form['cargo_funcao'] = $dbResult['des_funcao_comunicante'];
    } else {
      $form['cargo_funcao'] = "";
    }

    if ($dbResult['telefone_contato']) {
      $form['tel_informante'] = $dbResult['telefone_contato'];
    } else {
      $form['tel_informante'] = "";
    }

    if ($dbResult['email_comunicante']) {
      $form['email_informante'] = $dbResult['email_comunicante'];
    } else {
      $form['email_informante'] = "";
    }

    //
    // 13. Fonte de Informação
    //
    $query = "select tipo_fonte_informacao.nome, ocorrencia_tipo_fonte_informacao.desc_outras_fontes " .
             "from ocorrencia_tipo_fonte_informacao " .
             "left join tipo_fonte_informacao on (tipo_fonte_informacao.id_tipo_fonte_informacao = ocorrencia_tipo_fonte_informacao.id_tipo_fonte_informacao)" .
             "where ocorrencia_tipo_fonte_informacao.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoFonteInformacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoFonteInformacao'], $row['nome']);
      if (isset($row['desc_outras_fontes'])) {
        $form['outras_fontes'] = $row['desc_outras_fontes'];
      }
    }

    //
    // Return the form newly created from the database
    //
    return $form;
  }
}
