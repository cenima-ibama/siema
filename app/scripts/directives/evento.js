'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:evento
 * @description
 * # evento
 */
angular.module('estatisticasApp')
  .directive('evento', function () {
    return {
      templateUrl: 'views/accordions/evento.html',
      restrict: 'E',
      controller: function($scope){


        $scope.evento.complementar = "";
        $scope.evento.semeventos = false;

        $scope.evento.eventos = [
            {"name": "Derramamento de líquidos", "value":"false"},
            {"name": "Vazamento de gases", "value":"false"},
            {"name": "Lançamento de sólidos", "value":"false"},
            {"name": "Produtos químicos/embalagens abandonadas", "value":"false"},
            {"name": "Desastre Natural", "value":"false"},
            {"name": "Explosão/incêndio", "value":"false"},
            {"name": "Mortandade de peixes", "value":"false"},
            {"name": "Rompimento de barragem", "value":"false"},
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
