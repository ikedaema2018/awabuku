<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Owner;
use App\Book;
use Validator;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    //index TOP画面
    public function index(){
     
        $categories = Category::orderBy('id', 'asc')
        ->get();
        
        $b = Book::all();
        $books = $b->groupBy('category_id');
        
        return view('index', [
            'categories' => $categories,
            'books' => $books
        ]);
    }    
    

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
            // 'id' => 'required',
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
        
        if(Auth::check()){
            $categories = Category::orderBy('id', 'asc')->get();
            return view('book_register', [
            'categories' => $categories
         ]);
            return view('/book_register');
        }else{
            return redirect('/aiu');
        }
    }
    
    //登録
    public function book_insert(Request $request) {
        // //バリデーション
        
        //Authで通らなければ、登録ができないように追加する
        // if(Auth::check()){
                 
        // $validator = Validator::make($request->all(), [
        //         'BookTitle' => 'required|min:1|max:255',
        //         'BookAuthor' => 'required|min:1|max:50',
        //         'isbn10' => 'required|max:10',
        //         'isbn13' => 'required|max:13',
        //         'PublishedDate' => 'required',
        //         'BookImage' => 'required',
        //         'BookDiscription' => 'required|min:1|max:1000',
        //         'owner' =>'required|min:1|max:50',
        //         'category_id'=>'required',
        //         'rental_flag'=>'required',
        //         'user_id'=>'required',
        //         'life_flag'=>'required',
            
        // ]);
        // //バリデーション:エラー 
        // if ($validator->fails()) {
        //         return redirect('/aaa')
        //             ->withInput()
        //             ->withErrors($validator);
        // }
        
        //isbnをstr型に変更
        $isbn10 = strval($request->isbn10);
        $isbn13 = strval($request->isbn13);
        
        
        if(count(Book::where('isbn13', $isbn13)->get()) == 0) {
        
        // 本作成処理...
        $books = new Book;
        $books->BookTitle = $request->BookTitle;
        $books->BookAuthor = $request->BookAuthor;
        $books->isbn10 = $isbn10;
        $books->isbn13 = $isbn13;
        $books->PublishedDate = $request->PublishedDate;
        $books->BookImage = $request->BookImage;
        $books->BookDiscription= $request->BookDiscription;
        $books->category_id= $request->category_id;
        $books->save();
        
        $aiu = Book::orderBy('id', 'desc')->first();
        $book_id = $aiu->id;
        
        
        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        $owners->owner= $request->owner;
        $owners->rental_flag= $request->rental_flag;
        $owners->save();  
        
        }else{
        $aiu = Book::where('isbn13', $isbn13)->first();
        $book_id = $aiu->id;
        
        
        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        $owners->owner= $request->owner;
        $owners->rental_flag= $request->rental_flag;
        $owners->save();  
        }
       
        return redirect('/book_owner/'.$owners->id);

        // }else{
        //   return redirect('/book');
        //     }
            
            
    }


        public function category_list() {
         $categories = Category::orderBy('id', 'asc')->get();
         return view('book_register', [
         'categories' => $categories
         ]);
     }
     
     
   
       //本の登録情報をを更新viewページに飛ばす
        public function book_update_view(Owner $owner) {
        $categories = Category::orderBy('id', 'asc')->get();  
        return view('book_update_view',['owner'=>$owner])->with(["categories"=>$categories]);
    }
    
    
    
    
     // 登録した本のオーナー、貸し借りフラグ、カテゴリを更新する。
     
        public function book_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'owner' =>'required|min:1|max:50',
            'category_id'=>'required',
            'rental_flag'=>'required',
            'life_flag'=>'required',
            
        ]);
        
        // var_dump($request->all());
        // if ($validator->fails()){
        //     return redirect('/book_update_view')
        //     ->withInput(["id"=>$request->id])
        //     ->withError($validator);
        // }
        
        $owner = Owner::find($request->id);
        $owner->owner = $request->owner;
        $owner->rental_flag = $request->rental_flag;
        $owner->life_flag = $request->life_flag;
        $owner->save();

        
        $book = Book::find($request->book_id);
        $book->category_id = $request->category_id;
        $book->save();
        return redirect('/book');        
        
    }  
   
          //本の登録情報をレンタルviewページに飛ばす
        public function rental_view(Book $book) {
         $owners= Owner::where('book_id',$book->id)->get();
        return view('rental_view', ['book'=> $book,'owners'=>$owners]
        // dd($book);
        // dd($owners);
        );
    }
   
   
   
   
   
   
   
   
}



?>



