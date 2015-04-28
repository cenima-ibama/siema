'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:origem
 * @description
 * # origem
 */
angular.module('estatisticasApp')
  .directive('origem', function () {
    return {
      templateUrl: 'views/accordions/origem.html',
      restrict: 'E',
      controller: function($scope){


        if ($scope.oleo) {
            $scope.origem.subPanel = '(Itens I do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        } else {
            $scope.origem.subPanel = "";
        }

        $scope.origem.complementar = "";
        $scope.origem.semOrigem = false;

        $scope.origem.origens = [
            {"name": "Rodovia", "value":"false"},
            {"name": "Ferrovia", "value":"false"},
            {"name": "Terminal/portos/ancoradouros/etc", "value":"false"},
            {"name": "Embarcação", "value":"false"},
            {"name": "Refinaria", "value":"false"},
            {"name": "Plataforma", "value":"false"},
            {"name": "Indústria", "value":"false"},
            {"name": "Barragem", "value":"false"},
            {"name": "Armazenamento/depósito", "value":"false"},
            {"name": "Posto de combustível", "value":"false"},
            {"name": "Outro(s)", "value":"false"}
        ];

        // $scope.localizacao.toggle = function() {
        //   if ($scope.localizacao.show == 'in') {
        //     $scope.localizacao.show = '';
        //     $scope.datas.show = '';
        //   } else {
        //     $scope.localizacao.show = 'in';
        //     $scope.datas.show = '';
        //   }

        //   // console.log($scope.localizacao.show);
        // };

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
