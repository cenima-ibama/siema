'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:acoes
 * @description
 * # acoes
 */
angular.module('estatisticasApp')
  .directive('acoes', function () {
    return {
      templateUrl: 'views/accordions/acoes.html',
      restrict: 'E',
      controller: function($scope){

        $scope.acoes.subPanel = $scope.oleo ? '(Itens VIII do Anexo II do Decreto n 4.136 de 20 de fevereiro de 2002)' : '';

        $scope.acoes.plano = "";
        $scope.acoes.planoIndividual = false;
        $scope.acoes.outrasProvidencias = false;
        $scope.acoes.semAcoes = false;


        $scope.$on('carregar_acoes', function(event, data){

            $scope.acoes.subPanel = $scope.oleo ? '(Itens VIII do Anexo II do Decreto n 4.136 de 20 de fevereiro de 2002)' : '';

            if ((data[0].plano_emergencia == "S") || (data[0].plano_emergencia == "N") || (data[0].plano_emergencia == "I")) {
                $scope.acoes.plano = data[0].plano_emergencia;
                $scope.acoes.planoIndividual = data[0].plano_emergencia_acionado == 'S' ? true : false;
                $scope.acoes.outrasProvidencias = data[0].iniciados_outras_providencias == 'S' ? true : false;
                $scope.acoes.outrasProvidenciasText = data[0].des_outras_providencias;
            } else {
                $scope.acoes.semAcoes = true;
            }
        });

        // $scope.loginIn = function(user, pass){
        //   // $cookies.user = {user: user, password: pass};
        //   RestApi.login({},{
        //       username: user,
        //       password: pass
        //     },function success(data, status){
        //       $scope.user = data.user;
        //       Auth.setUser(data.user);
        //       $location.path("/page2");
        //     },function error(data, status){
        //         console.log('!ERROR! ' + data);
        //     }
        //   );
        // }

      },
    };
  });
