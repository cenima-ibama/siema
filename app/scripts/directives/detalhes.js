'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:detalhes
 * @description
 * # detalhes
 */
angular.module('estatisticasApp')
  .directive('detalhes', function () {
    return {
      templateUrl: 'views/accordions/detalhes.html',
      restrict: 'E',
      controller: function($scope){

        $scope.detalhes.subPanel = $scope.oleo ? '(Itens VI e VII do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

        $scope.detalhes.semDetalhe = false;
        $scope.detalhes.causa = "";
        $scope.detalhes.situacao;

        $scope.$on('carregar_detalhes', function(event, data){
            $scope.detalhes.subPanel = $scope.oleo ? '(Itens VI e VII do Anexo II do Decreto n' + String.fromCharCode(176) + ' 4.136 de 20 de fevereiro de 2002)' : '';

            if (data[0].des_causa_provavel != null && data[0].des_causa_provavel != ""){
                $scope.detalhes.causa = data[0].des_causa_provavel;
            } else {
                $scope.detalhes.semDetalhe = true;
            }

            if ($scope.oleo) {
                $scope.detalhes.situacao = data[0].situacao_atual_descarga;
            }
        });
      },
    };
  });
