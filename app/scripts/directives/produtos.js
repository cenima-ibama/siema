'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:produtos
 * @description
 * # produtos
 */
angular.module('estatisticasApp')
  .directive('produtos', function ($compile, RestApi) {
    return {
      templateUrl: 'views/accordions/produtos.html',
      restrict: 'E',
      controller: function($scope){

        $scope.lastEle = 1;
        // $scope.valueRows = [$scope.lastEle];
        $scope.valueRows = [];
        $scope.produtos.novo_onu = true;
        $scope.produtos.produtos_onu = [];

        $scope.lastEleNaoOnu = 1001;
        // $scope.valueRowsNaoOnu = [$scope.lastEleNaoOnu];
        $scope.valueRowsNaoOnu = [];
        $scope.produtos.novo_nao_onu = true;
        $scope.produtos.produtos_outros = [];


        // $("#volume").mask("0000000000000000000000.09", {reverse: true});
        $("#volume").mask("000000000.9999999999", {reverse: true})
        $("#qtdOnu").mask("0000000000000000000000.09", {reverse: true});
        $("#qtdNaoOnu").mask("0000000000000000000000.09", {reverse: true});

        $scope.produtos.createMask = function(id) {
            var field = "#" + id;
            $(field).mask("00000.09", {reverse: true})
        };


        $scope.produtos.subPanel = $scope.oleo ? '(Itens V do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

        $scope.$on('carregar_produtos', function(event, data){
            $scope.produtos.subPanel = $scope.oleo ? '(Itens V do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

            RestApi.query({query: 'produtos_cadastrados', id: data[0].id_ocorrencia},
              function success(data, status){
                if(data) {
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

                    // $scope.produtos.produtos_outros.push({'field' : value.nome , 'id': value.id});
                  });
                }
              }
            );


            if ((data[0].produto_perigoso == 'f') && (data[0].produto_nao_se_aplica == 'f') && (data[0].produto_nao_especificado == 'f') &&
                ($scope.produtos.produtos_onu.length == 0) && ($scope.produtos.produtos_outros.length == 0)) {
                $scope.produtos.semProduto = true;
            } else {

                $scope.produtos.naoClassificado = data[0].produto_perigoso == 't' ? true : false;
                $scope.produtos.naoAplica = data[0].produto_nao_se_aplica == 't' ? true : false;
                $scope.produtos.naoEspecificado = data[0].produto_nao_especificado == 't' ? true : false;
            }

            if (data[0].tipo_substancia || data[0].volume_estimado) {
                $scope.produtos.tipo_substancia = data[0].tipo_substancia;
                $scope.produtos.valor_substancia = data[0].volume_estimado;
            } else {
                $scope.produtos.semCondicoes = true;
            }

            // if (data[0].des_causa_provavel != null){
            //     $scope.detalhes.causa = data[0].des_causa_provavel;
            // } else {
            //     $scope.detalhes.semDetalhe = true;
            // }

            // if ($scope.oleo) {
            //     $scope.detalhes.situacao = data[0].situacao_atual_descarga;
            // }
        });

        // Funcoes para produtos ONU
        $scope.addRowOnu = function(){
            $scope.valueRows.push(++$scope.lastEle);
            $scope.produtos.novo_onu = false;
        }

        $scope.deleteData = function(v){
            var index = $scope.produtos.produtos_onu.indexOf(v);
            if (index > -1) {
                $scope.produtos.produtos_onu.splice(index, 1);
            } else {
                $scope.produtos.substanciaOnu = "";
                $scope.produtos.quantidadeOnu = "";
                $scope.produtos.unidadeOnu = "";

                $scope.valueRows = [];
                $scope.produtos.novo_onu = true;
            }

        }

        $scope.saveData = function(){
            // document.getElementById(i).chiladdClass('disabled', 'disabled');

            var novo_produto_onu = {
                'id'    : $scope.produtos.substanciaOnu.id,
                'qtd'   : $scope.produtos.quantidadeOnu,
                'uni'   : $scope.produtos.unidadeOnu,
                'field' : $scope.produtos.substanciaOnu.field
            };

            $scope.produtos.produtos_onu.push(novo_produto_onu);

            $scope.produtos.substanciaOnu = "";
            $scope.produtos.quantidadeOnu = "";
            $scope.produtos.unidadeOnu = "";

            $scope.valueRows = [];

            $scope.produtos.novo_onu = true;
        }

        // Funcoes para produtos não ONU
        $scope.addRowNaoOnu = function(){
            $scope.valueRowsNaoOnu.push(++$scope.lastEleNaoOnu);
            $scope.produtos.novo_nao_onu = false;

        }

        $scope.deleteDataNaoOnu = function(v){
            var index = $scope.produtos.produtos_outros.indexOf(v);
            if (index > -1) {
                $scope.produtos.produtos_outros.splice(index, 1);
            } else {
                $scope.produtos.substanciaOutro = "";
                $scope.produtos.quantidadeOutro = "";
                $scope.produtos.unidadeOutro = "";

                $scope.valueRowsNaoOnu = [];
                $scope.produtos.novo_nao_onu = true;
            }

        }

        $scope.saveDataNaoOnu = function(){
            // document.getElementById(i).addClass('disabled', 'disabled');

            var novo_produto_outro = {
                'id'    : $scope.produtos.substanciaOutro.id,
                'qtd'   : $scope.produtos.quantidadeOutro,
                'uni'   : $scope.produtos.unidadeOutro,
                'field' : $scope.produtos.substanciaOutro.field
            };

            $scope.produtos.produtos_outros.push(novo_produto_outro);

            $scope.produtos.substanciaOutro = "";
            $scope.produtos.quantidadeOutro = "";
            $scope.produtos.unidadeOutro = "";

            $scope.valueRowsNaoOnu = [];

            $scope.produtos.novo_nao_onu = true;
        }

        //Função para inicializar o popover
        //Chamada no ng-init do html da diretiva
        $scope.popover = function(){
            $('[data-toggle="popover"]').popover({ html : true })
            $("#addTipo").popover({
                html : true,
                content: function() {
                  return $('#popoverContent').html();
                }
            }).click(function(ev) {
                 //this is workaround needed in order to make ng-click work inside of popover

                $compile($('.popover.in').contents())($scope);
            });
        }

        $scope.validate = function(dado){
            var data = ['SELECT', 'select', '*', 'FROM', 'from', '%', 'DROP', 'drop', ''];
            var o=0;

            angular.forEach(data, function(value, key){
                if(dado == value || !dado)
                    o++;
                angular.forEach(dado, function(v, k){
                    if(v == value)
                        o++;
                })
            })

            if(o==0)
                return true;
            else
                return false;

        }

        $scope.addTipo = function(val){
            var d = $scope.validate(val);
            if(d){
                RestApi.query({query: 'adicionar_tipo', tipo_produto:val},
                    function success(data, status){
                        if(data) {
                            $scope.produtos_outros = [];
                            angular.forEach(data, function(value, key){
                              $scope.produtos_outros.push({'field' : value.nome , 'id': value.id});
                            });
                        }
                    }
                );
            }

            $("#addTipo").popover('hide');

        }

      },
    };
  });
