'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:detalhes
 * @description
 * # detalhes
 */
angular.module('estatisticasApp')
  .directive('detalhes', function () {
    return {
      templateUrl: 'views/accordions/detalhes.html',
      restrict: 'E',
      controller: function($scope){

        $scope.detalhes.subPanel = $scope.oleo ? '(Itens VI e VII do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

        $scope.detalhes.semDetalhe = false;
        $scope.detalhes.causa = "";

        $scope.$on('carregar_detalhes', function(event, data){
            $scope.detalhes.subPanel = $scope.oleo ? '(Itens VI e VII do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

            if (data[0].des_causa_provavel != null){
                $scope.detalhes.causa = data[0].des_complemento_tipo_localizaca;
            } else {
                $scope.detalhes.semDetalhe = true;
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
