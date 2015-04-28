'use strict';

/**
 * @ngdoc overview
 * @name estatisticasApp
 * @description
 * # estatisticasApp
 *
 * Main module of the application.
 */
angular
  .module('estatisticasApp', [
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'chart.js',
    'ngMask',
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'ChartsCtrl'
      })
      .when('/acidente', {
        templateUrl: 'views/acidente.html',
        controller: 'AcidenteCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
