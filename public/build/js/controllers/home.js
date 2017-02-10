angular.module('app.controllers')
    .controller('HomeController', ['$scope', '$cookies', 'Project', 'appConfig', '$pusher', '$timeout',
        function ($scope, $cookies, Project, appConfig, $pusher, $timeout) {

        $scope.status = appConfig.project.status;

        Project.query({
            orderBy: 'created_at',
            sortedBy: 'desc',
            limit: 15
        }, function (response) {
            $scope.projects = response.data;
        });

        $scope.tasks = [];
        var pusher = $pusher(window.client);
        var channel = pusher.subscribe('user.' + $cookies.getObject('user').id);

        channel.bind('CodeProject\\Events\\TaskWasIncluded',
            function (data) {
                data.task.msg = 'Uma nova tarefa foi incluída'
                data.task.msg_time = 'Há alguns segundos';

                $scope.insertNotificationInPanel(data);
            }
        );

        channel.bind('CodeProject\\Events\\TaskWasChanged',
            function (data) {
                var acao = 'alterada';

                if(data.task.status == 3){
                    acao = 'concluída';
                }

                data.task.msg = 'Uma tarefa foi ' + acao + '!';
                data.task.msg_time = 'Há alguns segundos';

                $scope.insertNotificationInPanel(data);
            }
        );

        $scope.insertNotificationInPanel = function(data){
            if($scope.tasks.length == 6) {
                $scope.tasks.splice($scope.tasks.length-1, 1);
            }
            
            console.log($scope.tasks);

            $timeout(function () {
                $scope.tasks.unshift(data.task);
            }, 1000);
        };

    }]);