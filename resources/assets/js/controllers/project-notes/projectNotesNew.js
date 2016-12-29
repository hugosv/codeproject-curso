angular.module('app.controllers')
    .controller('ProjectNotesNewController',
    ['$scope', '$routeParams', '$location', 'ProjectNotes',  function($scope, $routeParams, $location, ProjectNotes) {

        $scope.projectId = $routeParams.id;
        $scope.projectNotes = new ProjectNotes();

        $scope.save = function() {
            if($scope.form.$valid) {
                ProjectNotes.save({id: $scope.projectId }, $scope.projectNotes, function () {
                    $location.path('/project/' + $scope.projectId + '/notes');
                });
            }
        }

    }]);