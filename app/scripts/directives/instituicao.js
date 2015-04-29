'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:instituicao
 * @description
 * # instituicao
 */
angular.module('estatisticasApp')
  .directive('instituicao', function () {
    return {
      templateUrl: 'views/accordions/instituicao.html',
      restrict: 'E',
      controller: function($scope){
        $scope.instituicao.instituicao = [
          {name: "IBAMA", value: "false"},
          {name: "Órgão Estadual ou Municipal de Meio Ambiente", value: "false"},
          {name: "Defesa Civil", value: "false"},
          {name: "Corpo de Bombeiros", value: "false"},
          {name: "Polícia Rodoviária", value: "false"},
          {name: "Polícia Militar", value: "false"},
          {name: "Polícia Civil", value: "false"},
          {name: "Marinha do Brasil", value: "false"},
          {name: "Empresa especializada em atendimento", value: "false"},
          {name: "Outra(s)", value: "false"},
        ];


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
