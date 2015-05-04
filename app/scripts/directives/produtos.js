'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:produtos
 * @description
 * # produtos
 */
angular.module('estatisticasApp')
  .directive('produtos', function () {
    return {
      templateUrl: 'views/accordions/produtos.html',
      restrict: 'E',
      controller: function($scope){

        $scope.produtos.subPanel = $scope.oleo ? '(Itens V do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

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
