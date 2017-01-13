angular.module('app.controllers')
    .controller('ProjectDetailsController',
    ['$scope', '$routeParams', 'Project', 'appConfig',

        function($scope, $routeParams, Project, appConfig) {

            $scope.project = Project.get({id: $routeParams.id});
            // $scope.client = Client.get({id: $scope.project.client_id});
            $scope.status = appConfig.project.status[0];

        }

    ]);