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
use App\Key;
use App\Tag;
use App\Book_tag;
use App\Scopes\LivingBookScope;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
//index TOP画面
    public function index(){
        
        //topics
        
        $topic=Comment::where('today_book',1)->first();
        $topic_user=User::where('id',$topic->user_id)->first();
        $topic_book=Book::where('id',$topic->book_id)->first();
        $topic_categories=Category_list::where('book_id',$topic_book->id)->get();
        $topic_category_names=[];
        
         //カテゴリーリストのカテゴリ名を取得.
        foreach($topic_categories as $topic_category){

         $tmpCategory=$topic_category->category_Name()->get()->toArray();
         if ($tmpCategory && count($tmpCategory) > 0) {
                    // 取得できたら、$booksに追加.
            $topic_category_names = array_merge($topic_category_names, $tmpCategory);  
         }
        }    
    
        
        $genres = Category_genre::all();
        
        // カテゴリジャンルごとの本一覧.
        $genreBooks = [];
    
        // ジャンルごとに処理.    
        foreach($genres as $genre) {
            $books = [];
 // ジャンルに紐づくカテゴリー一覧を取得.
          
            $categories = $genre->categories()->get();
            foreach($categories as $category){
                // カテゴリーごとに処理.

                // カテゴリーから本を取得する.
                $tmpBooks = $category->books()->get()->toArray();
                
                if ($tmpBooks && count($tmpBooks) > 0) {
                    // 取得できたら、$booksに追加.
                    $books = array_merge($books, $tmpBooks);
                }
            }
            // とあるカテゴリージャンルに紐づく、本の一覧.
            $genreBooks[$genre->category_genrename] = $books;
        }
   
        $b = Category_list::all();
        $category_lists = $b->groupBy('category_id');
        
        
        $thread_lists = Thread::orderBy('updated_at','desc')
                        ->take(5)
                        ->get();
        
        $rentals = Rental::all();
        
    
        $tags = Tag::all();

        return view('index', [
            'topic'=>$topic,
            'topic_user'=>$topic_user,
            'topic_book'=>$topic_book,
            'topic_category'=>$topic_category,
            'topic_category_names'=>$topic_category_names,
            'categories' => $categories,
            'category_lists' => $category_lists,
            'thread_lists'  =>$thread_lists,
            'rentals'=>$rentals,
            'genreBooks'=>$genreBooks,
            'tags'=>$tags,
        
        ]);
     
        
    }
    
