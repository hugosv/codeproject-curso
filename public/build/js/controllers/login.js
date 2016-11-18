angular.module('app.controllers')
    .controller('LoginController', ['$scope', '$location', '$cookies', 'User', 'OAuth',
        function ($scope, $location, $cookies, User, OAuth) {
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
                    // Esse trecho grava os cookies do usuário ao efetuar o login
                    // Se obtiver sucesso no login, então registra o cookie e redireciona para a home
                    User.authenticated({},{}, function(data) {
                        $cookies.putObject('user', data);
                        $location.path('home');
                    });

                }, function (data) {
                    $scope.error.error = true;
                    $scope.error.message = data.data.error_description;
                });
            }
        };
    }]);

