'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:pie
 * @description
 * # pie
 */

angular.module('estatisticasApp')
  .directive('eventosmes', function () {
    return {
      template: '<canvas class="chart-pie chart-stats" data="eventosMesData" labels="eventosMesLabels" legend="true" options="eventosMesOptions"></canvas> ',
      restrict: 'E',
      link: function postLink(scope, element, attrs) {

        scope.$on('load_eventosmes', function(event, ret){

          var data = ret.data;
          var dado = [];
          var sum = 0;
          var serie = ret.ano;
          var month = ret.mes;
          var months = ['','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
          var areas = ['NO','NE','CO','SE','SU',''];

          dado[0] = ['Norte', 'Nordeste', 'Centro-Oeste', 'Sudeste', 'Sul', 'Sem Região Cadastrada'];
          dado[1] = [];




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
                    if ((date[0] == serie) && (months[parseInt(date[1])] == month) && (reg.regiao == areas[a])) {
                      return sum++;
                    }
                  } else if ((date[0] == serie) && (reg.regiao == areas[a]) && (date[1] == month) &&
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

          //     if((date[0] == serie) && (date[1] == month) && (data[i].regiao == areas[r])) {
          //       sum++;
          //     }
          //   }

          //   dado[1].push(sum);
          // }

          scope.chart4 =  month + ', ' + serie + ': Todos Tipos de Eventos : Todos Tipos de Origens';
          scope.eventosMesLabels = dado[0];
          scope.eventosMesData = dado[1];

          scope.eventosMesOptions = {
            animationSteps: 5
          };

        });

      }
    };
  });
