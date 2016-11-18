/**
 * Created by hugo on 9/24/16.
 */

var app = angular.module('app', [
    'ngRoute',
    'angular-oauth2',
    'app.controllers',
    'app.filters',
    'app.services'
]);

angular.module('app.controllers', ['ngMessages', 'angular-oauth2']);
angular.module('app.filters', []);
angular.module('app.services', ['ngResource']);


app.provider('appConfig', function(){
   var config = {
       baseUrl: 'http://codeproject.app',
       project:{
           status: [
               { value: 1, label: 'Não Iniciado'},
               { value: 2, label: 'Iniciado'},
               { value: 3, label: 'Concluído'}
           ]
       },
       utils: {
           /**
            * Trecho responsável por serializar a resposta do servidor para um formato entendível pelo angular.
            * O que faz é colocar o conteúdo do array data dentro da própria variável que o Angular está esperando ler
            * Aplicando esse trecho aqui, a solução fica disponível em todo o projeto automaticamente.
            **/
           transformResponse: function(data, headers) {
               var headersGetter = headers();
               if(headersGetter['content-type'] == 'application/json' || headersGetter['content-type'] == 'text/json')
               {
                   var dataJson = JSON.parse(data);
                   if(dataJson.hasOwnProperty('data')) {
                       dataJson = dataJson.data;
                   }
                   return dataJson;
               }
               return data;
           }
       }
   };

   return {
       config: config,
       $get: function () {
           return config;
       }
   };

});

app.config([

    '$routeProvider', '$httpProvider', 'OAuthProvider', 'OAuthTokenProvider', 'appConfigProvider',

    function ($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider) {

        $httpProvider.defaults.transformResponse = appConfigProvider.config.utils.transformResponse;

        $routeProvider
            .when('/login', {
                templateUrl: 'build/views/login.html',
                controller: 'LoginController'
                })

            .when('/home', {
                templateUrl: 'build/views/home.html',
                controller: 'HomeController'
              })

            // clients
            .when('/clients', {
                templateUrl: 'build/views/client/list.html',
                controller: 'ClientListController'
            })
            .when('/clients/new', {
                templateUrl: 'build/views/client/new.html',
                controller: 'ClientNewController'
            })
            .when('/clients/:id', {
                templateUrl: 'build/views/client/details.html',
                controller: 'ClientDetailsController'
            })
             .when('/clients/:id/edit', {
                templateUrl: 'build/views/client/edit.html',
                controller: 'ClientEditController'
            })
            .when('/clients/:id/remove', {
                templateUrl: 'build/views/client/remove.html',
                controller: 'ClientRemoveController'
            })

            // project notes
            .when('/project/:id/notes', {
                templateUrl: 'build/views/project-notes/list.html',
                controller: 'ProjectNotesListController'
            })
            .when('/project/:id/notes/new', {
                templateUrl: 'build/views/project-notes/new.html',
                controller: 'ProjectNotesNewController'
            })
            .when('/project/:id/notes/:idNote/show', {
                templateUrl: 'build/views/project-notes/details.html',
                controller: 'ProjectNotesDetailsController'
            })
            .when('/project/:id/notes/:idNote/edit', {
                templateUrl: 'build/views/project-notes/edit.html',
                controller: 'ProjectNotesEditController'
            })
            .when('/project/:id/notes/:idNote/remove', {
                templateUrl: 'build/views/project-notes/remove.html',
                controller: 'ProjectNotesRemoveController'
            })

            // Project
            .when('/project/', {
                templateUrl: 'build/views/project/list.html',
                controller: 'ProjectListController'
            })
            .when('/project/new', {
                templateUrl: 'build/views/project/new.html',
                controller: 'ProjectNewController'
            })
            .when('/project/:id', {
                templateUrl: 'build/views/project/details.html',
                controller: 'ProjectDetailsController'
            })
            .when('/project/:id/edit', {
                templateUrl: 'build/views/project/edit.html',
                controller: 'ProjectEditController'
            })
            .when('/project/:id/remove', {
                templateUrl: 'build/views/project/remove.html',
                controller: 'ProjectRemoveController'
            });

        OAuthProvider.configure({
            baseUrl: appConfigProvider.config.baseUrl,
            clientId: 'appid1',
            clientSecret: 'secret', // optional
            grantPath: 'oauth/access_token'
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false
            }
        })

    }
]);

app.run(['$rootScope', '$window', 'OAuth', function($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function(event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);