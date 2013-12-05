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
    if (isset($form["semLocalizacao"])) {
      $values = $values . "'N',";
    } else {
      $values = $values . "'S',";

      if (isset($form['oceano'])) {
        $fields = $fields . "id_bacia_sedimentar,";
        $values = $values . "'" . $form['dropdownBaciaSedimentar'] . "',";
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
        $values = $values . "'" . $form['inputEndereco'] . "',";
      }
    }


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
      $values = $values . "'" . $form["inputCompOrigem"] . "',";
    }


    //
    // 4. Tipo de Evento
    //
    // Informação compelementar do tipo de evento
    if (isset($form["inputCompEvento"])) {
      $fields = $fields . "des_complemento_tipo_evento,";
      $values = $values . "'" . $form["inputCompEvento"] . "',";
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

      if (isset($form["hasOleo"])) {
        if (isset($form['inputTipoSubstancia'])) {
          $fields = $fields . "tipo_substancia,";
          $values = $values . "'" . $form['inputTipoSubstancia'] . "',";
        }
        if (isset($form['inputVolumeEstimado'])) {
          $fields = $fields . "volume_estimado,";
          $values = $values . "'" . $form['inputVolumeEstimado'] . "',";
        }
      }
    }


    //
    // 6. Detalhes do Acidente
    //
    if(!isset($form['semCausa'])) {
      if (isset($form["inputCausaProvavel"])) {
        $fields = $fields . "des_causa_provavel,";
        $values = $values . "'" . $form["inputCausaProvavel"] . "',";
      }
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


    //
    // 7. Danos Identificados
    //
    // Informação compelementar dos danos idenfitifados
    if(!isset($form['semDanos'])) {
      if (isset($form["inputCompDano"])) {
        $fields = $fields . "des_complemento_tipo_dano_ident,";
        $values = $values . "'" . $form["inputCompDano"] . "',";
      }
      if (isset($form["inputDesDanos"])) {
        $fields = $fields . "des_danos,";
        $values = $values . "'" . $form["inputDesDanos"] . "',";
      }
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
        $values = $values . "'" . $form["inputInfoInstituicaoNome"] . "',";
      }
      if (isset($form["inputInfoInstituicaoTelefone"])) {
        $fields = $fields . "telefone_instituicao_atuando,";
        $values = $values . "'" . $form["inputInfoInstituicaoTelefone"] . "',";
      }
      if (isset($form["inputCompInstituicao"])) {
        $fields = $fields . "des_complemento_instituicao_atu,";
        $values = $values . "'" . $form["inputCompInstituicao"] . "',";
      }
    }


    //
    // 10. Ações Iniciais Tomadas
    //
    if (!isset($form['semProcedimentos'])) {
      $fields = $fields . "plano_emergencia,";
      if ($form["planoEmergencia"] == '1') {
        $values = $values . "'S',";
      } else {
        $values = $values . "'N',";
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
        $values = $values . "'" . $form["inputMedidasTomadas"] . "',";
      } else {
        $values = $values . "'N',";
      }
    }


    //
    // 11. Informações Gerais Sobre a Ocorrência
    //
    if(isset($form["inputDesObs"])) {
      $fields = $fields . "des_obs,";
      $values = $values . "'" . $form["inputDesObs"] . "',";
    }


    //
    // 12.Identificação do Comunicante (In case the user is logged)
    //
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
        $sql = "insert into ocorrencia_tipo_fonte_informacao (id_ocorrencia, id_tipo_fonte_informacao ) VALUES (" .
                $id . "," .  $tipoFonteInformacao .
                ");";

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
      $sql = "select * from tmp_ocorrencia_produto;";
      $res = $ocorrenciasDatabase->query($sql);
      $this->firephp->log($res->result_array());

      // Copy rows from tmp_ocorrencia_produto to ocorrencia_produto
      $sql = "";

      foreach ($res->result_array() as $key => $row)
      {
        $this->firephp->log($row);
        $sql = $sql .
               " insert into ocorrencia_produto " .
               " (id_ocorrencia,id_produto,quantidade,unidade_medida) values " .
               " ('" . $id . "','" . $row['id_produto'] . "','" . $row['quantidade'] . "','" . $row['unidade_medida'] . "');";
      }

      $this->firephp->log($sql);
      $res = $ocorrenciasDatabase->query($sql);
    }
    // Clean tmp_ocorrencia_produto
    $sql = "delete from tmp_ocorrencia_produto;";
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
        $values = $values . ",'" . $form['inputNomeNavio'] . "'";
      } else if(isset($form['inputNomeInstalacao']))  {
        $fields = $fields . ",des_instalacao";
        $values = $values . ",'" . $form['inputNomeInstalacao'] . "'";
      }

      if (isset($form['inputFuncaoNavio'])) {
        $fields = $fields . ",des_funcao_comunicante";
        $values = $values . ",'" . $form['inputFuncaoNavio'] . "'";
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
    if (isset($form["semLocalizacao"])) {
      $fields = $fields . "'N'";
    } else {
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
        $fields = $fields . ",id_municipio=" . $form['dropdownMunicipio'];
      }

      if(isset($form['inputEndereco'])) {
        $fields = $fields . ",endereco_ocorrencia='" . $form['inputEndereco'] . "'";
      }
    }


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
      $fields = $fields .",dt_primeira_obs=''";
      $fields = $fields .",hr_primeira_obs=''";
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
      $fields = $fields .",dt_ocorrencia=''";
      $fields = $fields .",hr_ocorrencia=''";
      $fields = $fields .",periodo_ocorrencia=NULL";
    }


    //
    // 3. Origem do Acidente
    //
    if (!isset($form['semOrigem'])) {
      if (isset($form["inputCompOrigem"])) {
        $fields = $fields . ",des_complemento_tipo_localizaca='" . $form["inputCompOrigem"] . "'";
      }
    } else {
      $fields = $fields . ",des_complemento_tipo_localizaca=''";
    }


    //
    // 4. Tipo de Evento
    //
    if (!isset($fomr['semEvento'])) {
      if (isset($form["inputCompEvento"])) {
        $fields = $fields . ",des_complemento_tipo_evento='" . $form["inputCompEvento"] . "'";
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

      if (isset($form['hasOleo'])) {
        if (isset($form["inputTipoSubstancia"])) {
          $fields = $fields . ",tipo_substancia='" . $form["inputTipoSubstancia"] . "'";
        } else {
          $fields = $fields . ",tipo_substancia=''";
        }
        if (isset($form["inputVolumeEstimado"])) {
          $fields = $fields . ",volume_estimado='" . $form["inputVolumeEstimado"] . "'";
        } else {
          $fields = $fields . ",volume_estimado=''";}
      }
    } else {
      $fields = $fields . ",produto_perigoso='f'";
      $fields = $fields . ",produto_nao_se_aplica='f'";
      $fields = $fields . ",produto_nao_especificado='f'";
    }


    //
    // 6. Detalhes do Acidente
    //
    if (!isset($form['semCausa'])) {
      if (isset($form["inputCausaProvavel"])) {
        $fields = $fields . ",des_causa_provavel='" . $form["inputCausaProvavel"] . "'";
      }
    } else {
      $fields = $fields . ",des_causa_provavel=''";
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

    //
    // 7. Danos Identificados
    //
    if (!isset($form['semDanos'])) {
      if (isset($form["inputCompDano"])) {
        $fields = $fields . ",des_complemento_tipo_dano_ident='" . $form["inputCompDano"] . "'";
      }

      if (isset($form["inputDesDanos"])) {
        $fields = $fields . ",des_danos='" . $form["inputDesDanos"] . "'";
      }
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
          $subfields = $subfields . "nome='" . $form["inputResponsavel"] . "'";
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
        $fields = $fields . ",nome_instituicao_atuando='" . $form["inputInfoInstituicaoNome"] . "'";
      }
      if (isset($form["inputInfoInstituicaoTelefone"])) {
        $fields = $fields . ",telefone_instituicao_atuando='" . $form["inputInfoInstituicaoTelefone"] . "'";
      }
      if (isset($form["inputCompInstituicao"])) {
        $fields = $fields . ",des_complemento_instituicao_atu='" . $form["inputCompInstituicao"] . "'";
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
      } else {
        $fields = $fields . "'N'";
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

        $fields = $fields . ",des_outras_providencias='" . $form["inputMedidasTomadas"] . "'";
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
      $fields = $fields . ",des_obs='" . $form["inputDesObs"] . "'";
    }


    //
    // 12. Identificação do Comunicante
    //
    if (isset($form["inputNomeInformante"])) {
      $fields = $fields . ",nome_comunicante='" . $form["inputNomeInformante"] . "'";
    }
    if (isset($form["inputTelInformante"])) {
      $fields = $fields . ",telefone_contato='" . $form["inputTelInformante"] . "'";
    }
    if (isset($form["inputEmailInformante"])) {
      $fields = $fields . ",email_comunicante='" . $form["inputEmailInformante"] . "'";
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
        $sql = "insert into ocorrencia_tipo_fonte_informacao (id_ocorrencia, id_tipo_fonte_informacao ) VALUES (" .
                $id . "," .  $tipoFonteInformacao .
                ");";

        $this->firephp->log($sql);

        $ocorrenciasDatabase->query($sql);
      }
    }


    //
    // Relation responsavel (in case it is a oil form)
    // Table responsavel
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
        if (isset($form['inputFuncaoNavio'])) {
          $sql = $sql . " ,des_funcao_comunicante='" . $form['inputFuncaoNavio']  . "'";
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

        if (isset($form['inputFuncaoNavio'])) {
          $fields = $fields . ",des_funcao_comunicante";
          $values = $values . ",'" . $form['inputFuncaoNavio'] . "'";
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

    //
    // Finishing the database transaction
    //
    $ocorrenciasDatabase->trans_complete();

  }

  public function convertDBtoForm($dbResult)
  {
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
    if (isset($dbResult['dt_ocorrencia_feriado'])) {
      $form['dtFeriado'] = $dbResult['dt_ocorrencia_feriado'];
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

    if($dbResult['ocorrencia_oleo']) {
      if ($infoOil['des_navio'] == NULL) {
        $form['inputNomeInstalacao'] = $infoOil['des_instalacao'];
        $form['typeOfOrigin'] = 'instalacao';
      } else {
        $form['inputNomeNavio'] = $infoOil['des_navio'];
      $form['typeOfOrigin'] = 'navio';
      }
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


    //
    // 5. Tipo do produto
    //
    $form['produtoNaoPerigoso'] = $dbResult['produto_perigoso'];
    $form['produtoNaoAplica'] = $dbResult['produto_nao_se_aplica'];
    $form['produtoNaoEspecificado'] = $dbResult['produto_nao_especificado'];

    if($dbResult['ocorrencia_oleo']) {
      $form['inputTipoSubstancia'] = $dbResult['tipo_substancia'];
      $form['inputVolumeEstimado'] = $dbResult['volume_estimado'];
    }


    //
    // 6. Detalhes do Acidente
    //
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


    //
    // 7. Danos Identificados
    //
    $query = "select ocorrencia_tipo_dano_identificado.id_tipo_dano_identificado from ocorrencia_tipo_dano_identificado where ocorrencia_tipo_dano_identificado.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoDanoIdentificado'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoDanoIdentificado'], $row['id_tipo_dano_identificado']);
    }
    $form['inputDesDanos'] = $dbResult['des_danos'];
    $form['inputCompDano'] = $dbResult['des_complemento_tipo_dano_ident'];


    //
    // 8. Identificacao Empresa/Orgao Responsavel
    //
    $form['inputResponsavel'] = $dbResult['nome'];
    $form['inputCPFCNPJ'] = $dbResult['cpf_cnpj'];
    $form['slctLicenca'] = $dbResult['des_licenca_ambiental'];


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


    //
    // 10. Ações Iniciais Tomadas
    //
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


    //
    // 11. Informações gerais sobre a Ocorrência
    //
    if($dbResult['des_obs']) {
      $form['inputDesObs'] = $dbResult['des_obs'];
    }


    //
    // 12. Informações sobre o Informante
    //
    if ($dbResult['nome_comunicante']) {
      $form['inputNomeInformante'] = $dbResult['nome_comunicante'];
    }
    if ($dbResult['telefone_contato']) {
      $form['inputTelInformante'] = $dbResult['telefone_contato'];
    }
    if ($dbResult['email_comunicante']) {
      $form['inputEmailInformante'] = $dbResult['email_comunicante'];
    }
    if($dbResult['ocorrencia_oleo']) {
      if ($infoOil['des_funcao_comunicante']) {
        $form['inputFuncaoNavio'] = $infoOil['des_funcao_comunicante'];
      }
    }


    //
    // 13. Fonte de Informação
    //
    $query = "select ocorrencia_tipo_fonte_informacao.id_tipo_fonte_informacao from ocorrencia_tipo_fonte_informacao where ocorrencia_tipo_fonte_informacao.id_ocorrencia = '" . $dbResult['id_ocorrencia'] . "'";
    $form['tipoFonteInformacao'] = array();
    foreach ($ocorrenciasDatabase->query($query)->result_array() as $row) {
      array_push($form['tipoFonteInformacao'], $row['id_tipo_fonte_informacao']);
    }


    //
    // Return the form newly created from the database
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
             " where nro_ocorrencia='" . $nro_ocorrencia . "';";
    $res = $ocorrenciasDatabase->query($query);


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
  public function getMunicipios()
  {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $result = $ocorrenciasDatabase->query("select id_municipio as id, nome as value from municipio order by value;");

    $array = array();
    foreach ($result->result_array() as $key => $value) {
      $array += array (
        $value['id']  =>  $value['value']
      );
    }
    $array += array (
      '0' => 'Sem município'
    );

    return $array;
  }

  // Returns the "UF's" stored on the database
  public function getUFs()
  {

    $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

    $result = $ocorrenciasDatabase->query("select id_uf as id, sigla as value from uf order by value;");

    $array = array();
    foreach ($result->result_array() as $key => $value) {
      $array += array (
        $value['id']  =>  $value['value']
      );
    }
    $array += array (
      '0' => 'Sem UF'
    );

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

  // // Returns the date which the accident might had happened
  // public function getDiaSemana($dateType, $id_ocorrencia)
  // {

  //   $ocorrenciasDatabase = $this->load->database('emergencias', TRUE);

  //   $result = $ocorrenciasDatabase->query("select (" . $dateType . ", 'D') from id_ocorrencia where id_ocorrencia='". $id_ocorrencia . "';");

  //   return $result->row_array();
  // }
}
