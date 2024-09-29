<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Post;


class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $table = 'sub_categories'; // 正しく指定されています

    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        // リレーションの定義
        return $this->belongsTo(MainCategory::class);
    }

    public function posts(){
        // リレーションの定義
        return $this->belongsToMany(Post::class, 'post_sub_categories', 'sub_category_id', 'post_id');
    }
}