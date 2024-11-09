<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostEditRequest;
use App\Http\Requests\BulletinBoard\CategoryFormRequest;
use App\Http\Requests\BulletinBoard\SubCategoryRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request){
        $posts = Post::with(['user', 'subCategories'])->withCount('postComments')->get();
        // dd($posts);
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        if(!empty($request->keyword)){
            $posts = Post::with('user')->withCount('postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user')->withCount('postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user')->withCount('postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user')->withCount('postComments')
            ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        // dd($request);
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        $post->subCategories()->attach($request->post_category_id);

        return redirect()->route('post.show');
    }

    public function postEdit(PostEditRequest $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(CategoryFormRequest $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function subCategoryCreate(SubCategoryRequest $request){
        SubCategory::create([
            'sub_category' => $request->input('sub_category_name'),
            'main_category_id' => $request->input('main_category_id'),
        ]);

        return redirect()->route('post.input');
    }

    public function commentCreate(CommentRequest $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }

    // カテゴリー検索機能
    public function search(Request $request){
        $keyword = $request->input('keyword');
        $posts = [];

        if ($request->like_posts) {
            // いいねした投稿を取得
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user')->withCount('postComments')
                ->whereIn('id', $likes)->get();
        } elseif ($request->my_posts) {
            // 自分の投稿を取得
            $posts = Post::with('user')->withCount('postComments')
                ->where('user_id', Auth::id())->get();
        } elseif ($keyword) {
            // キーワード検索による投稿を取得
            $subCategories = SubCategory::where('sub_category', $keyword)->pluck('id');
            $posts = Post::whereIn('id', function ($query) use ($subCategories) {
                $query->select('post_id')->from('post_sub_categories')->whereIn('sub_category_id', $subCategories);
            })->with('user')->withCount('postComments')->get();
        }

        $categories = MainCategory::with('subCategories')->get();

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories'));
    }

    public function showBySubCategory($id){
        $posts = Post::with(['user', 'subCategories'])->whereHas('subCategories', function($query) use ($id) {
            $query->where('sub_category_id', $id);
        })
        ->withCount('postComments')
        ->get();

        $categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories'));
    }
}
