angular.module('app.controllers')
    .controller('ProjectListController', ['$scope', 'Project',  function($scope, Project) {
        $scope.project = Project.query();
    }]);