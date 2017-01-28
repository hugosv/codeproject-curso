angular.module('app.controllers')
    .controller('MemberProjectsDashboard', ['$scope', '$location', '$routeParams', 'Project', 'appConfig',
        function ($scope, $location, $routeParams, Project, appConfig) {

            $scope.status = appConfig.project.status;

           $scope.projects = {
            };

            Project.memberProjects({
                orderBy: 'created_at',
                sortedBy: 'desc',
                limit: 15
            }, function (response) {
                $scope.projects = response.data;
            });

            $scope.showProject = function (project) {
                $scope.project = project;
            };

        }]);