angular.module('app.controllers')
    .controller('LoginController', ['$scope', '$location', 'OAuth',  function ($scope, $location, OAuth) {
        $scope.user = {
            username: '',
            password: ''
        };

        $scope.error = {
            message: '',
            error: false
        };

        $scope.login = function() {
            if($scope.form.$valid) {
                OAuth.getAccessToken($scope.user).then(function () {

                    $location.path('home');

                }, function (data) { //variavel data com o retorno do servidor

                    $scope.error.error = true;
                    // data.error_description = undefined;
                    $scope.error.message = data.data.error_description;

                });
            }
        };
    }]);

