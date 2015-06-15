'use strict';

describe('Directive: acoes', function () {

  // load the directive's module
  beforeEach(module('estatisticasApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<acoes></acoes>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the acoes directive');
  }));
});
