'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:line
 * @description
 * # line
 */
angular.module('estatisticasApp')
  .directive('mensal', function (RestApi,$rootScope) {
    return {
      template: '<canvas class="chart-line chart-stats" data="mensalData" labels="mensalLabels" legend="true" series="mensalSeries" options="mensalOptions"></canvas> ',
      restrict: 'E',
      link: function postLink(scope, element, attrs) {

        scope.$on('load_mensal', function(event, ret){

          var dado = [];
          var sum = 0;
          var origens = [];
          var eventos = [];
          var regions;
          var numberOfElements = scope.numberOfElements;
          var data = ret.data;

          dado[0] = [];
          dado[1] = [];

          var firstYear = 2007;
          var lastYear = new Date().getFullYear();


          for (var i = lastYear; (i >= firstYear) && (dado[0].length < numberOfElements); i--) {
            if (i <= ret.ano) {
              dado[0].push(i);
            }
          }


          for (var y=0; y < dado[0].length;y++){
            dado[1][y] = []
            for (var m = 1; m <= 12; m++){
              sum = 0;

              $.each(ret.data, function(key, region) {
                if ((ret.regiao == 'BRASIL') || (key == ret.regiao))
                  return $.each(region, function(key, reg) {
                    var date = null;

                    if (reg.dt_ocorrencia != null) {
                      date = reg.dt_ocorrencia.split('-');
                    } else {
                      date = reg.dt_registro.split('-');
                    }

                    if (ret.evento === "Todos" && ret.origem === "Todos") {
                      if ((date[0] == dado[0][y]) && (date[1] == m)) {
                        return sum++;
                      }
                    } else if ((date[0] == dado[0][y]) && (date[1] == m) &&
                      (reg.origens.indexOf(ret.origem) >= 0 || ret.origem === "Todos") &&
                      (reg.eventos.indexOf(ret.evento) >= 0 || ret.evento === "Todos")) {
                        return sum++;
                    }
                  });
              });


              dado[1][y].push(sum);
            }
          }

          // for(var y=0; y < dado[0].length; y++){
          //   dado[1][y] = []
          //   for (var m = 1; m <= 12; m++){
          //     sum = 0;

          //     for(var i = 0; i<data.length; i++){
          //       var date = null;
          //       if (data[i].dt_ocorrencia != null) {
          //         date = data[i].dt_ocorrencia.split('-');
          //       } else {
          //         date = data[i].dt_registro.split('-');
          //       }

          //       if ((eventos.indexOf(ret.tipo) != -1) || (ret.tipo == 'Todos')) {
          //         if ((origens.indexOf(ret.origem) != -1) || (ret.origem == 'Todos')) {
          //           if ((ret.regiao == data[i].regiao) || (ret.regiao == 'BRASIL')) {
          //             if((date[0] == dado[0][y]) && (date[1] == m)){
          //               sum++;
          //             }
          //           }
          //         }
          //       }
          //     }

          //     dado[1][y].push(sum);
          //   }
          // }



          //Broadcasting data para o escopo global
          //Por que este servico retorna o dado para um escopo diferente
          // scope.$broadcast('load_mensal', dado);
          // for (var i=0; i<data.length; i++){

          // }

          var months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

          scope.chart1 = 'Acidentes: Índices Mensal';
          scope.mensalLabels = months;
          scope.mensalSeries = dado[0].slice(0,numberOfElements);


          scope.mensalOptions = {
            animationSteps: 5,
            bezierCurve : false
          };

          scope.mensalData = dado[1].slice(0,numberOfElements);


        });
      }
    };
  });
