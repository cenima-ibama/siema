'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:PdfCtrl
 * @description
 * # PdfCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('PdfCtrl', function ($scope, RestApi, $routeParams) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

    $scope.licencas = [
      {name: 'Licenca ambiental federal', value: '1'},
      {name: 'Licenca ambiental estadual', value: '2'},
      {name: 'Licenca ambiental municipal', value: '3'},
    ];


    $scope.carregarMunicipios = function($uf) {
      RestApi.query({query: 'municipios', uf:$uf},
        function success(data, status){
          $scope.municipios = [];
          angular.forEach(data, function(value, key){
            $scope.municipios.push({'name' : value.nome, 'value': value.cod_ibge});
          });
        }
      );
    }

    RestApi.query({query: 'ufs'},
      function success(data, status){
        $scope.ufs = [];
        angular.forEach(data, function(value, key){
          $scope.ufs.push({'name' : value.sigla, 'value': value.id_uf});
        });
      }
    );

    RestApi.query({query: 'bacias'},
      function success(data, status){
        $scope.bacias = [];
        angular.forEach(data, function(value, key){
          $scope.bacias.push({'name' : value.nome, 'value': value.id_bacia_sedimentar});
        });
      }
    );

    RestApi.query({query: 'origens'},
      function success(data, status){
        $scope.origens = [];
        angular.forEach(data, function(value, key){
          $scope.origens.push({'name' : value.des_tipo_localizacao, 'id': value.id_tipo_localizacao, 'value': false});
        });
      }
    );

    RestApi.query({query: 'eventos'},
      function success(data, status){
        $scope.eventos = [];
        angular.forEach(data, function(value, key){
          $scope.eventos.push({'name' : value.nome, 'id': value.id_tipo_evento, 'value': false});
        });
      }
    );

    RestApi.query({query: 'ambientes'},
      function success(data, status){
        $scope.ambientes = [];
        angular.forEach(data, function(value, key){
          $scope.ambientes.push({'name' : value.nome, 'id': value.id_tipo_dano_identificado, 'value': false});
        });
      }
    );

    RestApi.query({query: 'instituicoes'},
      function success(data, status){
        $scope.instituicoes = [];
        angular.forEach(data, function(value, key){
          $scope.instituicoes.push({'name' : value.nome, 'id': value.id_instituicao_atuando_local, 'value': false});
        });
      }
    );



    RestApi.query({query: 'fontes'},
      function success(data, status){
        $scope.fontes = [];
        angular.forEach(data, function(value, key){
          $scope.fontes.push({'name' : value.nome, 'id': value.id_tipo_fonte_informacao, 'value': false});
        });
      }
    );


    // $scope.nro_ocorrencia = '201531732431';
    // $scope.nro_ocorrencia = '201491928814';
    // $scope.nro_ocorrencia = '201492328850';
    // $scope.nro_ocorrencia = '201491939644';
    // $scope.nro_ocorrencia = '201492528839';
    // $scope.nro_ocorrencia = '201491936043';
    // $scope.nro_ocorrencia = '201492436012';
    // $scope.nro_ocorrencia = '201491950448';

    $scope.nro_ocorrencia = $routeParams.id;

    RestApi.query({query: 'carregar_ocorrencia', ocorrencia:$scope.nro_ocorrencia},
      function success(data, status){
      $scope.oleo = data[0].ocorrencia_oleo == 'S' ? true : false;
      $scope.dt_cadastro = data[0].dt_registro;
      // var dt = new Date();
      // $scope.dt_atualizacao = dt.getFullYear() + "-" + ("0" + (dt.getMonth() + 1)).slice (-2) + "-" + ("0" + dt.getDate()).slice (-2) + " " + ("0" + dt.getHours()).slice (-2) + ":" + ("0" + dt.getMinutes()).slice (-2) + ":" + ("0" + dt.getSeconds()).slice (-2);
      $scope.dt_atualizacao = data[0].hora_atual;
      //Acoes
        $scope.acoes = {};
        $scope.acoes.plano = "";
        $scope.acoes.planoIndividual = "";
        $scope.acoes.outrasProvidencias = false;
        $scope.acoes.semAcoes = false;
        if ((data[0].plano_emergencia == "S") || (data[0].plano_emergencia == "N") || (data[0].plano_emergencia == "I")) {
            $scope.acoes.plano = data[0].plano_emergencia;

            $scope.acoes.planoIndividual = data[0].plano_emergencia_acionado == 'S' ? true : false;
            $scope.acoes.outrasProvidencias = data[0].iniciados_outras_providencias == 'S' ? true : false;
            $scope.acoes.outrasProvidenciasText = data[0].des_outras_providencias;
        } else {
            $scope.acoes.semAcoes = true;
        }

      //Ambientes atingidos
        $scope.ambiente = {};
        $scope.ambiente.complementar = "";
        $scope.ambiente.semAmbientes = false;
        var temp = data[0].ambiente.replace(/[{}]/g,'').split(',');
        if (temp[0] != "") {
            angular.forEach($scope.ambientes, function(val, key){
                if (temp.indexOf(val.id) >= 0) {
                    val.value = true;
                }
            });
            $scope.ambiente.complementar = data[0].des_complemento_tipo_dano_ident;
            if($scope.ambiente.complementar == ''){
              $scope.ambiente.complementarVal = false;
            } else{
              $scope.ambiente.complementarVal = true;
            }

        } else {
            $scope.ambiente.semAmbientes = true;
        }


      //Comunicante
        $scope.comunicante = {};
        $scope.comunicante.nome = data[0].nome_comunicante;
        $scope.comunicante.empresa = data[0].des_instituicao_empresa;
        $scope.comunicante.funcao = data[0].des_funcao_comunicante;
        $scope.comunicante.telefone = data[0].telefone_contato;
        $scope.comunicante.email = data[0].email_comunicante;

        if($scope.comunicante.nome == '' || $scope.comunicante.nome == null)
          $scope.comunicante.nome = '********';
        if($scope.comunicante.empresa == '' || $scope.comunicante.empresa == null)
          $scope.comunicante.empresa = '********';
        if($scope.comunicante.funcao == '' || $scope.comunicante.funcao == null )
          $scope.comunicante.funcao = '********';
        if($scope.comunicante.telefone == '' || $scope.comunicante.telefone == null)
          $scope.comunicante.telefone = '********';
        if($scope.comunicante.email == '' || $scope.comunicante.email == null)
          $scope.comunicante.email = '********';

      //Data
        $scope.datas = {};
        $scope.datas.diaObservacao;
        $scope.datas.horaObservacao;
        $scope.datas.obsSemana = 0;
        $scope.datas.obsPeriodo;
        $scope.datas.semObservacao = false;

        $scope.datas.diaIncidente;
        $scope.datas.horaIncidente;
        $scope.datas.incSemana = 0;
        $scope.datas.incPeriodo;
        $scope.datas.semIncidente = false;

        $scope.updateSemana = function($dia, $semana) {
          var temp = $scope.datas[$dia].split('/');
          $scope.datas[$semana] = new Date(temp[2],temp[1] - 1,temp[0]).getDay() + 1;
        }
        var temp;
        if (data[0].dt_primeira_obs != null) {
            temp = data[0].dt_primeira_obs.split("-");
            $scope.datas.diaObservacao = temp[2] + "/" + temp[1] + "/" + temp[0];
            $scope.updateSemana('diaObservacao','obsSemana');
            $scope.datas.horaObservacao = data[0].hr_primeira_obs;
            switch(data[0].periodo_primeira_obs){
              case 'M':
                $scope.datas.obsPeriodo = "Matutino";
                break;
              case 'V':
                $scope.datas.obsPeriodo = "Vespertino";
                break;
              case 'N':
                $scope.datas.obsPeriodo = "Noturno";
                break;
              case 'S':
                $scope.datas.obsPeriodo = "Madrugada";
                break;
            }
            // $scope.datas.obsPeriodo = data[0].periodo_primeira_obs;
            $scope.datas.semObservacao = false;
        } else {
            $scope.datas.semObservacao = true;
        }


        if (data[0].dt_ocorrencia != null){
            temp = data[0].dt_ocorrencia.split("-");
            $scope.datas.diaIncidente = temp[2] + "/" + temp[1] + "/" + temp[0];
            $scope.updateSemana('diaIncidente','incSemana');
            $scope.datas.horaIncidente = data[0].hr_ocorrencia;
            switch(data[0].periodo_primeira_obs){
              case 'M':
                $scope.datas.incPeriodo = "Matutino";
                break;
              case 'V':
                $scope.datas.incPeriodo = "Vespertino";
                break;
              case 'N':
                $scope.datas.incPeriodo = "Noturno";
                break;
              case 'S':
                $scope.datas.incPeriodo = "Madrugada";
                break;
            }
            // $scope.datas.incPeriodo = data[0].periodo_ocorrencia;
            $scope.datas.feriado = data[0].dt_ocorrencia_feriado;
            $scope.datas.semIncidente = false;
        } else {
            $scope.datas.semIncidente = true;
        }

      //Detalhes
        $scope.detalhes = {};

        if (data[0].des_causa_provavel != null && data[0].des_causa_provavel != ""){
          $scope.detalhes.semDetalhe = false;
          $scope.detalhes.causa = "";
          $scope.detalhes.situacao;
        } else {
          $scope.detalhes.semDetalhe = true;
        }

        if (data[0].des_causa_provavel != null){
              $scope.detalhes.causa = data[0].des_causa_provavel;
              if($scope.detalhes.causa == ''){
                $scope.detalhes.causaVal = false;
                $scope.detalhes.semDetalhe = true;
              } else {
                $scope.detalhes.causaVal = true;
              }
        } else {
            $scope.detalhes.semDetalhe = true;
        }

        if ($scope.oleo) {
            $scope.detalhes.situacaoVal = true;
            $scope.detalhes.situacao = data[0].situacao_atual_descarga;
        }

      //Empresa
        $scope.empresa = {};

        if (data[0].nome_responsavel != null) {
            $scope.empresa.nome = data[0].nome_responsavel;
            $scope.empresa.cadastro = data[0].cpf_cnpj_responsavel;
            $scope.empresa.licencaAmbiental = data[0].licenca_responsavel;

        } else {
            $scope.empresa.semEmpresa = true;
        }

      //Evento
        $scope.evento = {};
        var temp = data[0].evento.replace(/[{}]/g,'').split(',');
        if (temp[0] != "") {
            angular.forEach($scope.eventos, function(val, key){
                if (temp.indexOf(val.id) >= 0) {
                    val.value = true;
                }
            });
            $scope.evento.complementar = data[0].des_complemento_tipo_evento;
            if($scope.evento.complementar == ''){
              $scope.evento.complementarVal = false;
            } else {
              $scope.evento.complementarVal = true;
            }

        } else {
            $scope.evento.semEvento = true;
        }

      //Fonte
        $scope.fonte = {};
        $scope.fonte.complementar = "";

        var temp = data[0].fonte.replace(/[{}]/g,'').split(',');
        if (temp[0] != "") {
          angular.forEach($scope.fontes, function(val, key){
              if (temp.indexOf(val.id) >= 0) {
                  val.value = true;
              }
          });
          $scope.fonte.complementar = data[0].desc_outras_fontes;
          if($scope.fonte.complementar == ''){
            $scope.fonte.complementarVal = false;
          } else {
            $scope.fonte.complementarVal = true;
          }
        }

      //Gerais
        $scope.gerais = {};
        $scope.gerais.text = data[0].des_obs;
        if($scope.gerais.text == ''){
          $scope.gerais.text = '********';
        } else {
          $scope.gerais.text = $scope.gerais.text;
        }

      //Instituicao
        $scope.instituicao = {};
        $scope.instituicao.semInstituicao = false;
        $scope.instituicao.complementar = "";
        var temp = data[0].instituicao.replace(/[{}]/g,'').split(',');
        if (temp[0] != "") {
            angular.forEach($scope.instituicoes, function(val, key){
                if (temp.indexOf(val.id) >= 0) {
                    val.value = true;
                }
            });

            $scope.instituicao.complementar = data[0].des_complemento_instituicao_atu;
            $scope.instituicao.responsavel = data[0].nome_instituicao_atuando;
            $scope.instituicao.telefone = data[0].telefone_instituicao_atuando;

            if($scope.instituicao.responsavel == '' || $scope.instituicao.responsavel == null)
              $scope.instituicao.responsavel = '********';
            if($scope.instituicao.telefone == '' || $scope.instituicao.telefone == null)
              $scope.instituicao.telefone = '********';
            if($scope.instituicao.complementar == '' || $scope.instituicao.complementar == null )
              $scope.instituicao.complementar = '********';

            if($scope.instituicao.complementar == ''){
              $scope.instituicao.complementarVal = false;
            } else {
              $scope.instituicao.complementarVal = true;
            }



        } else {
            $scope.instituicao.semInstituicao = true;
        }

      //Localizacao
        $scope.localizacao = {};
        $scope.localizacao.lat = data[0].coordinate.split(' ')[0];
        $scope.localizacao.lng = data[0].coordinate.split(' ')[1];
        $scope.localizacao.oceano = data[0].id_bacia_sedimentar ? true : false;
        $scope.localizacao.bacia = data[0].id_bacia_sedimentar;
        $scope.localizacao.uf = data[0].id_uf;

        $scope.carregarMunicipios($scope.localizacao.uf);

        $scope.localizacao.municipio = data[0].id_municipio;
        $scope.localizacao.endereco = data[0].endereco_ocorrencia;

      //Origem
        $scope.origem = {};
        var temp = data[0].origem.replace(/[{}]/g,'').split(',');
        if (temp[0] != "") {
            angular.forEach($scope.origens, function(val, key){
                if (temp.indexOf(val.id) >= 0) {
                    val.value = true;
                }
            });
            $scope.origem.complementar = data[0].des_complemento_tipo_localizaca;
            if($scope.origem.complementar == ''){
              $scope.origem.complementarVal = false;
              // $scope.origem.semOrigem = true;
            } else{
              $scope.origem.complementarVal = true;
            }
        } else {
            $scope.origem.semOrigem = true;
        }

        if ($scope.oleo) {
            if ((data[0].des_navio != null) || (data[0].des_instalacao != null)) {
                $scope.origem.navio = data[0].des_navio;
                if($scope.origem.navio == ''){
                  $scope.origem.nouv = 'I';
                }

                $scope.origem.instalacao = data[0].des_instalacao;
                if($scope.origem.instalacao == ''){
                  $scope.origem.nouv = 'N';
                }
            } else {
                $scope.origem.semOleoOrigem = true;
            }
        }

      //Produtos
        $scope.produtos = {};
        $scope.produtos.produtos_outros = [];
        $scope.produtos.produtos_onu = [];
        $scope.getProdutos = function(){
          RestApi.query({query: 'produtos_cadastrados', id: data[0].id_ocorrencia},
            function success(data, status){
              if(data) {
                $scope.produtos.produtos_outros = [];
                angular.forEach(data, function(value, key){

                  var novo_produto = {};

                  if (value.id_produto_onu) {
                      novo_produto = {
                          'id'    : value.id_produto_onu,
                          'qtd'   : value.quantidade,
                          'uni'   : value.unidade_medida,
                          'field' : value.nome_onu + ' - ' + value.num_onu + ' - ' + value.classe_risco
                      };
                      $scope.produtos.produtos_onu.push(novo_produto);
                  } else {
                      novo_produto = {
                          'id'    : value.id_produto_outro,
                          'qtd'   : value.quantidade,
                          'uni'   : value.unidade_medida,
                          'field' : value.nome_outro
                      };
                      $scope.produtos.produtos_outros.push(novo_produto);
                  }
                });
              }
            }
          );
        }
        $scope.getProdutos();

        $scope.produtos.naoClassificado = data[0].produto_perigoso == 't' ? true : false;
        $scope.produtos.naoAplica = data[0].produto_nao_se_aplica == 't' ? true : false;
        $scope.produtos.naoEspecificado = data[0].produto_nao_especificado == 't' ? true : false;


        if (data[0].tipo_substancia || data[0].volume_estimado) {
            $scope.produtos.tipo_substancia = data[0].tipo_substancia;
            $scope.produtos.valor_substancia = data[0].volume_estimado;
        } else {
            $scope.produtos.semCondicoes = true;
        }





      }
    );

  });
