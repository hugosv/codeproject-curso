angular.module('app.controllers')
    .controller('ProjectNotesRemoveController',

    ['$scope', '$location', '$routeParams', 'ProjectNotes',
        function($scope, $location, $routeParams, ProjectNotes) {

            $scope.projectId = $routeParams.id;

            $scope.projectNotes = ProjectNotes.get({id: $routeParams.id, idNote: $routeParams.idNote});

            $scope.remove = function() {

                    $scope.projectNotes.$delete(
                        {

                            id: $scope.projectNotes.project_id,
                            idNote: $scope.projectNotes.id

                        }).then( function () {
                        $location.path('/project/' + $scope.projectId + '/notes');
                    });

                }
            }

    ]);