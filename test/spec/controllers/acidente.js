'use strict';

describe('Controller: AcidenteCtrl', function () {

  // load the controller's module
  beforeEach(module('estatisticasApp'));

  var AcidenteCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    AcidenteCtrl = $controller('AcidenteCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
