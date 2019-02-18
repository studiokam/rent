'use strict';

var controllersSite = angular.module('controllersSite', ['ngAnimate', 'angularFileUpload']); 


controllersSite.controller('list', ['$scope', '$http',  function($scope, $http){

	// pobranie przedmiotów
	$scope.data = $http.get('api/products/get').
	success(function(data){
		$scope.itemList = data;
		$scope.predicate = 'name'; // sort start	
	});

}]);



controllersSite.controller('parameters', ['$scope', '$http', '$timeout', function($scope, $http, $timeout){

	// pobranie parametrów
	$scope.data = function(){
		$http.get('api/parameters/get').success(function(data){
			$scope.parameters = data;
		});
	};
	$scope.data();
	
	
	// dodanie nowego parametru
	$scope.createParameter = function(parameter) {

		$http.post('api/parameters/create/', {
			parameter: parameter
		}).success(function(data){

			if (data == 'paramExists') {
				$scope.error = true;
				$timeout(function(){
					$scope.error = false;
					$scope.parameter = {};
					$scope.create.$setUntouched();
				}, 2000);
			} else {

				$scope.success = true;
				$scope.parameters.push(parameter);

				$timeout(function(){
					$scope.success = false;
					$scope.parameter = {};
					$scope.create.$setUntouched();
				}, 500);
			}

		});
	};

	//usunięcie parametru
	$scope.delete = function(parameter, $index){

		$scope.parameters.splice($index, 1);
		$http.post('api/parameters/delete/',{
			parameter : parameter
		});

	};


}]);



controllersSite.controller('new', ['$scope', '$http', '$timeout', '$location', function($scope, $http, $timeout, $location){

	// pobranie parametórw
	$scope.data = $http.get('api/parameters/get').
	success(function(data){
		$scope.params = data;
	});

	let takeParams = $scope.take = {
		params: []
	};

	// dodanie nowego produktu
	$scope.createItem = function(itemCreate) {

		$http.post('api/products/create/', {
			product: {
				name: itemCreate.name,
				parameters: angular.toJson(takeParams)
			}
		}).success(function(){
			$scope.success = true;

			$timeout(function(){
				$scope.success = false;
				$scope.product = {};
				$scope.take = {};
				$scope.create.$setUntouched();
			}, 2500);


		}).error(function(){
			console.log('Bład komunikacji z API');
		});
	};
	
	
}]);




controllersSite.controller('itemOptions', ['$scope', '$http', '$timeout', '$routeParams', '$location', 'FileUploader', function($scope, $http, $timeout, $routeParams, $location, FileUploader){

	let itemId = $routeParams.id;
	$scope.id = itemId;

	// pobranie danych przedmiotu do edycji
	$scope.data = function(){
		$http.get('api/products/get/' + itemId).
		success(function(data){
			$scope.products = data;
			$scope.take = JSON.parse(data.parameters);
		});

	};
	$scope.data();

	// pobranie parametrów
	$scope.par = $http.get('api/parameters/get').
	success(function(data){
		$scope.params = data;
	});

	// pobranie historii dzierżaw
	$scope.historyGet = function(){
		$http.get('api/products/gethistory/' + itemId).success(function(historyGet){
			$scope.history = historyGet;
		});
	};
	$scope.historyGet();

	// usunięcie pzedmiotu
	$scope.delete = function(){
		if (!confirm('Potwierdź usunięcie tego przedmiotu')) 
			return false;

		$http.get('api/products/delete/' + itemId).success(function(){
			$location.path('/list');
		});
	};


	// potwierdzenie powrotu przedmiotu na magazyn
	$scope.itemReturnConfirm = function(){
		$http.post('api/products/status_upp/', {
			status:{
				id: itemId,
				status: 0
			} 
		}).success(function(){
			$scope.data();
		}).error(function(){
			console.log('Bład komunikacji z API');
		});
	};


	// wypowiedzenie przed czasem
	$scope.itemTermination = function(){
		$http.post('api/products/item_termination/', {
			status:{
				id: itemId,
				status: 2
			} 
		}).success(function(){
			$scope.data();
		}).error(function(){
			console.log('Bład komunikacji z API');
		});
	};


	// aktualizacja przedmiotu 
	$scope.itemUpdate = function(itemUpp){
		$http.post('api/products/update/', {
			product: {
				id: itemId,
				name: itemUpp.name,
				parameters: angular.toJson($scope.take)
			}
		}).success(function(){
			$scope.success = true;

			$timeout(function(){
				$scope.success = false;
			}, 1000);

		}).error(function(){
			console.log('Bład komunikacji z API');
		});
	};


	// wydanie przedmiotu (nowa dzierżawa) 
	$scope.rent = {};
	$scope.itemNewRent = function(rent){
		
		let day = rent.rentDate.getDate();
		let month = rent.rentDate.getMonth() + 1;
		let year = rent.rentDate.getUTCFullYear();

		if (day < 10) { day = '0' + day};
		if (month < 10) { month = '0' + month};

		let rentDate = year + '-' + month + '-' + day + ' 15:00:00';

		$http.post('api/products/rent/', {
			rents: {
				productId : itemId,
				rentDate: rentDate,
				days: rent.days,
				price: rent.price
			}
		}).success(function(data){
			if (data == 'timeStartPlusDaysError') {
				$scope.alerts = {type: 'warning', msg: 'Bład'};
			} 
			else 
			{
				$scope.successRent = true;
				$timeout(function(){
					$scope.successRent = false;
					$scope.rent = {};
					$scope.rentForm.$setUntouched()

					$scope.historyGet();
					$scope.data();
					$scope.alerts = false;

				}, 500);
			}

		}).error(function(){
			console.log('Bład komunikacji z API');
		});
	};



	// zdjecia
	function getImages() {
		$http.get('api/images/get/' + itemId).
		success(function(data){
			$scope.images = data;
		});
		
	}
	getImages();

	var uploader = $scope.uploader = new FileUploader({
		url: 'api/images/upload/' + itemId
	});

	uploader.filters.push({
	    name: 'imageFilter',
	    fn: function(item /*{File|FileLikeObject}*/, options) {
	        var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
	        return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        }
	});

	uploader.onCompleteItem = function(fileItem, response, status, headers) {
        getImages();  
	};

	$scope.delImage = function(imageName, $index){
		$scope.images.splice($index, 1);

		$http.post('api/images/delete/',{
			id : itemId,
			image : imageName
		});
	};	

	$scope.setThumb = function ( product , image ) {

		$http.post( 'api/images/setThumb/' , {

			product : product,
			image : image

		}).success(function(data){
			$scope.data();
		}).error( function(){
			console.log( 'Błąd połączenia z API' );
		});

    };
	// zdiecia end

	
}]);