// <======カテゴリごとの紹介ページ ======>  
      
      
   public function category_genre_page(Category_genre $category_genre) { 
    
     $categories=Category::where('category_genre',$category_genre->id)
                        ->get();
     $book_lists= Category_list::all();
     $books = [];
     foreach($categories as $category){
                
                // カテゴリーごとに処理.

                // カテゴリーから本を取得する.
                $tmpBooks = $category->books()->get()->toArray();
                
                if ($tmpBooks && count($tmpBooks) > 0) {
                    // 取得できたら、$booksに追加.
                    $books = array_merge($books, $tmpBooks);
                }
            }
            
            // とあるカテゴリージャンルに紐づく、本の一覧.
           
            $genreBooks[$category_genre->category_genrename] = $books;
           
            
       
            
        return view('category_genre_page', [
            
             'category_genre' => $category_genre,
             'categories' =>$categories,
             'genreBooks'=>$genreBooks,
             'book_lists' =>$book_lists
      
        ]);
   } 
  
   public function category_page(Category $category) { 
    
   
     $book_lists= Category_list::where('category_id',$category->id)
                ->get();
      
        return view('category_page', [
            
            
             'category' =>$category,
             'book_lists' =>$book_lists
      
        ]);
  
  
   } 
    // <======ここからカテゴリーの処理 ======>
  
      //カテゴリージャンル登録
    public function category_genre() {
        $category_genres = Category_genre::orderBy('id', 'asc')->get();
        return view('category_genre', ['category_genres' => $category_genres]);
    }
    
    //ポストされてきたカテゴリージャンルをインサートする処理
    public function category_genre_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'category_genrename' => 'required | max: 30',
      ]);
      if ($validator->fails()){
          return redirect('category')
          ->withInput()
          ->withError($validator);
      }
      $category_genre = new Category_genre;
      $category_genre->category_genrename = $request->category_genrename;
      $category_genre->save();
      return redirect('category_genre');
    }
    
    //カテゴリージャンルを消す
    public function category_genre_delete(Category_genre $category_genre) {
        $category->delete();
        return redirect('category_genre');
    }
    
    //カテゴリージャンルを更新viewページに飛ばす
    public function category_genre_update_view(Category_genre $category_genre) {
        return view('category_genre_update_view', ['category_genre'=> $category_genre]);
    }
    // カテゴリージャンルを更新する
    public function category_genre_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
  
  
            'category_genrename' => 'required | max:30',
        ]);
        if ($validator->fails()){
            return redirect('/category_genre')
            ->withInput()
            ->withError($validator);
        }
        
        $category_genre = Category_genre::find($request->id);
        $category_genre->category_genrename = $request->category_genrename;
        $category_genre->save();
        return redirect('/category_genre'); 
        
    }
    
    //カテゴリー表示＆新規カテゴリー送信
    public function category() {
        $categories = Category::orderBy('id', 'asc')->get();
        $category_genres=Category_genre::orderBy('id', 'asc')->get();
       
        
        
        
        return view('category',
        ['categories' => $categories],
        ['category_genres' => $category_genres]
        );
    }
    
    //ポストされてきたカテゴリーをインサートする処理
    public function category_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'category_name' => 'required | max: 20',
          'category_genre' =>'required',
      ]);
      if ($validator->fails()){
          return redirect('category')
          ->withInput()
          ->withError($validator);
      }
      $categories = new Category;
      $categories->category_name = $request->category_name;
      $categories->category_genre = $request->category_genre;
      
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
            // $categories = Category::orderBy('id', 'asc')->get(); 

            $genres = Category_genre::all();
           
           
            $keys= Key::orderBy('id','asc') ->get(); 
           

            return view('book_register',
            [
            'genres'=>$genres,  
            'keys'=>$keys,
         
            ]
          
            );
        
            
        }else{
            return redirect('/aiu');
        }
    }
    
    //登録
    public function book_insert(Request $request) {
        // //バリデーション
        
        //Authで通らなければ、登録ができないように追加する
        if(Auth::check()){
        
        
        $validate_rule = [         
                'BookTitle'       => 'required',
                'BookAuthor'      => 'required',
                'isbn10'          => 'required',
                'isbn13'          => 'required',
                'PublishedDate'   => 'required',
                'Publisher'       => 'required',
                'BookImage'       => 'required',
                'BookDiscription' => 'required',
                
                
                
                'category_id'     =>'required',
                'rental_flag'     =>'required',
              
                'comment_text'    =>'required',
                'evaluation'      =>'required'
        ];
        
       $error_msg=[
                'BookTitle'       => '本の情報が入力されていません',
                'BookAuthor'      => '本の情報が入力されていません',
                'isbn10'          => '本の情報が入力されていません',
                'isbn13'          => '本の情報が入力されていません',
                'PublishedDate'   => '本の情報が入力されていません',
                'BookImage'       => '本の情報が入力されていません',
                'BookDiscription' => '本の情報が入力されていません',
                'Publisher'       => '本の情報が入力されていません',

                'category_id'     =>'カテゴリが入力されていません',
                'rental_flag'     =>'本が貸し出しの可否を選択してください',
               
                'comment_text'    =>'コメントを入力してください',
                'evaluation'      =>'評価を入力してください'

           ];
         $validator = Validator::make($request->all(), $validate_rule, $error_msg);

        // バリデーション:エラー 
        if ($validator->fails()) {
                return redirect('/book')
                    ->withInput()
                    ->withErrors($validator);
        }
        
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
        $books->Publisher = $request->Publisher;
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
        $category_lists->category_id= $request->category_id;
        $category_lists->save();
        }
        }
        
        
    for($i = 0; $i < count($request->tag_add); $i++){
          
            if(count(Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])
            ->get())==0){
        $tag =new Tag;
        $tag->category_id = $request->tag_category_id[$i];
        $tag->tags =$request->tag_add[$i];
        $tag ->save();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        
        }else{
        $tag = Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])->first();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        }
        }
        
    
        for ($i = 0; $i < count($request->tag_id); $i++){
            if(count(Book_tag::where('book_id', $book_id)
            ->where('tag_id', $request->tag_id[$i])->get()) == 0){
        $book_tag =new Book_tag;
        $book_tag ->book_id=$book_id;
        $book_tag ->tag_id= $request->tag_id[$i];
    
        
        $book_tag ->save();
        }
        }

        
        

        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        
        $owners->rental_flag= $request->rental_flag;
        
        
        if($request->gs == true  || Auth::user()->kanri_flag == 1) {
            //ここで$request->gsをもとにユーザー情報を検策
            $id= User::find($request->gs);
            $owners->user_id = $id->id;
        
            echo '<pre>' . var_export($id, true) . '</pre>';
            echo '<pre>' . var_export($owners, true) . '</pre>';
            
            
        }else{
            $owners->user_id =$request->user_id;
        }
        
        $owners->save();  
        
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evaluation= $request->evaluation;
        $comments->key= $request->key;
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

       for($i = 0; $i < count($request->tag_add); $i++){
          
            if(count(Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])
            ->get())==0){
        $tag =new Tag;
        $tag->category_id = $request->tag_category_id[$i];
        $tag->tags =$request->tag_add[$i];
        $tag ->save();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        
        }else{
        $tag = Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])->first();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        }
        }
        
        
        for ($i = 0; $i < count($request->tag_id); $i++){
            if(count(Book_tag::where('book_id', $book_id)
            ->where('tag_id', $request->tag_id[$i])->get()) == 0){
        $book_tag =new Book_tag;
        $book_tag ->book_id=$book_id;
        $book_tag ->tag_id= $request->tag_id[$i];
       
        $book_tag ->save();
        }
        }

        
        
        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
      
        $owners->rental_flag= $request->rental_flag;
        
        
      if($request->gs == 2  && Auth::user()->kanri_flag == 1) {
            //ここで$request->gsをもとにユーザー情報を検策
         
            $id= User::find($request->gs);
            logger('------------abc------------------');
            logger($id); 
            logger($request->gs);
            logger($owners); 
            $owners->user_id = $id->id;
  
        }
       
        
        $owners->save();  
       
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evaluation= $request->evaluation;
        $comments->key= $request->key;
   
        $comments->save();   
        
}

        return redirect('/book_owner/'.$owners->id);

        }else{
          return redirect('/book');
            }
       
    
 }



        public function category_list() {
         $categories = Category::orderBy('id', 'asc')->get();
         return view('book_register', [
         'categories' => $categories
         ]);
     }
   
     
   
       //本の登録情報をを更新viewページに飛ばす
        public function book_update_view(Owner $owner) {
        // $categories = Category::orderBy('id', 'asc')->get(); 
        $genres = Category_genre::all();
            // ジャンルごとに処理.    
            
            foreach($genres as $genre) {
             $categories = [];
            // ジャンルに紐づくカテゴリー一覧を取得.
           
            $tmp_categories = $genre->categories()->get()->toArray();
            
            if ($tmp_categories && count($tmp_categories) > 0) {
                    // 取得できたら、$categoriesに追加.
                    $categories = array_merge($categories, $tmp_categories);
                }
                 $genreCategories[$genre->category_genrename] = $categories;
            }
        return view('book_update_view',['owner'=>$owner])
        ->with([
            'categories' => $categories,
            'genreCategories' =>$genreCategories,
            ]);
    }
    
    
    
    
     // 登録した本のオーナー、貸し借りフラグ、カテゴリを更新する。
     
        public function book_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
           
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
        
        $owner->rental_flag = $request->rental_flag;
        $owner->life_flag = $request->life_flag;
        $owner->save();

        
        $book = Book::find($request->book_id);
        $book->save();
        
        
        $category = $request->category_id;
        
        for ($i = 0; $i < count($request->category_id); $i++){
        if(count(Category_list::where('book_id', $request->book_id)
            ->where('category_id', $request->category_id[$i])->get()) == 0){
        $category_lists =new Category_list;
        $category_lists->book_id=$request->book_id;
        $category_lists->category_id= $request->category_id;
        $category_lists->save();
            } 
            }
        
        return redirect('/book');        
        
    }  
   
    //本の登録情報をレンタルviewページに飛ばす
        public function rental_view(Book $book) {
        $owners= Owner::where('book_id',$book->id)->get();
        $comments=Comment::where('book_id',$book->id)->get();

       $rentals=Rental::where('return_flag',1)->get();

      
        $category_lists=Category_list::where('book_id',$book->id)->get();        

        return view('rental_view', [
            'book'=> $book,
            'owners'=>$owners,
            'comments'=>$comments,
            'category_lists'=>$category_lists,
            'rentals'=>$rentals,
            
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
     
        return redirect('/mypage')->with('alert', '返却期限を過ぎている書籍があります。大至急ご返却ください');
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
         $keys = Key::all();
        
        return view('return_view', [
            'owner'=>$owner,
            'rental'=> $rental,
            'keys'=> $keys,
            ]
        );
    }
   //本を返却する。
        public function return_comment(Request $request) {
        //バ���データーがおかしい・���
        //     $validator = Validator::make($request->all(), [
        //       'rental_id' => 'required',
        //       'user_id' => 'required',
        //       'owner_id' => 'required',
        //       'comment_text' => 'required',
        //       'evaluation' => 'required',
        //       'key' =>'required',

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
        $comments->evaluation= $request->evaluation;
        $comments->key= $request->key;
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

        //mypageの詳細情報
        public function mypage_detail(Owner $owner) {
         $book= Book::where('id',$owner->book_id)->first();
         $comments =Comment::where('book_id',$owner->book_id)
         ->where('user_id',Auth::user()->id)
         ->get();
         
        return view('mypage_detail', [
            'comments'=>$comments,
            'book'=> $book,
            'owner'=>$owner]
        );
        

        }

    //ownerの本の情報を削除する（life_flag->1に変更）
    
        public function delete_ownbook(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'life_flag'=>'required',
        ]);
        
        // var_dump($request->all());
        // if ($validator->fails()){
        //     return redirect('/book_update_view')
        //     ->withInput(["id"=>$request->id])
        //     ->withError($validator);
        // }
        
        $owner = Owner::find($request->id);
        $owner->life_flag = $request->life_flag;
        $owner->save();

   
        return redirect('/mypage');        
        
    } 




    //スレッド新規登録
    public function thread() {
        $threads = Thread::orderBy('id', 'asc')->get();
        // $categories=Category::orderBy('id', 'asc')->get();
      
         if(Auth::check()){
             
           
            $genres = Category_genre::all();
            
            $genreCategories = [];
            // ジャンルごとに処理.    
            
            foreach($genres as $genre) {
             $categories = [];
            // ジャンルに紐づくカテゴリー一覧を取得.
           
            $tmp_categories = $genre->categories()->get()->toArray();
            
            if ($tmp_categories && count($tmp_categories) > 0) {
                    // 取得できたら、$categoriesに追加.
                    $categories = array_merge($categories, $tmp_categories);
                }
                
                 $genreCategories[$genre->category_genrename] = $categories;
              
           
            }
             
             return view('threads',
                ['threads' => $threads,
                // 'categories' => $categories,
                'genreCategories' =>$genreCategories]
             );
         }
         }

  
  
    //ポストされてきたカテゴリーをインサートする処理
    public function thread_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'thread_sub'  =>'required | max: 50',
          'thread_body' =>'required | max: 200',
          'category'    =>'required'
      ]);
      if ($validator->fails()){
          return redirect('threads')
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
      $categories = Category::orderBy('id', 'asc')->get();
      $thread_comment_lists =Thread_comment::where('thread_id',$thread->id)->get();
      $genres = Category_genre::all();
      $keys= Key::orderBy('id','asc') ->get(); 
           

     
        return view('thread_page',
        ['thread' => $thread,
        'thread_user_name' => $thread_user_name,
        'user_comments' => $user_comments,
        'categories' =>$categories,
        'thread_comment_lists'=>$thread_comment_lists,
        'genres'=>$genres,  
        'keys'=>$keys,
        ]
        
        );
    } 
    //スレッド用の書籍の新規登録
    public function thread_comment_insert(Request $request) {
 

        $thread_comments = new Thread_comment;
        $thread_comments->comment_id =$request->id;
        $thread_comments->thread_id =$request->thread_id;
        $thread_comments->thread_comment =$request->thread_comment;
        $thread_comments->save();
        
        $thread = Thread::where('id',$request->thread_id)->first();
        
         return redirect('/thread/'.$thread->id);
      
    }

   //スレッド用の書籍の新規登録
    public function book_insert_thread(Request $request) {
      // //バリデーション
        
        //Authで通らなければ、登録ができないように追加する
        if(Auth::check()){
        
        
        $validate_rule = [         
                'BookTitle'       => 'required',
                'BookAuthor'      => 'required',
                'isbn10'          => 'required',
                'isbn13'          => 'required',
                'PublishedDate'   => 'required',
                'BookImage'       => 'required',
                'BookDiscription' => 'required',
                'Publisher'        => 'required',
                
                
                'category_id'     =>'required',
                'rental_flag'     =>'required',
              
                'comment_text'    =>'required',
                'evaluation'      =>'required'
        ];
        
       $error_msg=[
                'BookTitle'       => '本の情報が入力されていません',
                'BookAuthor'      => '本の情報が入力されていません',
                'isbn10'          => '本の情報が入力されていません',
                'isbn13'          => '本の情報が入力されていません',
                'PublishedDate'   => '本の情報が入力されていません',
                'BookImage'       => '本の情報が入力されていません',
                'BookDiscription' => '本の情報が入力されていません',
                'Publisher'        => '本の情報が入力されていません',
                
                'category_id'     =>'カテゴリが入力されていません',
                'rental_flag'     =>'本が貸し出しの可否を選択してください',
               
                'comment_text'    =>'コメントを入力してください',
                'evaluation'      =>'評価を入力してください'

           ];
         $validator = Validator::make($request->all(), $validate_rule, $error_msg);

        // バリデーション:エラー 
        if ($validator->fails()) {
                return redirect('/book')
                    ->withInput()
                    ->withErrors($validator);
               
        }
        
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
        $books->Publisher =$request->Publisher;
        
        $books->save();
        
        $aiu = Book::orderBy('id', 'desc')->first();
        $book_id = $aiu->id;
        
        
        $category = $request->category_id;
        for ($i = 0; $i < count($request->category_id); $i++){
            if(count(Category_list::where('book_id', $book_id)
            ->where('category_id', $request->category_id[$i])->get()) == 0){
        $category_lists =new Category_list;
        $category_lists->book_id=$book_id;
        $category_lists->category_id= $request->category_id;
        $category_lists->save();
        }
        }
        
        
    for($i = 0; $i < count($request->tag_add); $i++){
          
            if(count(Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])
            ->get())==0){
        $tag =new Tag;
        $tag->category_id = $request->tag_category_id[$i];
        $tag->tags =$request->tag_add[$i];
        $tag ->save();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        
        }else{
        $tag = Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])->first();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        }
        }
        
    
        for ($i = 0; $i < count($request->tag_id); $i++){
            if(count(Book_tag::where('book_id', $book_id)
            ->where('tag_id', $request->tag_id[$i])->get()) == 0){
        $book_tag =new Book_tag;
        $book_tag ->book_id=$book_id;
        $book_tag ->tag_id= $request->tag_id[$i];
    
        
        $book_tag ->save();
        }
        }

        
        

        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
        
        $owners->rental_flag= $request->rental_flag;
        
        
        if($request->gs == true  || Auth::user()->kanri_flag == 1) {
            //ここで$request->gsをもとにユーザー情報を検策
            $id= User::find($request->gs);
            $owners->user_id = $id->id;
        
            echo '<pre>' . var_export($id, true) . '</pre>';
            echo '<pre>' . var_export($owners, true) . '</pre>';
            
            
        }else{
            $owners->user_id =$request->user_id;
        }
        
        $owners->save();  
        
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evaluation= $request->evaluation;
        $comments->key= $request->key;
        $comments->save();  
    
    
        $kkk = Comment::orderBy('id', 'desc')->first();
        $comment_id = $kkk->id;
    
        $thread_comments = new Thread_comment;
        $thread_comments->comment_id =$comment_id;
        $thread_comments->thread_id =$request->thread_id;
        $thread_comments->thread_comment =$request->thread_comment;
        $thread_comments->save();
        
        
        return redirect('/thread/'.$request->thread_id);

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

       for($i = 0; $i < count($request->tag_add); $i++){
          
            if(count(Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])
            ->get())==0){
        $tag =new Tag;
        $tag->category_id = $request->tag_category_id[$i];
        $tag->tags =$request->tag_add[$i];
        $tag ->save();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        
        }else{
        $tag = Tag::where('tags',$request->tag_add[$i])->where('category_id', $request->tag_category_id[$i])->first();
        $newTag = $tag->id;
        $book_tag=new Book_tag;
        $book_tag ->tag_id=$newTag;
        $book_tag ->book_id=$book_id;
        $book_tag -> save();
        }
        }
        
        
        for ($i = 0; $i < count($request->tag_id); $i++){
            if(count(Book_tag::where('book_id', $book_id)
            ->where('tag_id', $request->tag_id[$i])->get()) == 0){
        $book_tag =new Book_tag;
        $book_tag ->book_id=$book_id;
        $book_tag ->tag_id= $request->tag_id[$i];
       
        $book_tag ->save();
        }
        }

        
        
        $owners = new Owner;
        $owners->book_id= $book_id;
        $owners->user_id= $request->user_id;
      
        $owners->rental_flag= $request->rental_flag;
        
        
      if($request->gs == 2  && Auth::user()->kanri_flag == 1) {
            //ここで$request->gsをもとにユーザー情報を検策
         
            $id= User::find($request->gs);
            logger('------------abc------------------');
            logger($id); 
            logger($request->gs);
            logger($owners); 
            $owners->user_id = $id->id;
  
        }
       
        
        $owners->save();  
       
        $eok = Owner::orderBy('id', 'desc')->first();
        $owner_id = $eok->id;
        
        
        $comments = new Comment;
        $comments->book_id= $book_id;
        $comments->owner_id =$owner_id;
        $comments->user_id= $request->user_id;
        $comments->comment_text= $request->comment_text;
        $comments->evaluation= $request->evaluation;
        $comments->key= $request->key;
   
        $comments->save();   
        
        
        $kkk = Comment::orderBy('id', 'desc')->first();
        $comment_id = $kkk->id;
    
        $thread_comments = new Thread_comment;
        $thread_comments->comment_id =$comment_id;
        $thread_comments->thread_id =$request->thread_id;
        $thread_comments->thread_comment =$request->thread_comment;
        $thread_comments->save();



}

        return redirect('/thread/'.$request->thread_id);

        }else{
          return redirect('/thread/'.$request->thread_id);
            }
       
    
 }


  
      //特徴登録
    public function key() {
        $keys = key::orderBy('id', 'asc')->get();
        return view('key', ['keys' => $keys]);
    }
    
    //ポストされてきたカテゴリージャンルをインサートする処理
    public function key_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'key' => 'required | max: 30',
      ]);
      if ($validator->fails()){
          return redirect('key')
          ->withInput()
          ->withError($validator);
      }
      $key = new key;
      $key->key = $request->key;
      $key->save();
      return redirect('key');
    }








