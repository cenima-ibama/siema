'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:ChartsCtrl
 * @description
 * # ChartsCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('ChartsCtrl', function ($scope, $rootScope, RestApi) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

	$scope.anos = [];

	var year = new Date();

	for(var i=2008; i<= year.getFullYear(); i++){
		$scope.anos.push(i.toString());
	}


  RestApi.query({query: 'vw_ocorrencia'},
    function success(data, status){

      var ret ={};


      //Broadcasting data para o escopo global
      //Por que este servico retorna o dado para um escopo diferente

      $scope.rest = data;
      $scope.numberOfElements = 3;
      Chart.defaults.global.colours = ["#00B2EE", "#F7464A", "#46BFBD", "#7B68EE", "#FDB45C", "#949FB1", "#8B5742"];

      ret.regiao = $rootScope.regiao;
      ret.ano = $rootScope.ano;
      ret.mes = $rootScope.mes;
      ret.evento = $rootScope.evento;
      ret.origem = $rootScope.origem;
      ret.data = data;


      var restData = {};
      var reg = 0;
      var regions = $rootScope.regionsArray;
      var elem = {};

      for (var i = 0; i < data.length; i++) {
        if ((reg = regions.indexOf(data[i].regiao)) != -1) {
          if (restData[regions[reg]] == null) {
            restData[regions[reg]] = {};
          }
        }

        restData[regions[reg]][data[i].id_ocorrencia] = {};
        elem = restData[regions[reg]][data[i].id_ocorrencia];


        elem.dt_registro = data[i].dt_registro;
        elem.dt_ocorrencia = data[i].dt_ocorrencia;
        elem.regiao = data[i].regiao;
        elem.origens = data[i].origem.replace(/[\"{}]+/g, '').split(",");
        elem.eventos = data[i].eventos.replace(/[\"{}]+/g, '').split(",");
        elem.danos = data[i].tipos_danos_identificados.replace(/[\"{}]+/g, '').split(",");
        elem.instituicoes = data[i].institiuicoes_atuando_local.replace(/[\"{}]+/g, '').split(",");
        elem.fontes = data[i].tipos_fontes_informacoes.replace(/[\"{}]+/g, '').split(",");
        
      }


      ret.data = restData;
      $scope.restData = restData;
      // ret.data = data;

      // $scope.$broadcast('load_mensal', restData);
      $scope.$broadcast('load_mensal', ret);
      $scope.$broadcast('load_instituicoes', ret);
      $scope.$broadcast('load_eventos', ret);
      $scope.$broadcast('load_eventosmes', ret);

    }
  );

  $scope.$on('filter', function(event, data){

      var ret = {};

      ret.regiao = data.regiao;
      ret.ano = data.ano;
      ret.mes = data.mes;
      ret.evento = data.evento;
      ret.origem = data.origem;
      // ret.data = $scope.rest;
      ret.data = $scope.restData;

      $scope.$broadcast('load_mensal', ret);
      $scope.$broadcast('load_instituicoes', ret);
      $scope.$broadcast('load_eventos', ret);
      $scope.$broadcast('load_eventosmes', ret);
  });

  // $('#addMeModal').modal({
  //   keyboard: false,
  //   backdrop: false
  // });

  // $('#acdtModal').modal('show');

});
