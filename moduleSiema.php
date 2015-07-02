<?php
	$value = $_GET['query'];
	$orderby = $_GET['orderby'];
	$ocorrencia = $_GET['ocorrencia'];
	$formulario = $_GET['formulario'];
	$tipo_produto = $_GET['tipo_produto'];
	$municipio_nome = $_GET['municipio_nome'];
	$id = $_GET['id'];
	$uf = $_GET['uf'];
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];

	header('content-type: application/json; charset=utf-8');
	header("access-control-allow-origin: *");

	$HOST = "localhost";
	// $HOST = "10.1.8.45";
	$USER = "siema";
	$PASSWORD = "siemahmlg";
	$DATABASE = "siema";
	// $USER = "emergencias";
	// $PASSWORD = "3m3rg3nc14s";
	// $DATABASE = "emergencias_homolog";
	$PORT = "5432";


	$cdb = pg_connect("host=$HOST port=$PORT dbname=$DATABASE user=$USER password=$PASSWORD");
	// $query = 'SELECT * FROM '. $value ;


	switch ($value) {
		case 'vw_ocorrencia':
			$query = 'SELECT * FROM '. $value .' ORDER BY dt_registro';
			break;

		case 'carregar_ocorrencia':
			$query = '
				SELECT ocorrencia.*,
							 ST_AsLatLonText(pon.shape, \'D°M\'\'S.SSS\') as coordinate, ST_AsGeoJson(pon.shape) as coordinate_json,
							 array(select id_tipo_localizacao from ocorrencia_tipo_localizacao where id_ocorrencia=ocorrencia.id_ocorrencia) as origem,
							 array(select id_tipo_evento from ocorrencia_tipo_evento where id_ocorrencia=ocorrencia.id_ocorrencia) as evento,
							 array(select id_tipo_dano_identificado from ocorrencia_tipo_dano_identificado where id_ocorrencia=ocorrencia.id_ocorrencia) as ambiente,
							 array(select id_instituicao_atuando_local from ocorrencia_instituicao_atuando_local where id_ocorrencia=ocorrencia.id_ocorrencia) as instituicao,
							 array(select id_tipo_fonte_informacao from ocorrencia_tipo_fonte_informacao where id_ocorrencia=ocorrencia.id_ocorrencia) as fonte,
							 (select max(desc_outras_fontes) from ocorrencia_tipo_fonte_informacao where id_ocorrencia=ocorrencia.id_ocorrencia) as desc_outras_fontes,
							 res.nome as nome_responsavel, res.cpf_cnpj as cpf_cnpj_responsavel, res.des_licenca_ambiental as licenca_responsavel,
							 det_ocor.des_navio, det_ocor.des_instalacao, det_ocor.des_funcao_comunicante, to_char(now(),\'YYYY-MM-DD HH24:MI:SS\') as hora_atual
				FROM ocorrencia
						 LEFT JOIN ocorrencia_pon pon on (pon.id_ocorrencia = ocorrencia.id_ocorrencia)
						 LEFT JOIN responsavel res on (res.id_responsavel = ocorrencia.id_responsavel)
		 				 LEFT JOIN detalhamento_ocorrencia det_ocor on (det_ocor.id_ocorrencia = ocorrencia.id_ocorrencia)
				WHERE nro_ocorrencia=\'' . $ocorrencia . '\'
				LIMIT 1';
			break;

		case 'ufs':
			$query = 'SELECT id_uf, sigla, estado FROM uf ORDER BY sigla;';
			break;

		case 'municipios':
			$query = 'SELECT cod_ibge, nome FROM municipio WHERE id_uf=\'' . $uf . '\' ORDER BY nome;';
			break;

		case 'municipio_id':
			$query = 'SELECT cod_ibge FROM municipio WHERE nome=\'' . $municipio_nome . '\';';
			break;

		case 'bacias':
			$query = 'SELECT id_bacia_sedimentar, nome FROM bacia_sedimentar ORDER BY nome;';
			break;

		case 'origens':
			$query = 'SELECT id_tipo_localizacao, des_tipo_localizacao FROM tipo_localizacao ORDER BY id_tipo_localizacao;';
			break;

		case 'eventos':
			$query = 'SELECT id_tipo_evento, nome FROM tipo_evento ORDER BY id_tipo_evento;';
			break;

		case 'ambientes':
			$query = 'SELECT id_tipo_dano_identificado, nome FROM tipo_dano_identificado ORDER BY id_tipo_dano_identificado;';
			break;

		case 'instituicoes':
			$query = 'SELECT id_instituicao_atuando_local, nome FROM instituicao_atuando_local ORDER BY id_instituicao_atuando_local;';
			break;

		case 'fontes':
			$query = 'SELECT id_tipo_fonte_informacao, nome FROM tipo_fonte_informacao ORDER BY id_tipo_fonte_informacao;';
			break;

		case 'produtos_onu':
			$query = 'SELECT id_produto_onu as id, nome, num_onu, classe_risco FROM produto_onu ORDER BY nome;';
			break;

		case 'verificar_marcador':
			$query = 'SELECT ST_Intersects(br_mar.geom,ST_SetSRID(ST_MakePoint(' . $lng . ',' . $lat . '),4674)) as intersects FROM br_mar;';
			break;

		case 'adicionar_tipo':
			$query = 'INSERT INTO produto_outro (nome) VALUES (\'' . $tipo_produto . '\');
								SELECT id_produto_outro as id, nome FROM produto_outro ORDER BY nome;';
			break;

		case 'produtos_cadastrados':
			$query = 'SELECT op.id_produto_onu, op.quantidade, op.unidade_medida, op.id_produto_outro, ponu.nome as nome_onu, ponu.num_onu, ponu.classe_risco, poutro.nome as nome_outro
								FROM ocorrencia_produto op
								LEFT JOIN produto_onu ponu ON (ponu.id_produto_onu=op.id_produto_onu)
								LEFT JOIN produto_outro poutro ON (poutro.id_produto_outro=op.id_produto_outro)
								WHERE id_ocorrencia=\'' . $id . '\';';
			break;

		case 'produtos_outros':
			$query = 'SELECT id_produto_outro as id, nome FROM produto_outro ORDER BY nome;';
			break;

		case 'atualizar_ocorrencia':

			$form = json_decode($formulario);


			//
			// Busca o ID da ocorrencia a ser atualizada, baseando-se no numero da ocorrencia
			//
			$query = 'SELECT id_ocorrencia FROM ocorrencia WHERE nro_ocorrencia=\'' . $form->nro_ocorrencia . '\'';
			$res_id_ocorrencia = pg_query($query);
			$form->id_ocorrencia = pg_fetch_row($res_id_ocorrencia)[0];


			//
			// Inicia a transação no banco.
			//
			pg_query('BEGIN');


			//
			//
			//Grava a geometria de ponto da ocorrência
			//
			//
			$query = 'UPDATE ocorrencia_pon SET shape=ST_SetSRID(ST_MakePoint(' . $form->acidente->geometry->coordinates[0] . ',' . $form->acidente->geometry->coordinates[1] . '),4674) WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\'';
			$res_localizacao = pg_query($query);



			//
			//Cria a query para atualização de uma ocorrência
			//
			$query_ocorrencia = 'UPDATE ocorrencia SET ';



			//
			//
			// Localização
			//
			//
			if($form->localizacao->uf) {
				$query_ocorrencia = $query_ocorrencia . 'id_uf=' . $form->localizacao->uf . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'id_uf=null,';
			}

			if($form->localizacao->municipio) {
			$query_ocorrencia = $query_ocorrencia . 'id_municipio=' . $form->localizacao->municipio . ',';
			} else {
			$query_ocorrencia = $query_ocorrencia . 'id_municipio=null,';
			}

			if ($form->localizacao->oceano && $form->localizacao->bacia) {
				$query_ocorrencia = $query_ocorrencia . 'id_bacia_sedimentar=' . $form->localizacao->bacia . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'id_bacia_sedimentar=null,';
			}

			$query_ocorrencia = $query_ocorrencia . 'endereco_ocorrencia=\'' . $form->localizacao->endereco . '\',';



			//
			//
			// Data e Hora do Acidente
			//
			//
			if (!$form->datas->semObservacao) {

				$obsPeriodo = $form->datas->obsPeriodo ? '\'' . $form->datas->obsPeriodo . '\'' : 'null';

				$query_ocorrencia = $query_ocorrencia . 'dt_primeira_obs=\'' . $form->datas->diaObservacao . '\',' .
																								'hr_primeira_obs=\'' . $form->datas->horaObservacao . '\',' .
																								'periodo_primeira_obs=' . $obsPeriodo . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'dt_primeira_obs=null,' .
																								'hr_primeira_obs=null,' .
																								'periodo_primeira_obs=null,';
			}

			if (!$form->datas->semIncidente) {

				$incPeriodo = $form->datas->incPeriodo ? '\'' . $form->datas->incPeriodo . '\'' : 'null';

				$query_ocorrencia = $query_ocorrencia . 'dt_ocorrencia=\'' . $form->datas->diaIncidente . '\',' .
																								'hr_ocorrencia=\'' . $form->datas->horaIncidente . '\',' .
																								'periodo_ocorrencia=' . $incPeriodo . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'dt_ocorrencia=null,' .
																								'hr_ocorrencia=null,' .
																								'periodo_ocorrencia=null,';
			}



			//
			//
			// Origem do Acidente
			//
			//
			if (!$form->origem->semOrigem) {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_localizaca=\'' . pg_escape_string($form->origem->complementar) . '\',';

				$query = 'DELETE FROM ocorrencia_tipo_localizacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_origem = pg_query($query);

				if($res_origem) {
					$query = 'INSERT INTO ocorrencia_tipo_localizacao (id_ocorrencia,id_tipo_localizacao) VALUES ';

					foreach ($form->origem->origens as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

      				$query = trim($query, ",");
					$res_origem = pg_query($query);
				}

			} else {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_localizaca=null,';

				$query = 'DELETE FROM ocorrencia_tipo_localizacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_origem = pg_query($query);
			}

			if ($form->oleo) {
				if (!$form->origem->semOleoOrigem) {
					$query = 'SELECT des_navio, des_instalacao FROM detalhamento_ocorrencia WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_origem_oleo = pg_query($query);
					$detalhamento_ocorrencia = pg_fetch_row($res_origem_oleo);

					if($detalhamento_ocorrencia) {
						$query = 'UPDATE detalhamento_ocorrencia SET des_navio=\'' . pg_escape_string($form->origem->navio) . '\', des_instalacao=\'' . pg_escape_string($form->origem->instalacao) . '\' WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
						$res_origem_oleo = pg_query($query);
					} else {
						$query = 'INSERT INTO detalhamento_ocorrencia (id_ocorrencia,des_navio,des_instalacao) VALUES (\'' . $form->id_ocorrencia . '\',\''. pg_escape_string($form->origem->navio) . '\',\'' . pg_escape_string($form->origem->instalacao) . '\');';
						$res_origem_oleo = pg_query($query);
					}
				} else {
					$query = 'DELETE FROM detalhamento_ocorrencia WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_origem_oleo = pg_query($query);
				}
			}



			//
			//
			// Tipo de Evento
			//
			//
			if (!$form->evento->semEvento) {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_evento=\'' . pg_escape_string($form->evento->complementar) . '\',';

				$query = 'DELETE FROM ocorrencia_tipo_evento WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_evento = pg_query($query);

				if($res_evento) {
					$query = 'INSERT INTO ocorrencia_tipo_evento (id_ocorrencia,id_tipo_evento) VALUES ';

					foreach ($form->evento->eventos as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

      				$query = trim($query, ",");
					$res_evento = pg_query($query);
				}

			} else {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_evento=null,';

				$query = 'DELETE FROM ocorrencia_tipo_evento WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_evento = pg_query($query);
			}



			//
			//
			// Tipo de Produtos
			//
			//
			// Deleta todos os produtos anteriores relacionados a esta ocorrencia
			$query = 'DELETE FROM ocorrencia_produto WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\'';
			$res_produtos = pg_query($query);

			if (!$form->produtos->semProduto && $res_produtos && ($form->produtos->produtos_onu || $form->produtos->produtos_outros)){

				$query = 'INSERT INTO ocorrencia_produto (id_ocorrencia,id_produto_onu, id_produto_outro, quantidade, unidade_medida) VALUES ';

				// Insere todos os produtos onu
				foreach ($form->produtos->produtos_onu as $key => $value) {
					$query = $query . ' (\'' . $form->id_ocorrencia . '\',\'' . $value->id . '\',null,\''. $value->qtd . '\',\'' . $value->uni . '\'),';
				}

				// Insere todos os produtos não onu
				foreach ($form->produtos->produtos_outros as $key => $value) {
					$query = $query . ' (\'' . $form->id_ocorrencia . '\',null,\'' . $value->id . '\',\''. $value->qtd . '\',\'' . $value->uni . '\'),';
				}

      			$query = trim($query, ",");
				// print_r($query);

				$res_produtos = pg_query($query);
			}

			if (!$form->produtos->naoEspecificado) {
				$nao_especificado = 'null';
			} else {
				$nao_especificado = $form->produtos->naoEspecificado == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->naoAplica) {
				$nao_aplica = 'null';
			} else {
				$nao_aplica = $form->produtos->naoAplica == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->naoClassificado) {
				$nao_classificado = 'null';
			} else {
				$nao_classificado = $form->produtos->naoClassificado == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->valor_substancia) {
				$valor_substancia = 'null';
			} else {
				$valor_substancia = $form->produtos->valor_substancia;
			}

			$query_ocorrencia = $query_ocorrencia . 'tipo_substancia=\'' . pg_escape_string($form->produtos->tipo_substancia) . '\',' .
																							'volume_estimado=' . $valor_substancia . ',' .
																							'produto_nao_especificado=' . $nao_especificado . ',' .
																							'produto_nao_se_aplica=' . $nao_aplica . ',' .
																							'produto_perigoso=' . $nao_classificado . ',';


			//
			//
			// Detalhs do Acidente
			//
			//
			if (!$form->detalhes->semDetalhe) {
				$query_ocorrencia = $query_ocorrencia . 'des_causa_provavel=\'' . pg_escape_string($form->detalhes->causa) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'des_causa_provavel=null,';
			}

			if ($form->oleo) {
				$situacao = $form->detalhes->situacao ? '\'' . $form->detalhes->situacao . '\'' : 'null';
				$query_ocorrencia = $query_ocorrencia . 'situacao_atual_descarga=' . $situacao . ',';
			}



			//
			//
			// Ocorrências e/ou Ambientes Atingidos
			//
			//
			if (!$form->ambiente->semAmbientes) {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_dano_ident=\'' . pg_escape_string($form->ambiente->complementar) . '\',';

				$query = 'DELETE FROM ocorrencia_tipo_dano_identificado WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_ambiente = pg_query($query);

				if($res_ambiente) {
					$query = 'INSERT INTO ocorrencia_tipo_dano_identificado (id_ocorrencia,id_tipo_dano_identificado) VALUES ';

					foreach ($form->ambiente->ambientes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

      				$query = trim($query, ",");
					$res_ambiente = pg_query($query);
				}

			} else {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_tipo_dano_ident=null,';

				$query = 'DELETE FROM ocorrencia_tipo_dano_identificado WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_ambiente = pg_query($query);
			}



			//
			//
			// Identificação da Empresa/Responsável
			//
			//
	    if (!$form->empresa->semEmpresa) {

    		$query = 'SELECT id_responsavel FROM ocorrencia WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
			$res_empresa = pg_query($query);
			$responsavel_id = pg_fetch_row($res_empresa)[0];

    		if ($responsavel_id) {
				
				$query = 'UPDATE responsavel SET nome=\'' . $form->empresa->nome 
											. '\',cpf_cnpj=\'' . $form->empresa->cadastro 
											. '\',des_licenca_ambiental=\'' . $form->empresa->licencaAmbiental . '\' WHERE id_responsavel=\'' . $responsavel_id . '\'';
				$res_empresa = pg_query($query);

    		} else {

				$query = 'INSERT INTO responsavel (nome, cpf_cnpj, des_licenca_ambiental) VALUES (\'' . $form->empresa->nome . '\',\'' .
																																																$form->empresa->cadastro . '\',\'' .
																																																$form->empresa->licencaAmbiental . '\');';
				$res_empresa = pg_query($query);

				// print_r($res_empresa);

				if ($res_empresa) {
					$query = 'SELECT id_responsavel FROM responsavel WHERE cpf_cnpj=\'' . $form->empresa->cadastro . '\' AND ' .
					                                                     	'nome=\'' . $form->empresa->nome . '\' AND ' .
					                                                     	'des_licenca_ambiental=\'' . $form->empresa->licencaAmbiental . '\' ORDER BY id_responsavel DESC';
					$res_empresa = pg_query($query);
					$responsavel_id = pg_fetch_row($res_empresa)[0];

					$query_ocorrencia = $query_ocorrencia . 'id_responsavel=' . $responsavel_id . ', informacao_responsavel=\'T\',';
					$res_empresa = pg_query($query);
				}
			}
	    } else {

			// $query = 'SELECT id_responsavel FROM ocorrencia WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\'';
			// $res_empresa = pg_query($query);
			// $responsavel_id = pg_fetch_row($res_empresa)[0];

			// if ($responsavel_id) {
			// 	$query_responsavel = 'DELETE FROM responsavel WHERE id_responsavel=\'' . $responsavel_id .  '\';';
			// 	$res_empresa = pg_query($query_responsavel);
			// }

			// if ($res_empresa) {
				$query_ocorrencia = $query_ocorrencia . 'id_responsavel=null, informacao_responsavel=\'N\',';
				$res_empresa = pg_query($query);
			// }
	    }



			//
			//
			// Instituição/Empresa Atuando no Local
			//
			//
			if (!$form->instituicao->semInstituicao) {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_instituicao_atu=\'' . pg_escape_string($form->instituicao->complementar) . '\',' .
																								'nome_instituicao_atuando=\'' . pg_escape_string($form->instituicao->responsavel) . '\',' .
																								'telefone_instituicao_atuando=\'' . $form->instituicao->telefone . '\',';

				$query = 'DELETE FROM ocorrencia_instituicao_atuando_local WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_ambiente = pg_query($query);

				if($res_ambiente) {
					$query = 'INSERT INTO ocorrencia_instituicao_atuando_local (id_ocorrencia,id_instituicao_atuando_local) VALUES ';

					foreach ($form->instituicao->instituicoes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

      				$query = trim($query, ",");
					$res_ambiente = pg_query($query);
				}

			} else {
				$query_ocorrencia = $query_ocorrencia . 'des_complemento_instituicao_atu=null,' .
																								'nome_instituicao_atuando=null,' .
																								'telefone_instituicao_atuando=null,';

				$query = 'DELETE FROM ocorrencia_instituicao_atuando_local WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_ambiente = pg_query($query);
			}



			//
			//
			// Ações Iniciais Tomadas
			//
			//
			if (!$form->acoes->semAcoes) {

				$acionado = $form->acoes->planoIndividual ? 'S' : 'N';
				$outrasProvidencias = $form->acoes->outrasProvidencias ? 'S' : 'N';

				$query_ocorrencia = $query_ocorrencia . 'plano_emergencia=\'' . $form->acoes->plano . '\',' .
																								'plano_emergencia_acionado=\'' . $acionado. '\',' .
																								'iniciados_outras_providencias=\'' . $outrasProvidencias . '\',' .
																								'des_outras_providencias=\'' . pg_escape_string($form->acoes->outrasProvidenciasText) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'plano_emergencia=null,' .
																								'plano_emergencia_acionado=\'N\',' .
																								'iniciados_outras_providencias=\'N\',' .
																								'des_outras_providencias=null,';
			}


			//
			//
			// Informações Gerais Sobre a Ocorrência
			//
			//
			$query_ocorrencia = $query_ocorrencia .  'des_obs=\'' . pg_escape_string($form->gerais->text) . '\',';



			//
			//
			// Identificação do Comunicante
			//
			//
			$query_ocorrencia = $query_ocorrencia . 'nome_comunicante=\'' . pg_escape_string($form->comunicante->nome) . '\',' .
																							'des_instituicao_empresa=\'' . pg_escape_string($form->comunicante->empresa) . '\','.
																							'des_funcao_comunicante=\'' . pg_escape_string($form->comunicante->funcao) . '\','.
																							'telefone_contato=\'' . $form->comunicante->telefone . '\','.
																							'email_comunicante=\'' . pg_escape_string($form->comunicante->email) . '\',';



			//
			//
      // Adicionar Arquivo
			//
			//



			//
			//
      // Fonte de Informações
			//
			//
			if ($form->fonte) {
				$query = 'DELETE FROM ocorrencia_tipo_fonte_informacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_fonte = pg_query($query);

				if($res_fonte && (sizeof($form->fonte->fontes) > 0)) {
					$query = 'INSERT INTO ocorrencia_tipo_fonte_informacao (id_ocorrencia,id_tipo_fonte_informacao,desc_outras_fontes) VALUES ';

					foreach ($form->fonte->fontes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . ',\'' . pg_escape_string($form->fonte->complementar) . '\'),';
					}

      				$query = trim($query, ",");
					$res_fonte = pg_query($query);
				}
			} else {
				$query = 'DELETE FROM ocorrencia_tipo_fonte_informacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
				$res_fonte = pg_query($query);
			}


			//
			//
			// Em caso de um formulario sendo atualizado para validação, inserir o campo validado informado.
			//
			//
			if ($form->validador) {
				$validado = $form->validador->validado ? "S" : "N";
				$query_ocorrencia = $query_ocorrencia . 'validado=\'' . $validado . '\',';
			}


			//
			//
			// Finalizando a construção da query para inserção da ocorrencia
			//
			//
			$query_ocorrencia = trim($query_ocorrencia, ",");
			$query_ocorrencia = $query_ocorrencia . ' WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
			// print_r($query_ocorrencia);
			$res_ocorrencia = pg_query($query_ocorrencia);



			//
			//
			// Caso todas as queries anteriores sejam executadas com sucesso, realiza a inserção da query de ocorrencia. Caso contrario, rollback
			//
			//
			$response = array();
      		if ($res_localizacao && $res_ocorrencia && $res_origem && (!$form->oleo || $res_origem_oleo) && $res_evento && $res_ambiente && $res_empresa && $res_fonte) {

		 		pg_query("COMMIT") or die("Transaction commit failed\n");
				pg_close($cdb);
				array_push($response, true);

		 	} else {
				pg_query("ROLLBACK") or die("Transaction rollback failed\n");
				pg_close($cdb);
				array_push($response, false);
			}

			if (!$res_ocorrencia) {
				array_push($response, "erro na query ocorrencia");				
			}
			if (!$res_localizacao) {
				array_push($response, "erro na query localizacao");				
			}
			if (!$res_origem) {
				array_push($response, "erro na query origem");				
			}
			if (!$res_evento) {
				array_push($response, "erro na query evento");				
			}
			if (!$res_ambiente) {
				array_push($response, "erro na query ambiente");				
			}
			if (!$res_empresa) {
				array_push($response, "erro na query empresa");				
			}
			if (!$res_fonte) {
				array_push($response, "erro na query fonte");				
			}
			if (!$res_origem_oleo) {
				array_push($response, "erro na query origem_oleo");				
			}

			print_r(json_encode($response));
			exit();

			//
			//
			// Finaliza a conexão ao banco e finaliza o pedido
			//
			//
			// pg_close($cdb);
			// exit();

			// print_r($form->acidente->geometry->coordinates[1]);
			// print_r(json_encode($form->origem->origens));
			// exit();

			// $query = 'SELECT ST_Intersects(br_mar.geom,ST_SetSRID(ST_MakePoint(' . $lng . ',' . $lat . '),4674)) as intersects FROM br_mar;';
			break;

		case 'criar_ocorrencia':

			$form = json_decode($formulario);


			//
			// Inicia a transação no banco.
			//
			pg_query('BEGIN');



			//
			//Cria a query para atualização de uma ocorrência
			//
			$query_ocorrencia = 'INSERT INTO ocorrencia (id_uf,id_municipio,id_bacia_sedimentar,endereco_ocorrencia,' .
																'dt_primeira_obs,hr_primeira_obs,periodo_primeira_obs,dt_ocorrencia,hr_ocorrencia,periodo_ocorrencia,' .
																'des_complemento_tipo_localizaca,' .
																'des_complemento_tipo_evento,' .
																'tipo_substancia,volume_estimado,produto_nao_especificado,produto_nao_se_aplica,produto_perigoso,' .
																'des_causa_provavel,situacao_atual_descarga,' .
																'des_complemento_tipo_dano_ident,' .
																'informacao_responsavel,id_responsavel,' .
																'des_complemento_instituicao_atu,nome_instituicao_atuando,telefone_instituicao_atuando,' .
																'plano_emergencia,plano_emergencia_acionado,iniciados_outras_providencias,des_outras_providencias,' .
																'des_obs,' .
																'nome_comunicante,des_instituicao_empresa,des_funcao_comunicante,telefone_contato,email_comunicante,' .
																'ocorrencia_oleo,nro_ocorrencia,tipo_comunicado,cpf_contato' .
																') VALUES (';


			//
			//
			// Localização
			//
			//
			if($form->localizacao->uf) {
				$query_ocorrencia = $query_ocorrencia . '' . $form->localizacao->uf . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}

			if($form->localizacao->municipio) {
			$query_ocorrencia = $query_ocorrencia . '' . $form->localizacao->municipio . ',';
			} else {
			$query_ocorrencia = $query_ocorrencia . 'null,';
			}

			if ($form->localizacao->oceano && $form->localizacao->bacia) {
				$query_ocorrencia = $query_ocorrencia . '' . $form->localizacao->bacia . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}

			$query_ocorrencia = $query_ocorrencia . '\'' . $form->localizacao->endereco . '\',';



			//
			//
			// Data e Hora do Acidente
			//
			//
			if (!$form->datas->semObservacao) {

				$obsPeriodo = $form->datas->obsPeriodo ? '\'' . $form->datas->obsPeriodo . '\'' : 'null';

				$query_ocorrencia = $query_ocorrencia . '\'' . $form->datas->diaObservacao . '\',' .
																								'\'' . $form->datas->horaObservacao . '\',' .
																								'' . $obsPeriodo . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,' .
																								'null,' .
																								'null,';
			}

			if (!$form->datas->semIncidente) {

				$incPeriodo = $form->datas->incPeriodo ? '\'' . $form->datas->incPeriodo . '\'' : 'null';

				$query_ocorrencia = $query_ocorrencia . '\'' . $form->datas->diaIncidente . '\',' .
																								'\'' . $form->datas->horaIncidente . '\',' .
																								'' . $incPeriodo . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,' .
																								'null,' .
																								'null,';
			}



			//
			//
			// Origem do Acidente
			//
			//
			if (!$form->origem->semOrigem) {
				$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->origem->complementar) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}


			//
			//
			// Tipo de Evento
			//
			//
			if (!$form->evento->semEvento) {
				$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->evento->complementar) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}



			//
			//
			// Tipo de Produtos
			//
			//
			if (!$form->produtos->naoEspecificado) {
				$nao_especificado = 'null';
			} else {
				$nao_especificado = $form->produtos->naoEspecificado == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->naoAplica) {
				$nao_aplica = 'null';
			} else {
				$nao_aplica = $form->produtos->naoAplica == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->naoClassificado) {
				$nao_classificado = 'null';
			} else {
				$nao_classificado = $form->produtos->naoClassificado == 'S' ? 'true' : 'false';
			}

			if (!$form->produtos->valor_substancia) {
				$valor_substancia = 'null';
			} else {
				$valor_substancia = $form->produtos->valor_substancia;
			}

			$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->produtos->tipo_substancia) . '\',' .
																							'' . $valor_substancia . ',' .
																							'' . $nao_especificado . ',' .
																							'' . $nao_aplica . ',' .
																							'' . $nao_classificado . ',';


			//
			//
			// Detalhs do Acidente
			//
			//
			if (!$form->detalhes->semDetalhe) {
				$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->detalhes->causa) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}

			if ($form->oleo) {
				$situacao = $form->detalhes->situacao ? '\'' . $form->detalhes->situacao . '\'' : 'null';
				$query_ocorrencia = $query_ocorrencia . $situacao . ',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}



			//
			//
			// Ocorrências e/ou Ambientes Atingidos
			//
			//
			if (!$form->ambiente->semAmbientes) {
				$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->ambiente->complementar) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,';
			}



			//
			//
			// Identificação da Empresa/Responsável
			//
			//
	    if (!$form->empresa->semEmpresa) {
				$query_ocorrencia = $query_ocorrencia . '\'T\',';
				$query = 'INSERT INTO responsavel (nome, cpf_cnpj, des_licenca_ambiental) VALUES (\'' . pg_escape_string($form->empresa->nome) . '\',\'' .
																														$form->empresa->cadastro . '\',\'' .
																														$form->empresa->licencaAmbiental . '\');';
				$res_empresa = pg_query($query);

				// print_r($res_empresa);

				if ($res_empresa) {
					// $oid = pg_last_oid($res_empresa);
					// print_r($oid);
					$query = 'SELECT id_responsavel FROM responsavel WHERE cpf_cnpj=\'' . $form->empresa->cadastro . '\' AND ' .
					                                                     	'nome=\'' . pg_escape_string($form->empresa->nome) . '\' AND ' .
					                                                     	'des_licenca_ambiental=\'' . $form->empresa->licencaAmbiental . '\' ORDER BY id_responsavel DESC';
					$res_empresa = pg_query($query);
					$responsavel_id = pg_fetch_row($res_empresa)[0];

					$query_ocorrencia = $query_ocorrencia . '' . $responsavel_id . ',';
					$res_empresa = pg_query($query);
				}
	    } else {
					$query_ocorrencia = $query_ocorrencia . '\'N\',null,';
					$res_empresa = true;
	    }



			//
			//
			// Instituição/Empresa Atuando no Local
			//
			//
			if (!$form->instituicao->semInstituicao) {
				$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->instituicao->complementar) . '\',' .
																								'\'' . pg_escape_string($form->instituicao->responsavel) . '\',' .
																								'\'' . str_replace(array("(",")"," ","-"),"",$form->instituicao->telefone) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,' .
																								'null,' .
																								'null,';
			}



			//
			//
			// Ações Iniciais Tomadas
			//
			//
			if (!$form->acoes->semAcoes) {

				$acionado = $form->acoes->planoIndividual ? 'S' : 'N';
				$outrasProvidencias = $form->acoes->outrasProvidencias ? 'S' : 'N';

				$query_ocorrencia = $query_ocorrencia . '\'' . $form->acoes->plano . '\',' .
																								'\'' . $acionado. '\',' .
																								'\'' . $outrasProvidencias . '\',' .
																								'\'' . pg_escape_string($form->acoes->outrasProvidenciasText) . '\',';
			} else {
				$query_ocorrencia = $query_ocorrencia . 'null,' .
																								'\'N\',' .
																								'\'N\',' .
																								'null,';
			}


			//
			//
			// Informações Gerais Sobre a Ocorrência
			//
			//
			$query_ocorrencia = $query_ocorrencia .  '\'' . pg_escape_string($form->gerais->text) . '\',';



			//
			//
			// Identificação do Comunicante
			//
			//
			$query_ocorrencia = $query_ocorrencia . '\'' . pg_escape_string($form->comunicante->nome) . '\',' .
																							'\'' . pg_escape_string($form->comunicante->empresa) . '\','.
																							'\'' . pg_escape_string($form->comunicante->funcao) . '\','.
																							'\'' . str_replace(array("(",")"," ","-"),"",$form->comunicante->telefone) . '\','.
																							'\'' . pg_escape_string($form->comunicante->email) . '\',';



			//
			//
      // Adicionar Arquivo
			//
			//



			//
			//
      // Fonte de Informações
			//
			//
      // $form->fonte->fontes;
      // $form->fonte->complementar;



			//
			//
			// Finalizando a construção da query para inserção da ocorrencia
			//
			//
      $oleo = $form->oleo ? "S" : "N";
      $query_ocorrencia = $query_ocorrencia . '\'' . $oleo . '\',\'' . $form->nro_ocorrencia . '\',\'' . $form->tipo_comunicante . '\',\'' . $form->cpf_contato . '\');';
      // print_r($query_ocorrencia);
      $res_ocorrencia = pg_query($query_ocorrencia);

			// $res_localizacao = null;
			// $res_origem = null;
			// $res_origem_oleo = null;
			// $res_evento = null;
			// $res_ambiente = null;
			// $res_empresa = null;

      if($res_ocorrencia) {


				//
				// Busca o ID da ocorrencia a ser atualizada, baseando-se no numero da ocorrencia, para criar as relações
				//
				$query = 'SELECT id_ocorrencia FROM ocorrencia WHERE nro_ocorrencia=\'' . $form->nro_ocorrencia . '\';';
				$res_id_ocorrencia = pg_query($query);
				$form->id_ocorrencia = pg_fetch_row($res_id_ocorrencia)[0];
				// print_r($query);


				//
				//
				//Grava a geometria de ponto da ocorrência
				//
				//
				$query = 'INSERT INTO ocorrencia_pon (id_ocorrencia, shape) VALUES (' . $form->id_ocorrencia . ',ST_SetSRID(ST_MakePoint(' . $form->acidente->geometry->coordinates[0] . ',' . $form->acidente->geometry->coordinates[1] . '),4674));';
				$res_localizacao = pg_query($query);


				//
				//
				// Origem do Acidente - Criação de relações
				//
				//
				if (!$form->origem->semOrigem) {

					$query = 'INSERT INTO ocorrencia_tipo_localizacao (id_ocorrencia,id_tipo_localizacao) VALUES ';

					foreach ($form->origem->origens as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

  					$query = trim($query, ",");
					$res_origem = pg_query($query);

				} else {
					$query = 'DELETE FROM ocorrencia_tipo_localizacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_origem = pg_query($query);
				}

				if ($form->oleo) {
					if (!$form->origem->semOleoOrigem) {
							$query = 'INSERT INTO detalhamento_ocorrencia (id_ocorrencia,des_navio,des_instalacao) VALUES (\'' . $form->id_ocorrencia . '\',\''. pg_escape_string($form->origem->navio) . '\',\'' . pg_escape_string($form->origem->instalacao) . '\');';
							$res_origem_oleo = pg_query($query);
					} else {
						$res_origem_oleo = true;
					}
				}


				//
				//
				// Tipo de Evento - Criação de relações
				//
				//
				if (!$form->evento->semEvento) {

					$query = 'INSERT INTO ocorrencia_tipo_evento (id_ocorrencia,id_tipo_evento) VALUES ';

					foreach ($form->evento->eventos as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

      				$query = trim($query, ",");
					$res_evento = pg_query($query);
				} else {
					$query = 'DELETE FROM ocorrencia_tipo_evento WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_evento = pg_query($query);
				}


				//
				//
				// Tipo de Produtos - Criação de relações
				//
				//
				if (!$form->produtos->semProduto && ($form->produtos->produtos_onu || $form->produtos->produtos_outros)) {

					$query = 'INSERT INTO ocorrencia_produto (id_ocorrencia,id_produto_onu, id_produto_outro, quantidade, unidade_medida) VALUES ';

					// Insere todos os produtos onu
					foreach ($form->produtos->produtos_onu as $key => $value) {
						$query = $query . ' (\'' . $form->id_ocorrencia . '\',\'' . $value->id . '\',null,\''. $value->qtd . '\',\'' . $value->uni . '\'),';
					}

					// Insere todos os produtos não onu
					foreach ($form->produtos->produtos_outros as $key => $value) {
						$query = $query . ' (\'' . $form->id_ocorrencia . '\',null,\'' . $value->id . '\',\''. $value->qtd . '\',\'' . $value->uni . '\'),';
					}

	      			$query = trim($query, ",");
					// print_r($query);

					$res_produtos = pg_query($query);
				}


				//
				//
				// Ocorrências e/ou Ambientes Atingidos - Criação de relações
				//
				//
				if (!$form->ambiente->semAmbientes) {
					$query = 'INSERT INTO ocorrencia_tipo_dano_identificado (id_ocorrencia,id_tipo_dano_identificado) VALUES ';

					foreach ($form->ambiente->ambientes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

	    			$query = trim($query, ",");
					$res_ambiente = pg_query($query);

				} else {
					$query = 'DELETE FROM ocorrencia_tipo_dano_identificado WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_ambiente = pg_query($query);
				}


				//
				//
				// Instituição/Empresa Atuando no Local
				//
				//
				if (!$form->instituicao->semInstituicao) {
					$query = 'INSERT INTO ocorrencia_instituicao_atuando_local (id_ocorrencia,id_instituicao_atuando_local) VALUES ';

					foreach ($form->instituicao->instituicoes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . '),';
					}

    				$query = trim($query, ",");
					$res_ambiente = pg_query($query);

				} else {
					$query = 'DELETE FROM ocorrencia_instituicao_atuando_local WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_ambiente = pg_query($query);
				}


				//
				//
	      		// Fonte de Informações
				//
				//
				if ($form->fonte && (sizeof($form->fonte->fontes) > 0)) {
					$query = 'INSERT INTO ocorrencia_tipo_fonte_informacao (id_ocorrencia,id_tipo_fonte_informacao,desc_outras_fontes) VALUES ';

					foreach ($form->fonte->fontes as $key => $value) {
						$query = $query . '(' . $form->id_ocorrencia . ',' . $value . ',\'' . pg_escape_string($form->fonte->complementar) . '\'),';
					}

      				$query = trim($query, ",");
					// print_r($query);
					$res_fonte = pg_query($query);
				} else {
					$query = 'DELETE FROM ocorrencia_tipo_fonte_informacao WHERE id_ocorrencia=\'' . $form->id_ocorrencia . '\';';
					$res_fonte = pg_query($query);
				}

      }




			//
			//
			// Caso todas as queries anteriores sejam executadas com sucesso, realiza a inserção da query de ocorrencia. Caso contrario, rollback
			//
			//
			$response = array();
      		if ($res_ocorrencia && $res_localizacao && $res_origem && (!$form->oleo || $res_origem_oleo) && $res_evento && $res_ambiente && $res_empresa && $res_fonte) {

		 		pg_query("COMMIT") or die("Transaction commit failed\n");
				pg_close($cdb);
				array_push($response, true);
		 	} else {
				pg_query("ROLLBACK") or die("Transaction rollback failed\n");
				pg_close($cdb);
				array_push($response, false);
			}

			if (!$res_ocorrencia) {
				array_push($response, "erro na query ocorrencia");				
			}
			if (!$res_localizacao) {
				array_push($response, "erro na query localizacao");				
			}
			if (!$res_origem) {
				array_push($response, "erro na query origem");				
			}
			if (!$res_evento) {
				array_push($response, "erro na query evento");				
			}
			if (!$res_ambiente) {
				array_push($response, "erro na query ambiente");				
			}
			if (!$res_empresa) {
				array_push($response, "erro na query empresa");				
			}
			if (!$res_fonte) {
				array_push($response, "erro na query fonte");				
			}
			if (!$res_origem_oleo) {
				array_push($response, "erro na query origem_oleo");				
			}

			print_r(json_encode($response));
			exit();


			//
			//
			// Finaliza a conexão ao banco e finaliza o pedido
			//
			//
			// pg_close($cdb);
			// exit();

			// print_r($form->acidente->geometry->coordinates[1]);
			// print_r(json_encode($form->origem->origens));
			// exit();

			// $query = 'SELECT ST_Intersects(br_mar.geom,ST_SetSRID(ST_MakePoint(' . $lng . ',' . $lat . '),4674)) as intersects FROM br_mar;';
			break;

		case 'deletar_ocorrencia':
			$query =  'DELETE FROM ocorrencia_tipo_evento WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia_tipo_dano_identificado WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia_tipo_localizacao WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia_produto WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia_instituicao_atuando_local WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia_tipo_fonte_informacao WHERE id_ocorrencia=' . $id . ';' .
						'DELETE FROM ocorrencia WHERE id_ocorrencia=' . $id . ';';
			break;

		default:
			# code...
			break;
	}


	$fp = pg_query($query);


	print_r(json_encode(pg_fetch_all($fp)));
	pg_close($cdb);




?>
