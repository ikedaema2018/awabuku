<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Category;
use App\Category_genru;
use App\Category_list;

use App\Owner;
use App\Book;
use App\Rental;
use App\User;
use App\Comment;
use Validator;
use App\Thread;
use App\Thread_comment;
use App\Scopes\LivingBookScope;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GsController extends Controller
{
 //index TOP画面
    public function gsbooks(){  
        
    $owners = Owner::where('owner',"ジーズ")->get();  
   
   
//   $genrus = Category_genru::all(); 
//   $genreBooks = [];
   
    
//     // ジャンルごとに処理.    
//     foreach($genrus as $genru) {
//         $books = [];
//     // ジャンルに紐づくカテゴリー一覧を取得.
      
//         $categories = $genru->categories()->get();
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
//         $genreBooks[$genru->category_genruname] = $books;
       
       
        
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
                    // 取得できたら、$booksに追加.
            $category_names = array_merge($category_names, $tmpCategory);  
         }
        }    
    
        return view('gsbook_view', [
            'book'=> $book,
            'owner'=>$owner,
            'comments'=>$comments,
            'category_names'=>$category_names,
            
        ]
        );  

     }
     
     
    //
}
