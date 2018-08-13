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
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    //index TOP画面
    public function index(){
     
     
     
        $categories = Category::orderBy('id', 'asc')
        ->get();
        
        $b = Category_list::all();
        $category_lists = $b->groupBy('category_id');
        
        return view('index', [
            'categories' => $categories,
            'category_lists' => $category_lists,
        ]);
    }    
    

    // <======ここからカテゴリーの処理 ======>
  
      //カテゴリージャンル登録
    public function category_genru() {
        $category_genrus = Category_genru::orderBy('id', 'asc')->get();
        return view('category_genru', ['category_genrus' => $category_genrus]);
    }
    
    //ポストされてきたカテゴリージャンルをインサートする処理
    public function category_genru_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'category_genruname' => 'required | max: 30',
      ]);
      if ($validator->fails()){
          return redirect('category')
          ->withInput()
          ->withError($validator);
      }
      $category_genru = new Category_genru;
      $category_genru->category_genruname = $request->category_genruname;
      $category_genru->save();
      return redirect('category_genru');
    }
    
    //カテゴリージャンルを消す
    public function category_genru_delete(Category_genru $category_genru) {
        $category->delete();
        return redirect('category_genru');
    }
    
    //カテゴリージャンルを更新viewページに飛ばす
    public function category_genru_update_view(Category_genru $category_genru) {
        return view('category_genru_update_view', ['category_genru'=> $category_genru]);
    }
    // カテゴリージャンルを更新する
    public function category_genru_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_genruname' => 'required | max:30',
        ]);
        if ($validator->fails()){
            return redirect('/category_genru')
            ->withInput()
            ->withError($validator);
        }
        
        $category_genru = Category_genru::find($request->id);
        $category_genru->category_genruname = $request->category_genruname;
        $category_genru->save();
        return redirect('/category_genru'); 
        
    }
    
    //カテゴリー表示＆新規カテゴリー送信
    public function category() {
        $categories = Category::orderBy('id', 'asc')->get();
        $category_genrus=Category_genru::orderBy('id', 'asc')->get();
        return view('category',
        ['categories' => $categories],
        ['category_genrus' => $category_genrus]
        );
    }
    
    //ポストされてきたカテゴリーをインサートする処理
    public function category_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'category_name' => 'required | max: 20',
          'category_genru' =>'required',
      ]);
      if ($validator->fails()){
          return redirect('category')
          ->withInput()
          ->withError($validator);
      }
      $categories = new Category;
      $categories->category_name = $request->category_name;
      $categories->category_genru = $request->category_genru;
      
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
            'category_name' => 'required | max:20',
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
        $books->save();
        
        $aiu = Book::orderBy('id', 'desc')->first();
        $book_id = $aiu->id;
        
        
        $category = $request->category_id;
        for ($i = 0; $i < count($request->category_id); $i++){
            if(count(Category_list::where('book_id', $book_id)
            ->where('category_id', $request->category_id[$i])->get()) == 0){
        $category_lists =new Category_list;
        $category_lists->book_id=$book_id;
        $category_lists->category_id= $request->category_id[$i];
        $category_lists->save();
        }
        }

        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        $owners->owner= $request->owner;
        $owners->rental_flag= $request->rental_flag;
        $owners->save();  
        
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evolution= $request->evolution;
        $comments->person= $request->person;
        $comments->comment_text= $request->comment_text;
        $comments->save();  
    
        }else{
        $aiu = Book::where('isbn13', $isbn13)->first();
        $book_id = $aiu->id;
       
        
        
        for ($i = 0; $i < count($request->category_id); $i++){
            if(count(Category_list::where('book_id', $book_id)
            ->where('category_id', $request->category_id[$i])->get()) == 0){
        $category_lists =new Category_list;
        $category_lists->book_id=$book_id;
        $category_lists->category_id= $request->category_id[$i];
        $category_lists->save();
        }
        }
        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        $owners->owner= $request->owner;
        $owners->rental_flag= $request->rental_flag;
        $owners->save();  
        
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evolution= $request->evolution;
        $comments->person= $request->person;
        $comments->comment_text= $request->comment_text;
        $comments->save();   
        

    //   dd(count(Category_list::where('book_id', 1)
    //         ->where('category_id', 1)->get()));
        return redirect('/book_owner/'.$owners->id);

        // }else{
        //   return redirect('/book');
        //     }
            
            
    }}}


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
        $comments=Comment::where('book_id',$book->id)->get();
        $category_lists=Category_list::where('book_id',$book->id)->get();        

        return view('rental_view', ['book'=> $book,'owners'=>$owners,
        'comments'=>$comments,'category_lists'=>$category_lists,
        ]
        );
    }
   
   //本を借りる
        public function book_rental(Request $request) {
            
        $expired = Rental::where('user_id', $request->user_id)
        ->where('return_flag',1)
        //返却期限を過ぎているものをselect
        ->where('return_day', '<', date("Y-m-d 00:00:00"))
        ->count();
        
        
        if ($expired >0) {
            $categories = Category::orderBy('id', 'asc')
        ->get();
        
        $b = Book::all();
        $books = $b->groupBy('category_id');
        
        return view('index', [
            'categories' => $categories,
            'books' => $books,
            'alert' => "alert"
        ]);
        }
            
            
        $validator = Validator::make($request->all(), [
          'user_id' => 'required',
          'owner_id' => 'required',
        ]);
        if ($validator->fails()){
            return redirect('/')
            ->withInput()
            ->withError($validator);
        }
        $rentals = new Rental;
        $rentals->user_id = $request->user_id;
        $rentals->owner_id = $request->owner_id;
        $rentals->return_day =date("Y-m-d",strtotime("+7 day"));
        $rentals->return_flag = 1;
        $rentals->save();
        
        $owner = [
        'return_flag' => 1
        ];
        Owner::where('id', $request->owner_id)
        ->update($owner);
        
        
         return redirect('/book_rentaled_view/'.$rentals->id);    
        }
     //本の登録情報をレンタルviewページに飛ばす
        public function book_rentaled_view(Rental $rental) {
         $owner= Owner::where('id',$rental->owner_id)->first();
        return view('book_rentaled_view', ['owner'=>$owner,'rental'=> $rental]
        );
    }
   
   //マイページ
       public function mypage() {
        if(Auth::check()) {
            $owners = Owner::where('user_id', Auth::user()->id)->get();
            $rentals = Rental::where('user_id', Auth::user()->id)
            ->where('return_flag',1)
            //返却期限を過ぎていないものをselect
            ->where('return_day', '>=', date("Y-m-d 00:00:00"))
            ->orderBy('updated_at', 'desc')
            ->get();
            
            $expired_rentals = Rental::where('user_id', Auth::user()->id)
            ->where('return_flag',1)
            //返却期限を過ぎているものをselect
            ->where('return_day', '<', date("Y-m-d 00:00:00"))
            ->orderBy('updated_at', 'desc')
            ->get();
            
            $returned_rentals = Rental::where('user_id', Auth::user()->id)
            ->where('returned_day','>',1)
            ->orderBy('updated_at','desc')
            ->get();
            
            return view('/mypage', [
            
                'owners' => $owners,
                'rentals' => $rentals,
                'expired_rentals' => $expired_rentals,
                'returned_rentals' => $returned_rentals
                 
                ]);
        }else{
            return redirect('/login');
        }
    }
        //本の登録情報をレンタルviewページに飛ばす
        public function return_view(Rental $rental) {
         $owner= Owner::where('id',$rental->owner_id)->first();
        return view('return_view', ['owner'=>$owner,'rental'=> $rental]
        );
    }
   //本を返却する。
        public function return_comment(Request $request) {
        //バリデーターがおかしい・・
        //     $validator = Validator::make($request->all(), [
        //       'rental_id' => 'required',
        //       'user_id' => 'required',
        //       'owner_id' => 'required',
        //       'comment_text' => 'required',
        //       'evolution' => 'required',
        //       'person' =>'required',

        // ]);
        // if ($validator->fails()){
        //     return redirect('/aaa')
        //     ->withInput()
        //     ->withError($validator);
        // }
        $comments = new Comment;
        $comments->rental_id = $request->rental_id;
        $comments->user_id = Auth::user()->id;
        $comments->owner_id = $request->owner_id;
        $comments->book_id = $request->book_id;
        $comments->comment_text= $request->comment_text;
        $comments->evolution= $request->evolution;
        $comments->person= $request->person;
        $comments->save();
        
        $rental = [
            'return_flag' => 0,
            'returned_day'=> date("Y-m-d")
                ];
                
        Rental::where('id', $request->rental_id)
        ->update($rental);
        
        
        $owner = [
            'return_flag' => 0
                ];
                
        Owner::where('id', $request->owner_id)
        ->update($owner);
       
        return redirect('/mypage');    
        }

        //本の登録情報をレンタルviewページに飛ばす
        public function mypage_detail(Owner $owner) {
         $book= Book::where('id',$owner->book_id)->first();
         $comments=Comment::where('book_id',$owner->book_id)->get();
        
        return view('mypage_detail', ['owner'=>$owner,'book'=> $book,'comments'=>$comments]
        );

        }

    //スレッド新規登録
    public function thread() {
        $threads = Thread::orderBy('id', 'asc')->get();
        $categories=Category::orderBy('id', 'asc')->get();
        return view('thread',
        ['threads' => $threads],
        ['categories' => $categories]
        );
    }

    //ポストされてきたカテゴリーをインサートする処理
    public function thread_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'thread_sub'  =>'required | max: 50',
          'thread_body' =>'required | max: 200',
          'category'    =>'required'
      ]);
      if ($validator->fails()){
          return redirect('thread')
          ->withInput()
          ->withError($validator);
      }
      $threads = new Thread;
      $threads->thread_sub = $request->thread_sub;
      $threads->thread_body = $request->thread_body;
      $threads->category_id=$request->category;
      $threads->user_name=Auth::user()->id;
      $threads->save();
      return redirect('/thread/'.$threads->id);
    }
 
    //ポストされてきたスレッドをスレッドページへ
       public function thread_page(Thread $thread) {

      $thread_user_name =User::where('id',$thread->user_name)->first();
      $user_comments =Comment::where('user_id',Auth::user()->id)->get();
      
      
        return view('thread_page',
        ['thread' => $thread,
        'thread_user_name' => $thread_user_name,
        'user_comments' => $user_comments ]
        
        );
    } 











}



?>



