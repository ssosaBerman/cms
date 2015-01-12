<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', function(){

	return View::make('hello');
});

Route::any('cms', function(){
	
	$response = Response::make('Hello World');

	$cmsView = View::make('cms');
	$cmsView->nest('install', 'cms.install');
	$cmsView->nest('register', 'cms.register');

	return $cmsView;
});

Route::any('form', function(){
	echo '
	<form action="form" method="post">
		<input type="text" name="test">
	</form>';
	
	$name = Input::get('test');
	
	print_r($name);
});

// Route::any('form', function(){
// 	echo '
// 	<form action="form" method="post">
// 		<input type="text" name="test">
// 	</form>';
	
// 	$name = Input::get('test');
	
// 	print_r($name);
// });