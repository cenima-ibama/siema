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

        $scope.acoes.subPanel = '(Itens VIII do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';

        $scope.check = function(){
          console.log($scope.acoes);
        }
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
