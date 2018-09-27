<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Category;
use App\Category_genre;
use App\Category_list;

use App\Owner;
use App\Book;
use App\Book_tag;
use App\Rental;
use App\User;
use App\Comment;
use Validator;
use App\Thread;
use App\Thread_comment;
use App\Key;
use App\Tag;
use App\Scopes\LivingBookScope;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GsController extends Controller
{
 //index TOP画面
    public function gsbooks(){  
        
    $owners = Owner::where('user_id',"2")->get();  
   
   
//   $genres = Category_genre::all(); 
//   $genreBooks = [];
   
    
//     // ジャンルごとに処理.    
//     foreach($genres as $genre) {
//         $books = [];
//     // ジャンルに紐づくカテゴリー一覧を取得.
      
//         $categories = $genre->categories()->get();
//         foreach($categories as $category){
//             // カテゴリーごとに処理.

//             // カテゴリーから本を取得する.
//             $tmpBooks = $category->books()->get()->toArray();
            
//             if ($tmpBooks && count($tmpBooks) > 0) {
//                 // 取得できたら、$booksに追加.
//                 $books = array_merge($books, $tmpBooks);
//             }
//         }
//         // とあるカテゴリージャンルに紐づく、本の一覧.
//         $genreBooks[$genre->category_genrename] = $books;
       
       
        
       return view('gsbooks', [
            'owners'=>$owners,
            
        ]); 
      
     }
   
     public function gsbook_view(Owner $owner){  
         
        $book= Book::where('id',$owner->book_id)->first();
        $comments=Comment::where('owner_id',$owner->id)->get();

        $categories=Category_list::where('book_id',$book->id)->get();
        $category_names=[];
        
         //カテゴリーリストのカテゴリ名を取得.
        foreach($categories as $category){
         $tmpCategory=$category->category_Name()->get()->toArray();
         if ($tmpCategory && count($tmpCategory) > 0) {
            $category_names = array_merge($category_names, $tmpCategory);  
         }
        }    

     
   
        return view('gsbook_view', [
            'book'=> $book,
           
            'owner'=>$owner,
            'comments'=>$comments,
            'category_names'=>$category_names,
            'categories' => $categories,
       ]
        );  

     }
     
     
     
      public function gsbook_comment_insert(Request $request) {
        // //バリデーション
        
        //Authで通らなければ、登録ができないように追加する
        if(Auth::check()){
                 
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
        
        //バリデーション:エラー 
        // if ($validator->fails()) {
        //         return redirect('/aaa')
        //             ->withInput()
        //             ->withErrors($validator);
        // }
        
        //isbnをstr型に変更
   
        
       
        
        $comments = new Comment;
        $comments->book_id= $request->book_id;
        $comments->owner_id =$request->owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evolution= $request->evolution;
        $comments->person= $request->person;
        $comments->comment_text= $request->comment_text;
        $comments->save();  
    
    
    
        }else{
 
        $owners->save();  
    
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evolution= $request->evolution;
        $comments->person= $request->person;
        $comments->comment_text= $request->comment_text;
        $comments->save();   
        
       }

        return redirect('/gsbook/'.$request->owner_id);

        // }else{
        //   return redirect('/book');
        //     }
       
        }
      
      
     
     
     
     
     
    //
}
