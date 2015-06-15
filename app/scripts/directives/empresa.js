'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:empresa
 * @description
 * # empresa
 */
angular.module('estatisticasApp')
  .directive('empresa', function () {
    return {
      templateUrl: 'views/accordions/empresa.html',
      restrict: 'E',
      controller: function($scope){

        $("#cpfCnpjEmpresa").mask("99999999999999");

        $scope.empresa.nome = "";
        $scope.empresa.cadastro = "";
        $scope.empresa.licencaAmbiental = "0";
        $scope.empresa.semEmpresa = false;

        $scope.$on('carregar_empresa', function(event, data){
            if (data[0].nome_responsavel != null) {
                $scope.empresa.nome = data[0].nome_responsavel;
                $scope.empresa.cadastro = data[0].cpf_cnpj_responsavel;
                $scope.empresa.licencaAmbiental = data[0].licenca_responsavel;
            } else {
                $scope.empresa.semEmpresa = true;
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
