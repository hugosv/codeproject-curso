<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::post('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

/**
 * Aplique o processo de Autorização em todos os endpoints de nossa API
 */
Route::group(['middleware' => 'oauth'], function() {
    Route::resource('client', 'ClientController', ['except' => ['create', 'edit']]);
    Route::resource('project', 'ProjectController', ['except' => ['create', 'edit']]);

    Route::group(['middleware' => 'check-project-owner'], function () {
        /** SubRoutes de project */
        Route::resource('project.notes', 'ProjectNoteController', ['except' => ['create', ' edit']]);
        Route::resource('project.task', 'ProjectTaskController', ['except' => ['create', 'edit']]);
        Route::resource('project.member', 'ProjectMemberController', ['except' => ['create',  'edit', 'update']]);

        /** Routes para o project file se adequar ao do curso */
        Route::group(['prefix' => 'project'], function () {
            Route::get('{id}/file', 'ProjectFileController@index');
            Route::get('{id}/file/{fileId}', 'ProjectFileController@show');
            Route::get('{id}/file/{fileId}/download', 'ProjectFileController@showFile');
            Route::post('{id}/file', 'ProjectFileController@store');
            Route::put('{id}/file/{fileId}', 'ProjectFileController@update');
            Route::delete('{id}/file/{fileId}', 'ProjectFileController@destroy');
        });
    });

    /** Autenticação do usuário */
    Route::get('user/authenticated', 'UserController@authenticated');
    Route::resource('user', 'UserController', ['except' => ['create', 'edit']]);
});

