'use strict';

/**
 * @ngdoc service
 * @name estatisticasApp.RestApi
 * @description
 * # RestApi
 * Factory in the estatisticasApp.
 */
angular.module('estatisticasApp')
  .factory('RestApi', function ($resource) {
    return $resource('http://10.1.8.55/moduleSiema.php?:type', {},
    // return $resource('http://localhost/moduleSiema.php?:type', {},
      {
        get: {
          method:'GET',
          params:{ format:'json' },
          isArray: true
        },
        post: {
          method:'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          isArray: true,
        },
      },
      {stripTrailingSlashes: false}
    );

  });