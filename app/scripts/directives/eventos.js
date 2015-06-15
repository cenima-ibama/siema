'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:pie
 * @description
 * # pie
 */

angular.module('estatisticasApp')
  .directive('eventos', function () {
    return {
      template: '<canvas class="chart-pie chart-stats" data="eventosData" labels="eventosLabels" legend="true" options="eventosOptions"></canvas> ',
      restrict: 'E',
      link: function postLink(scope, element, attrs) {

        scope.$on('load_eventos', function(event, ret){

          var data = ret.data;
          var dado = [];
          var sum = 0;
          // var serie = new Date().getFullYear();
          var serie = ret.ano;
          var areas = ['NO','NE','CO','SE','SU',''];

          dado[0] = ['Norte', 'Nordeste', 'Centro-Oeste', 'Sudeste', 'Sul', 'Sem Região Cadastrada'];
          dado[1] = [];


          // var firstYear = 2007;
          // var lastYear = new Date().getFullYear();


          // for (var i = lastYear; (i >= firstYear) && (dado[0].length < numberOfElements); i--) {
          //   if (i <= ret.ano) {
          //     dado[0].push(i);
          //   }
          // }

          for (var a=0; a < areas.length;a++){
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
                    if ((date[0] == serie) && (reg.regiao == areas[a])) {
                      return sum++;
                    }
                  } else if ((date[0] == serie) && (reg.regiao == areas[a]) &&
                    (reg.origens.indexOf(ret.origem) >= 0 || ret.origem === "Todos") &&
                    (reg.eventos.indexOf(ret.evento) >= 0 || ret.evento === "Todos")) {
                      return sum++;
                  }
                });
            });


            dado[1].push(sum);
          }


          // for(var r=0; r < areas.length; r++){
          //   var sum = 0;
          //   for(var i = 0; i<data.length; i++){
          //     var date = null;
          //     if (data[i].dt_ocorrencia != null) {
          //       date = data[i].dt_ocorrencia.split('-');
          //     } else {
          //       date = data[i].dt_registro.split('-');
          //     }

          //     if((date[0] == serie) && (data[i].regiao == areas[r])) {
          //       sum++;
          //     }
          //   }

          //   dado[1].push(sum);
          // }

          scope.chart3 =  serie + ' : Todos Tipos de Eventos : Todos Tipos de Origens';
    		  scope.eventosLabels = dado[0];
    		  scope.eventosData = dado[1];

          scope.eventosOptions = {
            animationSteps: 5
          };
        });
      }
    };
  });
