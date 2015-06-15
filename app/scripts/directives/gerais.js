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

        $scope.gerais.subPanel = $scope.oleo ? '(Itens IX do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';
        $scope.gerais.text = "";

        $scope.$on('carregar_gerais', function(event, data){
            $scope.gerais.subPanel = $scope.oleo ? '(Itens IX do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';
            $scope.gerais.text = data[0].des_obs;
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