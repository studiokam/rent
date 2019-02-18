'use strict';

var app = angular.module('app', ['ngRoute', 'ngAnimate', 'checklist-model', 'controllersSite']);

app.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider){

	// ====================== Site Products =================

	$routeProvider.when('/list', {
		controller : 'list',
		templateUrl : 'partials/list.html'
	});

	$routeProvider.when('/options/:id', {
		controller : 'itemOptions',
		templateUrl : 'partials/options.html'
	});

	$routeProvider.when('/new', {
		controller : 'new',
		templateUrl : 'partials/new.html'
	});


	$routeProvider.when('/parameters', {
		controller : 'parameters',
		templateUrl : 'partials/parameters.html'
	});



	// ====================== Default =================

	$routeProvider.otherwise({
		redirectTo : '/list'
	});

	
}]);

