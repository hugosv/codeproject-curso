angular.module('app.controllers')
    .controller('ProjectNewController',
        ['$scope', '$location', '$cookies', 'Project', 'Client', 'appConfig',
            function($scope, $location, $cookies, Project, Client, appConfig) {

                $scope.project = new Project();
                $scope.clients = Client.query();
                $scope.status = appConfig.project.status;

                $scope.save = function() {
                    if($scope.form.$valid) { // Se o formulário está válido
                        // Então aplica o id do usuário, senão não retorna nada.
                        $scope.project.owner_id = $cookies.getObject('user').id;
                        $scope.project.$save().then(function () {
                            $location.path('/project');
                        });
                    }
                }
        }]);