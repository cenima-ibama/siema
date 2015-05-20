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

        $scope.upload = function (files) {
            if (files && files.length) {
              for (var i = 0; i < files.length; i++) {
                var file = files[i];
                Upload.http({
                  url: '//10.1.8.139/upload.php',
                  transformRequest: angular.identity,
                  headers : {
                      'Content-Type': file.type
                  },
                  data: file,
                })

                  // Upload.upload({
                  //     url: '//localhost/upload.php',
                  //     // fields: {'username': $scope.username},
                  //     file: file
                  // }).progress(function (evt) {
                  //     var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                  //     console.log('progress: ' + progressPercentage + '% ' + evt.config.file.name);
                  // }).success(function (data, status, headers, config) {
                  //     console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
                  // });
                }
            }
        };

        console.log('isOn directive');
        $scope.uploadPic = function(files) {
          $scope.formUpload = true;
          if (files != null) {
            $scope.upload(files);
          }
        };
      },

    };
  });
