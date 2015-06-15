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

        $scope.instituicao.semInstituicao = false;
        $scope.instituicao.complementar = "";

        $scope.$on('carregar_instituicao', function(event, data){
            var temp = data[0].instituicao.replace(/[{}]/g,'').split(',');
            if (temp[0] != "") {
                angular.forEach($scope.instituicoes, function(val, key){
                    if (temp.indexOf(val.id) >= 0) {
                        val.value = true;
                    }
                });
                $scope.instituicao.complementar = data[0].des_complemento_instituicao_atu;
                $scope.instituicao.responsavel = data[0].nome_instituicao_atuando;
                $scope.instituicao.telefone = data[0].telefone_instituicao_atuando;
                $scope
            } else {
                $scope.instituicao.semInstituicao = true;
            }
        });
        // $scope.instituicao.instituicao = [
        //   {name: "IBAMA", value: "false"},
        //   {name: "Órgão Estadual ou Municipal de Meio Ambiente", value: "false"},
        //   {name: "Defesa Civil", value: "false"},
        //   {name: "Corpo de Bombeiros", value: "false"},
        //   {name: "Polícia Rodoviária", value: "false"},
        //   {name: "Polícia Militar", value: "false"},
        //   {name: "Polícia Civil", value: "false"},
        //   {name: "Marinha do Brasil", value: "false"},
        //   {name: "Empresa especializada em atendimento", value: "false"},
        //   {name: "Outra(s)", value: "false"},
        // ];


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
