'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:mensal
 * @description
 * # mensal
 */
angular.module('estatisticasApp')
  .directive('instituicoes', function (RestApi) {
    return {
      template: '<canvas class="chart-bar chart-stats" data="instituicoesData" labels="instituicoesLabel" legend="true" series="instituicoesSeries" options="instituicoesOptions"></canvas>',
      restrict: 'E',
      link: function postLink(scope, element, attrs) {

        scope.$on('load_instituicoes', function(event, ret){


          var data = ret.data;
          var dado = [];
          var sum = 0;
          var numberOfElements = scope.numberOfElements;
          var instLocal = ["IBAMA","Órgão Estadual ou Municipal de Meio Ambiente","Defesa Civil","Corpo de Bombeiros","Polícia Rodoviária","Polícia Militar","Polícia Civil","Marinha do Brasil","Empresa especializada em atendimento", "Outra(s)", "Todos"];

          dado[0] = [];
          dado[1] = [];

          var firstYear = 2007;
          var lastYear = new Date().getFullYear();

          for (var i = lastYear;(i >= firstYear) && (dado[0].length < numberOfElements); i--) {
            if (i <= ret.ano) {
              dado[0].push(i);
            }
          };


          for (var y=0; y < dado[0].length;y++){
            dado[1][y] = []
            for (var a = 0; a < instLocal.length; a++) {
              sum = 0;

              $.each(ret.data, function(key, region) {
                if ((ret.regiao == 'BRASIL') || (key == ret.regiao)) {
                  return $.each(region, function(key, reg) {
                    var date = null;

                    if (reg.dt_ocorrencia != null) {
                      date = reg.dt_ocorrencia.split('-');
                    } else {
                      date = reg.dt_registro.split('-');
                    }

                    var institutions = reg.instituicoes;
                    if (ret.evento === "Todos" && ret.origem === "Todos") {
                      if ((date[0] == dado[0][y])) {
                        if  (institutions.indexOf(instLocal[a]) != -1) {
                          return sum++;
                        }
                      }
                    } else if ((date[0] == dado[0][y]) &&
                      (reg.origens.indexOf(ret.origem) >= 0 || ret.origem === "Todos") &&
                      (reg.eventos.indexOf(ret.evento) >= 0 || ret.evento === "Todos")) {
                        if  (institutions.indexOf(instLocal[a]) != -1) {
                          return sum++;
                        }
                    }
                  });
                }
              });


              dado[1][y].push(sum);
            }
          }

          // for(var y=0; y < dado[0].length; y++) {
          //   dado[1][y] = []
          //   for (var a = 0; a < instLocal.length; a++) {
          //     sum = 0;
          //     for(var i = 0; i<data.length; i++){
          //       var date = null;
          //       if (data[i].dt_ocorrencia != null) {
          //         date = data[i].dt_ocorrencia.split('-');
          //       } else {
          //         date = data[i].dt_registro.split('-');
          //       }

          //       if(date[0] == dado[0][y]){
          //         var institutions = data[i].institiuicoes_atuando_local.replace(/[\"{}]+/g, '').split(",");
          //         for (var j = 0; j < institutions.length; j++) {
          //           if  (institutions[j] == instLocal[a]) {
          //             sum++;
          //           }
          //         };
          //       }
          //     }

          //     dado[1][y][a] = sum;
          //   }
          // }

          scope.chart2 = 'Número de Acidentes Atendidos por Instituições';
      		scope.instituicoesLabel = ['IBAMA', 'OEMMA', 'DC', 'CB', 'PRF', 'PM', 'PC','Marinha', 'EEA', 'Outra(s)'];
      		scope.instituicoesSeries = dado[0].slice(0,numberOfElements);

          scope.instituicoesOptions = {
            animationSteps: 5
          };

      		scope.instituicoesData = dado[1].slice(0,numberOfElements);
        });
      }
    };
  });
