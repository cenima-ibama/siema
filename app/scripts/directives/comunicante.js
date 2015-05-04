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

        $scope.comunicante.subPanel = $scope.oleo ? '(Itens X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

        // if ($scope.oleo) {
        //   $scope.comunicante.subPanel = '(Itens X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        // } else {
        //     $scope.comunicante.subPanel = "";
        // }

        $scope.comunicante.nome = "";
        $scope.comunicante.empresa = "";
        $scope.comunicante.funcao = "";
        $scope.comunicante.telefone = "";
        $scope.comunicante.email = "";


        $scope.$on('carregar_origem', function(event, data){

            $scope.comunicante.subPanel = $scope.oleo ? '(Itens X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

            $scope.comunicante.nome = data[0].nome_comunicante;
            $scope.comunicante.empresa = data[0].des_instituicao_empresa;
            $scope.comunicante.funcao = data[0].des_funcao_comunicante;
            $scope.comunicante.telefone = data[0].telefone_contato;
            $scope.comunicante.email = data[0].email_comunicante;
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
