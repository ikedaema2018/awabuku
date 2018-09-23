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
use App\Key;
use Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Scopes\LivingBookScope;

class UsersearchController extends Controller
{
  
     public function user_search_page(User $user) {
        if(Auth::check()) {
            
        //     //userが持っている本
        //     $owner_books = Owner::where('user_id', $user->id)->get();
        //     $books = [];
        // // ユーザーの所有している本のデータをBookテーブルに紐づける.    
        //     foreach($owner_books as $owner_book) {
         
        //         // カテゴリーから本を取得する.
        //         $tmpOwnerBooks = $owner_book->books()->get()->toArray();
                
        //         if ($tmpOwnerBooks && count($tmpOwnerBooks) > 0) {
        //             // 取得できたら、$booksに追加.
        //             $books = array_merge($books, $tmpOwnerBooks);
        //         }
        //     }
        
        
        $user_comments=Comment::where('user_id',$user->id)->get();
        $books =[];
        
         // ユーザーがコメントしている本のデータをBookテーブルに紐づける.    
            foreach($user_comments as $user_comment) {
         
                // カテゴリーから本を取得する.
                $tmpCommentBooks = $user_comment->user_c()->get()->toArray();
                
                if ($tmpCommentBooks && count($tmpCommentBooks ) > 0) {
                    // 取得できたら、$booksに追加.
                    $books = array_merge($books, $tmpCommentBooks);
                }
            }
        

            
            $returned_rentals = Rental::where('user_id', $user->id)
            ->where('returned_day','>',1)
            ->orderBy('updated_at','desc')
            ->get();
            
            $rentaled_books=[];
            
            
            // ユーザー借りた本のデータをBookテーブルに紐づける.    
            foreach($returned_rentals as $returned_rental) {
         
                // カテゴリーから本を取得する.
                $tmpReturnedBooks = $returned_rental->rental_books->books()->get()->toArray();
                
                if ($tmpReturnedBooks && count($tmpReturnedBooks) > 0) {
                    // 取得できたら、$booksに追加.
                    $rentaled_books = array_merge($rentaled_books,$tmpReturnedBooks );
                }
            }

            return view('/user_search_page', [
                'user' =>$user,
                'books' => $books,
                'user_comments'=>$user_comments,
                'rentaled_books' => $rentaled_books,
               
                ]);
        }else{
            return redirect('/login');
        }
    }
 
    
    
}
    
    
?>
