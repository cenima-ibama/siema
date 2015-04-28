'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:AcidenteCtrl
 * @description
 * # AcidenteCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('AcidenteCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

    if ($scope.acao == 'carregar') {
      RestApi.query({query: 'carregar_ocorrencia'},
        function success(data, status){
          $scope.$broadcast('carregar_localizacao', ret);
          $scope.$broadcast('carregar_datas', ret);
          $scope.$broadcast('carregar_origem', ret);
          $scope.$broadcast('carregar_evento', ret);
          $scope.$broadcast('carregar_propdutos', ret);
          $scope.$broadcast('carregar_detalhes', ret);
          $scope.$broadcast('carregar_ambientes', ret);
          $scope.$broadcast('carregar_empresa', ret);
          $scope.$broadcast('carregar_instituicao', ret);
          $scope.$broadcast('carregar_acoes', ret);
          $scope.$broadcast('carregar_gerais', ret);
          $scope.$broadcast('carregar_comunicante', ret);
          $scope.$broadcast('carregar_arquivos', ret);
        }
      );
    } else if ($scope.acao == 'deletar') {
      RestApi.query({query: 'deletar_ocorrencia'},
        function success(data, status){

        }
      );
    }

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

    angular.forEach($scope.accordions, function(obj) {
      $scope[obj] = {};
      $scope[obj].subPanel = "";
    });


    $scope.nro_ocorrencia = "123123123";

    $scope.bacias = [
      { name: "Bacia 1", value: "1" },
      { name: "Bacia 2", value: "2" },
      { name: "Bacia 3", value: "3" },
      { name: "Bacia 4", value: "4" }
    ];

    $scope.ufs = [
      { name: "UF 1", value: "1" },
      { name: "UF 2", value: "2" },
      { name: "UF 3", value: "3" },
      { name: "UF 4", value: "4" }
    ];

    $scope.municipios = [
      { name: "Municipio 1", value: "1" },
      { name: "Municipio 2", value: "2" },
      { name: "Municipio 3", value: "3" },
      { name: "Municipio 4", value: "4" }
    ];

    $scope.oleo = true;

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
