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
    'ui.bootstrap',
    'ngFileUpload'
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
      .when('/html', {
        templateUrl: 'views/html.html',
        controller: 'PdfCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
