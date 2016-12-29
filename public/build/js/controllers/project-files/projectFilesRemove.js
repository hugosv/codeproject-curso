angular.module('app.controllers')
    .controller('ProjectFilesRemoveController',

    ['$scope', '$location', '$routeParams', 'ProjectFiles',
        function($scope, $location, $routeParams, ProjectFiles) {

            $scope.projectId = $routeParams.id;

            $scope.projectFiles = ProjectFiles.get({id: $routeParams.id, idFile: $routeParams.idFile});

            $scope.remove = function() {

                    $scope.projectFiles.$delete(
                        {

                            id: $scope.projectFiles.project_id,
                            idFile: $scope.projectFiles.id

                        }).then( function () {
                        $location.path('/project/' + $scope.projectId + '/files');
                    });

                }
            }

    ]);