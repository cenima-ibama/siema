'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:FormCtrl
 * @description
 * # FormCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('FormCtrl', function ($scope, $rootScope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

  $scope.baseUrl = 'images/icons/';

  $scope.regions = [
    { name: 'NE', image: $scope.baseUrl + 'Nordeste.png', subtitle: 'Nordeste'},
    { name: 'NO', image: $scope.baseUrl + 'Norte.png', subtitle: 'Norte'},
    { name: 'SU', image: $scope.baseUrl + 'Sul.png', subtitle: 'Sul'},
    { name: 'CO', image: $scope.baseUrl + 'CentroOeste.png', subtitle: 'Centro-Oeste'},
    { name: 'SE', image: $scope.baseUrl + 'Sudeste.png', subtitle: 'Sudeste'},
    { name: 'BRASIL', image: $scope.baseUrl + 'Brasil.png', subtitle: 'Brasil'},
  ];
  $rootScope.regionsArray = $scope.regions.map(function(obj){ return obj.name});


  $scope.meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
  $scope.eventos = ['Derramamento de ĺíquido', 'Desastre Natural', 'Explosão/Incêndio', 'Lançamento de sólidos', 'Mortandade de peixes', 'Produtos químicos/embalagens abandonadas', 'Rompimento de barragem', 'Vazamento de gases', 'Outro(s)', 'Todos'];
  $scope.origens = ['Rodovia', 'Ferrovia', 'Terminal/Portos/Ancoradouros/etc.', 'Embarcação', 'Refinaria', 'Plataforma', 'Indústria', 'Duto', 'Barragem', 'Armazenamento/Depósito', 'Posto de Combustível', 'Outro(s)', 'Todos'];


  $rootScope.regiao = 'BRASIL';
  $rootScope.ano = (new Date().getFullYear()).toString();
  $rootScope.mes = $scope.meses[new Date().getMonth()];
  $rootScope.evento = 'Todos';
  $rootScope.origem = 'Todos';

  $scope.getValueClass = function ($out){
    $scope.regiao = $out.$$watchers[2].last;
  }

  $scope.changeClass = function ($out){
    if ($out.$$watchers[2].last == $scope.regiao) {
      return 'active';
    }
  }

  $scope.filter = function() {
    var data = {};
    data.regiao = $scope.regiao;
    data.ano = $scope.ano;
    data.mes = $scope.mes;
    data.evento = $scope.evento;
    data.origem = $scope.origem;

    $rootScope.$broadcast('filter', data);
  };
});
