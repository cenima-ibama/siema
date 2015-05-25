'use strict';
/**
 * @ngdoc directive
 * @name estatisticasApp.directive:arquivos
 * @description
 * # arquivos
 */
angular.module('estatisticasApp')
  .directive('arquivos', function () {
    return {
      templateUrl: 'views/accordions/arquivos.html',
      restrict: 'E',
      controller: function($rootScope, $scope, Upload){

          $scope.showFileName = function(files) {
            $scope.file = files;
            if (files && files.length) {
              $scope.name = files[0].name;
            }
          }

          $scope.upload = function (files) {
            if (files && files.length) {
              $scope.file = files;
              for (var i = 0; i < files.length; i++) {
                var file = files[i];
                $scope.name = files[i].name;
                file.ocurr = $scope.nro_ocorrencia;
                Upload.upload({
                  'url': 'http://10.1.8.139/upload.php',
                  'data': $scope.nro_ocorrencia,
                  'file': file
                }).success(function (data, status, headers, config) {
                    console.log('Arquivo ' + config.file.name + ' upado com sucesso. Retorno: ' + data);
                    $rootScope.arquivoSuccess = data;
                });
              }
            }
          }

          $scope.limpArquivo = function(files){
            $scope.file = null;
            $scope.files = null;
          }
        }
      }
    });
