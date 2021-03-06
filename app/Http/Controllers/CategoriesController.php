<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, Topic $topic, User $user, Link $link)
    {
        //读取分类ID关联的话题  并按每20条分页
        $topics = $topic->withOrder($request->order)
            ->with('user','category')
            ->where('category_id', $category->id)
            ->paginate(20);
        //传参变量 话题和分类到模板中
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
