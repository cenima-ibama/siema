'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:gerais
 * @description
 * # gerais
 */
angular.module('estatisticasApp')
  .directive('gerais', function () {
    return {
      templateUrl: 'views/accordions/gerais.html',
      restrict: 'E',
      controller: function($scope){

        $scope.gerais.subPanel = '(Itens IX do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';

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
