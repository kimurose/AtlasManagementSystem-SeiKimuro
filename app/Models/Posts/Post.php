<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Like;
use App\Models\Posts\PostComment;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
    }

    // コメント数
    public function commentCount() {
        return $this->postComments()->count();
    }

    // 投稿に対する「いいね」のリレーション
    public function likes()
    {
        return $this->hasmany(like::class, 'like_post_id');
    }

    // 投稿に対する「いいね」の数を取得するメソッド
    public function likeCounts()
    {
        return $this->likes()->count();
    }
}