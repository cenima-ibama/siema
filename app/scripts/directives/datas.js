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

        $scope.datas.subPanel = $scope.oleo ? '(Itens II e III do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

        // if ($scope.oleo) {
        //     $scope.datas.subPanel = '(Itens II e III do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)';
        // } else {
        //     $scope.datas.subPanel = "";
        // }

        $scope.datas.diaObservacao = '';
        $scope.datas.horaObservacao = '';
        $scope.datas.obsSemana = '0';
        $scope.datas.obsPeriodo = '';
        $scope.datas.semObservacao = false;

        $scope.datas.diaIncidente = '';
        $scope.datas.horaIncidente = '';
        $scope.datas.incSemana = '0';
        $scope.datas.incPeriodo = '';
        $scope.datas.semIncidente = false;

        $scope.updateSemana = function($dia, $semana) {
          var temp = $scope.datas[$dia].split('/');
          $scope.datas[$semana] = (new Date(temp[2],temp[1] - 1,temp[0]).getDay() + 1).toString();
          // $scope.$apply();
        }

        $scope.getPeriodoObs = function(hora){
            var hr = hora.split(':');
            hr[0] = parseInt(hr[0]); 
            if(hr[0] >= 0 && hr[0] < 6)
                $scope.datas.obsPeriodo = 'S';
            else if(hr[0] >= 6 && hr[0] < 12)
                $scope.datas.obsPeriodo = 'M';
            else if(hr[0] >= 12 && hr[0] < 18)
                $scope.datas.obsPeriodo = 'V';
            else if(hr[0] >= 18 && hr[0] <= 23)
                $scope.datas.obsPeriodo = 'N';

        }

        $scope.getPeriodoInc = function(hora){
            var hr = hora.split(':');
            hr[0] = parseInt(hr[0]); 
            if(hr[0] >= 0 && hr[0] < 6)
                $scope.datas.incPeriodo = 'S';
            else if(hr[0] >= 6 && hr[0] < 12)
                $scope.datas.incPeriodo = 'M';
            else if(hr[0] >= 12 && hr[0] < 18)
                $scope.datas.incPeriodo = 'V';
            else if(hr[0] >= 18 && hr[0] <= 23)
                $scope.datas.incPeriodo = 'N';

        }

        $scope.$on('carregar_datas', function(event, data){
            $scope.datas.subPanel = $scope.oleo ? '(Itens II e III do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

            var temp;
            if (data[0].dt_primeira_obs != null) {
                temp = data[0].dt_primeira_obs.split("-");
                $scope.datas.diaObservacao = temp[2] + "/" + temp[1] + "/" + temp[0];
                $scope.updateSemana('diaObservacao','obsSemana');
                $scope.datas.horaObservacao = data[0].hr_primeira_obs;
                $scope.datas.obsPeriodo = data[0].periodo_primeira_obs;
                $scope.datas.semObservacao = false;
            } else {
                $scope.datas.semObservacao = true;
            }


            if (data[0].dt_ocorrencia != null){
                temp = data[0].dt_ocorrencia.split("-");
                $scope.datas.diaIncidente = temp[2] + "/" + temp[1] + "/" + temp[0];
                $scope.updateSemana('diaIncidente','incSemana');
                $scope.datas.horaIncidente = data[0].hr_ocorrencia;
                $scope.datas.incPeriodo = data[0].periodo_ocorrencia;
                $scope.datas.feriado = data[0].dt_ocorrencia_feriado;
                $scope.datas.semIncidente = false;
            } else {
                $scope.datas.semIncidente = true;
            }

        });

        // $scope.$on('criar_datas', function(event, data){
        //     $scope.datas.diaObservacao = "";
        //     $scope.datas.horaObservacao = "";
        //     $scope.datas.obsSemana = "";
        //     $scope.datas.obsPeriodo = "";
        //     $scope.datas.semObservacao = false;


        //     $scope.datas.diaIncidente = "";
        //     $scope.datas.horaIncidente = "";
        //     $scope.datas.incSemana = "";
        //     $scope.datas.incPeriodo = "";
        //     $scope.datas.semIncidente = false;

        // });

        // $scope.updateObsSemana = function() {
        //   var temp = $scope.datas.diaObservacao.split('/');
        //   $scope.datas.obsSemana = new Date(temp[2],temp[1] - 1,temp[0]).getDay() + 1;
        // }

      },
    };
  });
