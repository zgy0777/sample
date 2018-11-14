<?php

//首页
Route::get('/','StaticPagesController@home')->name('home');
//帮助页
Route::get('/help','StaticPagesController@help')->name('help');
//关于页
Route::get('/about','StaticPagesController@about')->name('about');

//注册
Route::get('signup', 'UsersController@create')->name('signup');

//用户资源路由
Route::resource('users','UsersController');
//等价于
/*
 * Route::get('/users', 'UsersController@index')->name('users.index');
 * Route::get('/users/create', 'UsersController@create')->name('users.create');
 * Route::get('/users/{user}', 'UsersController@show')->name('users.show');
 * Route::post('/users', 'UsersController@store')->name('users.store');
 * Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
 * Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
 * Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
*/


//sessions相关
Route::get('/login','SessionsController@create')->name('login');
Route::post('/login','SessionsController@store')->name('login');
Route::delete('/logout','SessionsController@destroy')->name('logout');

//邮件发送调试
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');
//视图部分
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//修改密码部分
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');