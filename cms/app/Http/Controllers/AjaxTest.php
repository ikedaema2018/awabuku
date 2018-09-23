<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class AjaxTest extends Controller
{
    //
    public function ajax($id)
        {
        $tags = Tag::where('category_id', $id)->get();
        return response()->json($tags);
    }
}
