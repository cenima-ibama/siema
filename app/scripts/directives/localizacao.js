'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:localizacao
 * @description
 * # localizacao
 */
angular.module('estatisticasApp')
  .directive('localizacao', function (RestApi) {
    return {
      templateUrl: 'views/accordions/localizacao.html',
      restrict: 'E',
      controller: function($scope){

        $scope.localizacao.subPanel  = $scope.oleo ? '(Itens IV do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

        // if ($scope.oleo) {
        //     $scope.localizacao.subPanel = '(Itens IV do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        // } else {
        //     $scope.localizacao.subPanel = "";
        // }

        $scope.localizacao.lat;
        $scope.localizacao.lng;
        $scope.localizacao.oceano = false;
        $scope.localizacao.bacia;
        $scope.localizacao.uf;
        $scope.localizacao.municipio;
        $scope.localizacao.endereco;


        $("#lat").add("#lng").mask("S99" + String.fromCharCode(176) + "99\'99.99999999999", {'translation': {S: {pattern: /^-/, optional: true}}});


        $scope.localizacao.carregarMunicipios = function($uf) {
          RestApi.query({query: 'municipios', uf:$uf},
            function success(data, status){
              $scope.municipios = [];
              angular.forEach(data, function(value, key){
                $scope.municipios.push({'name' : value.nome, 'value': value.cod_ibge});
              });
            }
          );
        }



        $scope.$on('carregar_localizacao', function(event, data){
            $scope.localizacao.subPanel  = $scope.oleo ? '(Itens IV do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

            $scope.localizacao.lat = data[0].coordinate.split(' ')[0];
            $scope.localizacao.lng = data[0].coordinate.split(' ')[1];
            $scope.localizacao.oceano = data[0].id_bacia_sedimentar ? true : false;
            $scope.localizacao.bacia = data[0].id_bacia_sedimentar;
            $scope.localizacao.uf = data[0].id_uf;

            $scope.localizacao.carregarMunicipios($scope.localizacao.uf);

            $scope.localizacao.municipio = data[0].id_municipio;
            $scope.localizacao.endereco = data[0].endereco_ocorrencia;


            $scope.$broadcast('carregar_mapa', data);
        });

       //  $scope.mask = function(field) {
       //      if (field == "lat") {
       //          $($scope.localizacao.lat).mask("S99\°99\'99.999", {
       //              'translation': {
       //                  S: {
       //                      pattern: /^-/,
       //                      optional: true
       //                  }
       //              }
       //          });
       //      } else if (field == "lng"){
       //          $($scope.localizacao.lng).mask("S99\°99\'99.999", {
       //              'translation': {
       //                  S: {
       //                      pattern: /^-/,
       //                      optional: true
       //                  }
       //              }
       //          });
       //      }
       // }

        // $scope.localizacao.mudarPonto = function($campo) {
        //     console.log($scope.localizacao.acidente);
        // };

        // $scope.$on('mudar_latlng', function(event,data){
        //     $scope.localizacao.lat = data.lat;
        //     $scope.localizacao.lng = data.lng;

        //     $scope.$digest();
        // });

        // $scope.$on('criar_localizacao', function(event, data){
        //     $scope.localizacao.lat = "";
        //     $scope.localizacao.lng = "";
        //     $scope.localizacao.oceano = false;
        //     $scope.localizacao.bacia = "";
        //     $scope.localizacao.uf = "";
        //     $scope.localizacao.municipio = "";
        //     $scope.localizacao.endereco = "";

        // });

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
