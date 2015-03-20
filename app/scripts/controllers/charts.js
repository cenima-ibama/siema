'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:ChartsCtrl
 * @description
 * # ChartsCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('ChartsCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

	$scope.baseUrl = 'images/icons/';

	$scope.regions = [
		{ name: 'NORDESTE', image: $scope.baseUrl + 'Nordeste.png'},	
		{ name: 'NORTE', image: $scope.baseUrl + 'Norte.png'},	
		{ name: 'SUL', image: $scope.baseUrl + 'Sul.png'},	
		{ name: 'CENTRO-OESTE', image: $scope.baseUrl + 'CentroOeste.png'},	
		{ name: 'SUDESTE', image: $scope.baseUrl + 'Sudeste.png'},	
		{ name: 'BRASIL', image: $scope.baseUrl + 'Brasil.png'},	
	];


	$scope.anos = [];

	var year = new Date();

	for(var i=2008; i<= year.getFullYear(); i++){
		$scope.anos.push(i.toString());
	}

	$scope.meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
	$scope.tipos = ['Derramamento de ĺíquido', 'Desastre Natural', 'Explosão/Incêndio', 'Lançamento de sólidos', 'Mortandade de peixes', 'Produtos químicos/embalagens abandonadas', 'Rompimento de barragem', 'Vazamento de gases', 'Todos'];
	$scope.origens = ['Rodovia', 'Ferrovia', 'Terminal/Portos/Ancoradouros/etc.', 'Embarcação', 'Refinaria', 'Plataforma', 'Indústria', 'Duto', 'Barragem', 'Armazenamento/Depósito', 'Posto de Combustível', 'Outros'];



  });
