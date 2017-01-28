angular.module('app.controllers')
    .controller('HomeController', ['$scope', '$cookies', 'Project', 'appConfig', function ($scope, $cookies, Project, appConfig) {
        console.log($cookies.getObject('user').id);
        console.log($cookies.getObject('user').email);

        $scope.status = appConfig.project.status;

        Project.query({
            orderBy: 'created_at',
            sortedBy: 'desc',
            limit: 15
        }, function (response) {
            $scope.projects = response.data;
        });

    }]);