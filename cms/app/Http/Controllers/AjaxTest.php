<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Comment;

class AjaxTest extends Controller
{
    //
    public function ajax($category_ids) {
        $tags = Tag::whereIn('category_id', explode(',', $category_ids))->get();
        return response()->json($tags);
    }
    
    public function ajax_comment($book_id, $user_id)
        {
        $comments = Comment::where('user_id', $user_id)
                            ->where('book_id',$book_id)
                            ->get();
        return response()->json($comments);
    }
    
    
    
    
}
