'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:AcidenteCtrl
 * @description
 * # AcidenteCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('AcidenteCtrl', function ($scope, RestApi) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

    $scope.accordions = ['localizacao',
                         'datas',
                         'origem',
                         'evento',
                         'produtos',
                         'detalhes',
                         'ambientes',
                         'empresa',
                         'instituicao',
                         'acoes',
                         'gerais',
                         'comunicante'];

    $scope.acao = 'carregar';
    $scope.oleo = false;
    $scope.nro_ocorrencia = '201531732431';
    // $scope.nro_ocorrencia = '201491928814';

    $scope.oleo = false;

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



    if ($scope.acao == 'carregar') {
      RestApi.query({query: 'carregar_ocorrencia', ocorrencia:$scope.nro_ocorrencia},
        function success(data, status){
          $scope.oleo = data[0].ocorrencia_oleo == 'S' ? true : false;

          $scope.$broadcast('carregar_localizacao', data);
          $scope.$broadcast('carregar_datas', data);
          $scope.$broadcast('carregar_origem', data);
          // $scope.$broadcast('carregar_evento', ret);
          // $scope.$broadcast('carregar_propdutos', ret);
          // $scope.$broadcast('carregar_detalhes', ret);
          // $scope.$broadcast('carregar_ambientes', ret);
          // $scope.$broadcast('carregar_empresa', ret);
          // $scope.$broadcast('carregar_instituicao', ret);
          // $scope.$broadcast('carregar_acoes', ret);
          // $scope.$broadcast('carregar_gerais', ret);
          // $scope.$broadcast('carregar_comunicante', ret);
          // $scope.$broadcast('carregar_arquivos', ret);
        }
      );
    } else if ($scope.acao == 'deletar') {
      RestApi.query({query: 'deletar_ocorrencia'},
        function success(data, status){

        }
      );
    }


    angular.forEach($scope.accordions, function(obj) {
      $scope[obj] = {};
      $scope[obj].subPanel = "";
    });

    // $scope.bacias = [
    //   { name: "Bacia 1", value: "1" },
    //   { name: "Bacia 2", value: "2" },
    //   { name: "Bacia 3", value: "3" },
    //   { name: "Bacia 4", value: "4" }
    // ];


    // $scope.municipios = [
    //   { name: "Municipio 1", value: "1" },
    //   { name: "Municipio 2", value: "2" },
    //   { name: "Municipio 3", value: "3" },
    //   { name: "Municipio 4", value: "4" }
    // ];

    // $scope.toggleAccordion = function($accordion) {
    //   if ($scope[$accordion].show == 'in') {
    //     angular.forEach($scope.accordions, function(obj) {
    //       $scope[obj].show = "";
    //     });
    //   } else {
    //     angular.forEach($scope.accordions, function(obj) {
    //       $scope[obj].show = "";
    //     });
    //     $scope[$accordion].show = 'in';
    //   }
    // };

  });
