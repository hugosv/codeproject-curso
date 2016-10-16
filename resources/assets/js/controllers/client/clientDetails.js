angular.module('app.controllers')
    .controller('ClientDetailsController',
    ['$scope', '$routeParams', 'Client',

        function($scope, $routeParams, Client) {

            $scope.client = Client.get({id: $routeParams.id});

        }
    ]);