public function tag() {
        $categories = Category::orderBy('id', 'asc')->get();
        $tags=Tag::orderBy('id', 'asc')->get();
   
        
        
        
        return view('tag',
        ['categories' => $categories],
        ['tags' => $tags]
        );
    }
    
    //ポストされてきたカテゴリーをインサートする処理
    public function tag_insert(Request $request) {
      $validator = Validator::make($request->all(), [
          'tags' => 'required | max: 20',
          'category_id' =>'required',
      ]);
      if ($validator->fails()){
          return redirect('tag')
          ->withInput()
          ->withError($validator);
      }
      $tag = new Tag;
      $tag->tags = $request->tags;
      $tag->category_id = $request->category_id;
      
      $tag->save();
      return redirect('tag');
    }
    
    //タグを消す
    public function tag_delete(Tag $tag) {
        $tag->delete();
        return redirect('tag');
    }
    
    //タグを更新viewページに飛ばす
    public function tag_update_view(Tag $tag) {
        return view('tag_update_view', ['tag'=> $tag]);
    }
    // タグを更新する
    public function tag_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'tags' => 'required | max:20',
        ]);
        if ($validator->fails()){
            return redirect('/')
            ->withInput()
            ->withError($validator);
        }
        
        $tag = Tag::find($request->id);
        $tag->tags = $request->tags;
        $tag->save();
        return redirect('/tag'); 
        
    }

public function tag_page(Tag $tag) { 

    //  $tags= Category_list::where('category_id',$category->id)
    //             ->get();
  
        return view('tag_page', [
          
             'tag' =>$tag,
            //  'book_lists' =>$book_lists
      
        ]);
  
  
   } 



//ownerブックの更新
    public function modify_ownbook(Owner $owner) {
        
        if(Auth::check()){
            // $categories = Category::orderBy('id', 'asc')->get(); 
            
            $genres = Category_genre::all();
            $book= Book::where('id',$owner->book_id)->first();
            $comments =Comment::where('book_id',$owner->book_id)
             ->where('user_id',Auth::user()->id)
             ->get();
            $keys= Key::orderBy('id','asc') ->get(); 
           

            return view('modify_ownbook',
            [
            'book'=>$book,  
            'owner'=>$owner,  
            'comments'=>$comments,
            'keys' =>$keys,
            'genres'=>$genres,
            
            ]
          
            );
        
            
        }else{
            return redirect('/aiu');
        }
    }



}
?>



