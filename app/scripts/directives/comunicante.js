'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:comunicante
 * @description
 * # comunicante
 */
angular.module('estatisticasApp')
  .directive('comunicante', function () {
    return {
      templateUrl: 'views/accordions/comunicante.html',
      restrict: 'E',
      controller: function($scope){

        $scope.comunicante.subPanel = '(Itens X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';

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
