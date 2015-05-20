'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:file
 * @description
 * # file
 */
angular.module('estatisticasApp')
  .directive('file', function () {
    return {
        scope: {
            file: '='
        },
        link: function(scope, el, attrs){
            el.bind('change', function(event){
                var files = event.target.files;
                var file = files[0];
                scope.file = file ? file.name : undefined;
                scope.$apply();
            });
        }
    };
  });
