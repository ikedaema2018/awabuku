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


use App\Http\Middleware\LoginMiddleware;


Route::get('/login', 'UserController@login_view');
Route::get('logout', 'UserController@logout');
Route::get('facebook', 'UserController@loginFacebook');
Route::get('facebook/callback', 'UserController@facebookCallback');
Route::get('/loginCheck', 'UserController@loginCheck');

///////カテゴリ登録
//カテゴリジャンル
Route::get('/category_genre','BooksController@category_genre')
->middleware('login');
Route::post('/category_genre','BooksController@category_genre_insert')
->middleware('login');
Route::delete('/category_genre/{category_genre}', 'BooksController@category_genre_delete')
->middleware('login');
Route::post('/category_genre/{category_genre}', 'BooksController@category_genre_update_view')
->middleware('login');
Route::post('/category_genre_update', 'BooksController@category_genre_update')
->middleware('login');

//カテゴリ
Route::get('/category','BooksController@category')
->middleware('login');
Route::post('/category', 'BooksController@category_insert')
->middleware('login');
Route::delete('/category/{category}', 'BooksController@category_delete')
->middleware('login');
Route::post('/category/{category}', 'BooksController@category_update_view')
->middleware('login');
Route::post('/category_update', 'BooksController@category_update')
->middleware('login');


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
Route::get('/', 'BooksController@index')
->middleware('login');


//貸出機能画面
Route::get('/rental/{book}','BooksController@rental_view')
->middleware('login');
Route::post('/book_rental','BooksController@book_rental')
->middleware('login');
Route::get('/book_rentaled_view/{rental}','BooksController@book_rentaled_view')
->middleware('login');

//マイページ
Route::get('/mypage','BooksController@mypage')
->middleware('login');
Route::get('/mypage/{owner}','BooksController@mypage_detail')
->middleware('login');
Route::post('/delete_ownbook','BooksController@delete_ownbook')
->middleware('login');

//返却画面
Route::post('/return/{rental}','BooksController@return_view')
->middleware('login');
Route::post('/return_comment/','BooksController@return_comment')
->middleware('login');

//テスト
// Route::get('/mypagetest', 'TestController@mypagetest');
// Route::get('/datetest', 'TestController@datetest');


//掲示板
Route::get('/threads','BooksController@thread')
->middleware('login');
Route::post('/threads','BooksController@thread_insert')
->middleware('login');


Route::post('/thread', 'BooksController@book_insert_thread')
->middleware('login');
Route::post('/thread_2', 'BooksController@thread_comment_insert')
->middleware('login');
Route::get('/thread/{thread}', 'BooksController@thread_page')
->middleware('login');

//カテゴリジャンル別ページ
Route::get('/category_genre_page/{category_genre}', 'BooksController@category_genre_page')
->middleware('login');
Route::get('/category_page/{category}', 'BooksController@category_page')
->middleware('login');

//検索結果ページ
Route::get('/search', 'SearchController@getIndex')
->middleware('login');

//外部の人が見るユーザー情報
Route::get('/user_search_page/{user}','UsersearchController@user_search_page')
->middleware('login');

//gs画面
Route::get('/gsbooks', 'GsController@gsbooks')
->middleware('login');
Route::get('/gsbook/{owner}','GsController@gsbook_view')
->middleware('login');
Route::post('/gsbook/','GsController@gsbook_comment_insert')
->middleware('login');

//person入力画面
Route::get('/key','BooksController@key')
->middleware('login');
Route::post('/key_insert', 'BooksController@key_insert')
->middleware('login');

//tag入力画面
Route::get('/tag','BooksController@tag')
->middleware('login');
Route::post('/tag_insert', 'BooksController@tag_insert')
->middleware('login');