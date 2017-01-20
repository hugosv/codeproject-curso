<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @if(Config::get('app.debug'))
        <link rel="stylesheet" href="{{ asset('build/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('build/css/components.css') }}">
        <link rel="stylesheet" href="{{ asset('build/css/flaticon.css') }}">
        <link rel="stylesheet" href="{{ asset('build/css/font-awesome.css') }}">
    @else
        <link rel="stylesheet" href="{{ elixir('css/all.css') }}">

    @endif

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Laravel</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/#/login') }}">Login</a></li>
                    <li><a href="{{ url('/#/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/#/logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{--@yield('content')--}}

<div ng-view> </div>

<!-- Scripts -->
@if(Config::get('app.debug'))
    <script src="{{ asset('build/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-route.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-resource.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-animate.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-messages.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/ui-bootstrap-tpls.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/navbar.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-cookies.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/query-string.js') }}"></script>
    <script src="{{ asset('build/js/vendor/angular-oauth2.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/ng-file-upload-all.min.js') }}"></script>
    <script src="{{ asset('build/js/vendor/http-auth-interceptor.js') }}"></script>


    <script src="{{ asset('build/js/app.js') }}"></script>

    {{--CONTROLLERS--}}
    <script src="{{ asset('build/js/controllers/login.js') }}"></script>
    <script src="{{ asset('build/js/controllers/loginModal.js') }}"></script>
    <script src="{{ asset('build/js/controllers/home.js') }}"></script>

    <!-- CONTROLLERS CLIENT -->
    <script src="{{ asset('build/js/controllers/client/clientList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/client/clientDetails.js') }}"></script>
    <script src="{{ asset('build/js/controllers/client/clientNew.js') }}"></script>
    <script src="{{ asset('build/js/controllers/client/clientEdit.js') }}"></script>
    <script src="{{ asset('build/js/controllers/client/clientRemove.js') }}"></script>

    <!-- CONTROLLERS PROJECT -->
    <script src="{{ asset('build/js/controllers/project/projectList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project/projectDetails.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project/projectNew.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project/projectEdit.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project/projectRemove.js') }}"></script>

    <!-- CONTROLLERS PROJECT NOTE -->
    <script src="{{ asset('build/js/controllers/project-notes/projectNotesList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-notes/projectNotesNew.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-notes/projectNotesDetails.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-notes/projectNotesEdit.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-notes/projectNotesRemove.js') }}"></script>

    <!-- CONTROLLERS PROJECT FILE -->
    <script src="{{ asset('build/js/controllers/project-files/projectFilesList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-files/projectFilesNew.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-files/projectFilesEdit.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-files/projectFilesRemove.js') }}"></script>

    <!-- CONTROLLERS PROJECT TASK -->
    <script src="{{ asset('build/js/controllers/project-task/projectTaskList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-task/projectTaskNew.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-task/projectTaskEdit.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-task/projectTaskRemove.js') }}"></script>

    <!-- CONTROLLERS PROJECT MEMBER -->
    <script src="{{ asset('build/js/controllers/project-member/projectMemberList.js') }}"></script>
    <script src="{{ asset('build/js/controllers/project-member/projectMemberRemove.js') }}"></script>

    <!-- DIRECTIVES -->
    <script src="{{ asset('build/js/directives/projectFileDownload.js') }}"></script>
    <script src="{{ asset('build/js/directives/loginForm.js') }}"></script>

    <!-- FILTERS -->
    <script src="{{ asset('build/js/filters/dateBr.js') }}"></script>

    <!-- SERVICES -->
    <script src="{{ asset('build/js/services/url.js') }}"></script>
    <script src="{{ asset('build/js/services/oauthFixInterceptor.js') }}"></script>
    <script src="{{ asset('build/js/services/client.js') }}"></script>
    <script src="{{ asset('build/js/services/projectFiles.js') }}"></script>
    <script src="{{ asset('build/js/services/projectNotes.js') }}"></script>
    <script src="{{ asset('build/js/services/projectTask.js') }}"></script>
    <script src="{{ asset('build/js/services/projectMember.js') }}"></script>
    <script src="{{ asset('build/js/services/user.js') }}"></script>
    <script src="{{ asset('build/js/services/project.js') }}"></script>


@else
    <script src="{{ elixir('js/all.js') }}"></script>
@endif

</body>
</html>