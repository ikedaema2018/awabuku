<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Book;
use Validator;

class BooksController extends Controller
{
    
    // <======ここからカテゴリーの処理 ======>
    
    //カテゴリー表示＆新規カテゴリー送信
    public function category() {
        $categories = Category::orderBy('id', 'asc')->get();
        return view('category', ['categories' => $categories]);
    }
    
    //ポストされてきたカテゴリーをインサートする処理
    public function category_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'category_name' => 'required | max: 20',
      ]);
      if ($validator->fails()){
          return redirect('category')
          ->withInput()
          ->withError($validator);
      }
      $categories = new Category;
      $categories->category_name = $request->category_name;
      $categories->save();
      return redirect('category');
    }
    
    //カテゴリーを消す
    public function category_delete(Category $category) {
        $category->delete();
        return redirect('category');
    }
    
    //カテゴリーを更新viewページに飛ばす
    public function category_update_view(Category $category) {
        return view('category_update_view', ['category'=> $category]);
    }
    // カテゴリーを更新する
    public function category_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_name' => 'required | max:10',
        ]);
        if ($validator->fails()){
            return redirect('/category')
            ->withInput()
            ->withError($validator);
        }
        
        $category = Category::find($request->id);
        $category->category_name = $request->category_name;
        $category->save();
        return redirect('/category'); 
        
    }
    
    
    //ブックス画面の表示
    public function book_register() {
        return view('/book_register');
    }
    
    //登録
    public function book_insert(Request $request) {
        // //バリデーション
        $validator = Validator::make($request->all(), [
                'BookTitle' => 'required|min:1|max:255',
                'BookAuthor' => 'required|min:1|max:50',
                'isbn10' => 'required|max:10',
                'isbn13' => 'required|max:13',
                'PublishedDate' => 'required',
                'BookImage' => 'required',
                'BookDiscription' => 'required|min:1|max:255',
        ]);
        //バリデーション:エラー 
        if ($validator->fails()) {
                return redirect('/aaa')
                    ->withInput()
                    ->withErrors($validator);
        }
        
        //isbnをstr型に変更
        $isbn10 = strval($request->isbn10);
        $isbn13 = strval($request->isbn13);
        
        // 本作成処理...
        $books = new Book;
        $books->BookTitle = $request->BookTitle;
        $books->BookAuthor = $request->BookAuthor;
        $books->isbn10 = $isbn10;
        $books->isbn13 = $isbn13;
        $books->PublishedDate = $request->PublishedDate;
        $books->BookImage = $request->BookImage;
        $books->BookDiscription= $request->BookDiscription;
        $books->save();
        return redirect('/book');
    }

   
   
   
   
   
   
   
   



    
}



?>