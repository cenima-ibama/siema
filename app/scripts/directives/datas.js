'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:datas
 * @description
 * # datas
 */
angular.module('estatisticasApp')
  .directive('datas', function () {
    return {
      templateUrl: 'views/accordions/datas.html',
      restrict: 'E',
      controller: function($scope){

        if ($scope.oleo) {
            $scope.datas.subPanel = '(Itens II e III do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        } else {
            $scope.datas.subPanel = "";
        }

        $scope.datas.diaObservacao;
        $scope.datas.horaObservacao;
        $scope.datas.obsSemana = 0;
        $scope.datas.obsPeriodo;
        $scope.datas.semObservacao = false;

        $scope.datas.diaIncidente;
        $scope.datas.horaIncidente;
        $scope.datas.incSemana = 0;
        $scope.datas.incPeriodo;
        $scope.datas.semIncidente = false;

        $scope.updateSemana = function($dia, $semana) {
          var temp = $scope.datas[$dia].split('/');
          $scope.datas[$semana] = new Date(temp[2],temp[1] - 1,temp[0]).getDay() + 1;
        }

        // $scope.updateObsSemana = function() {
        //   var temp = $scope.datas.diaObservacao.split('/');
        //   $scope.datas.obsSemana = new Date(temp[2],temp[1] - 1,temp[0]).getDay() + 1;
        // }


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
