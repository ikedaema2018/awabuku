<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Category_genre;
use App\Category_list;
use App\Owner;
use App\Book;
use App\Rental;
use App\User;
use App\Comment;
use Validator;
use App\Thread;
use App\Thread_comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Scopes\LivingBookScope;
use App\Key;
use App\Tag;


class SearchController extends Controller
{
  //search TOP画面
    public function getIndex(Request $request){

    #キーワード受け取り
  $keyword = $request->input('keyword');
 
  #クエリ生成
  $query = Book::query();
 
  #もしキーワードがあったら
  if(!empty($keyword))
  {
    $query->where('BookTitle','like','%'.$keyword.'%')->orWhere('BookAuthor','like','%'.$keyword.'%');
    
  }
    $book_lists= Book::all();
 
  #ページネーション
  $data = $query->orderBy('created_at','desc')->paginate(10);
  logger("------keyward-----------");
  logger($data);
  return view('search')->with('data',$data)
  ->with('keyword',$keyword)
  ->with('message','ユーザーリスト');
     
  }
  
    
    //タグの検索
    public function getIndex_tag(Request $request){

    #キーワード受け取り
  $keyword = $request->input('keyword');
 
  #クエリ生成
  $query = Tag::query();
 
  #もしキーワードがあったら
  if(!empty($keyword))
  {
    $query->where('tags','like','%'.$keyword.'%');
    
  }
   
 
  #ページネーション
  $data = $query->orderBy('created_at','desc')->paginate(10);
  
  logger("------keyward_tag-----------");
  logger($data);
  
  return view('search_tag')->with('data',$data)
  ->with('keyword',$keyword)
  ->with('message','ユーザーリスト');
     
  }
  

//



}


?>

