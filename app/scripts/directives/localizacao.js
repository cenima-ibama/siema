'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:localizacao
 * @description
 * # localizacao
 */
angular.module('estatisticasApp')
  .directive('localizacao', function () {
    return {
      templateUrl: 'views/accordions/localizacao.html',
      restrict: 'E',
      controller: function($scope){

        if ($scope.oleo) {
            $scope.localizacao.subPanel = '(Itens IV do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        } else {
            $scope.localizacao.subPanel = "";
        }

        $scope.localizacao.lat;
        $scope.localizacao.lng;
        $scope.localizacao.oceano = false;
        $scope.localizacao.bacia;
        $scope.localizacao.uf;
        $scope.localizacao.municipio;
        $scope.localizacao.endereco;


        $scope.$on('carregar_localizacao', function(event, data){
            $scope.localizacao.lat = data.lat;
            $scope.localizacao.lng = data.lng;
            $scope.localizacao.oceano = false;
            $scope.localizacao.bacia = data.bacia;
            $scope.localizacao.uf = data.uf;
            $scope.localizacao.municipio = data.municipio;
            $scope.localizacao.endereco = data.endereco;

        });

        $scope.$on('criar_localizacao', function(event, data){
            $scope.localizacao.lat = "";
            $scope.localizacao.lng = "";
            $scope.localizacao.oceano = false;
            $scope.localizacao.bacia = "";
            $scope.localizacao.uf = "";
            $scope.localizacao.municipio = "";
            $scope.localizacao.endereco = "";

        });

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
