angular.module('app.controllers')
    .controller('ProjectNotesEditController',
    ['$scope', '$routeParams', '$location', 'ProjectNotes',  function($scope, $routeParams, $location, ProjectNotes) {

        $scope.projectNotes = ProjectNotes.get({id: $routeParams.id, idNote: $routeParams.idNote});

        $scope.save = function() {
            if($scope.form.$valid) {
                ProjectNotes.update({id: $scope.projectNotes.project_id, idNote: $scope.projectNotes.id}, $scope.projectNotes, function () {
                    $location.path('/project/' + $scope.projectNotes.project_id + '/notes');
                });
            }
        }

    }]);