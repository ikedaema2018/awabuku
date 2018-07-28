<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/login', 'UserController@login_view');
Route::get('logout', 'UserController@logout');
Route::get('facebook', 'UserController@loginFacebook');
Route::get('facebook/callback', 'UserController@facebookCallback');
Route::get('/loginCheck', 'UserController@loginCheck');
//カテゴリ登録
Route::get('/category','BooksController@category');
Route::post('/category', 'BooksController@category_insert');
Route::delete('/category/{category}', 'BooksController@category_delete');
Route::post('/category/{category}', 'BooksController@category_update_view');
Route::post('/category_update', 'BooksController@category_update');
//書籍の登録
Route::get('/book','BooksController@book_register');
// Route::get('/book','BooksController@book');
Route::post('/book', 'BooksController@book_insert');
// Route::delete('/book/{book}', 'BooksController@book_delete');
Route::get('/book_owner/{owner}', 'BooksController@book_update_view');
Route::post('/book_update', 'BooksController@book_update');
/////カテゴリの表示
//変更
//Route::get('/book', 'BooksController@category_list');
//TOP画面
Route::get('/', 'BooksController@index');
//貸出機能画面
Route::get('/rental/{book}','BooksController@rental_view');
Route::post('/book_rental','BooksController@book_rental');
Route::get('/book_rentaled_view/{rental}','BooksController@book_rentaled_view');
//マイページ
Route::get('/mypage','BooksController@mypage');
//返却画面
Route::post('/return/{rental}','BooksController@return_view');
Route::post('/return_comment/','BooksController@return_comment');
