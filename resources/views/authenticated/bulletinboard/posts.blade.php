@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p class=""><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>

      
       
      <div class="post_bottom_area d-flex">
        <!-- カテゴリー表示 -->
        <p>
          @foreach($post->subCategories as $subCategory)
          <a href="{{ route('post.by.sub_category', $subCategory->id) }}" class="sub_categories">
            <span>{{ $subCategory->sub_category ?? '未設定' }}</span>
          </a>
          @endforeach
        </p>
        <div class="d-flex post_status">
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="">{{ $post->post_comments_count }}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likeCounts() }}</span></p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likeCounts() }}</span></p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class="post_submit"><a href="{{ route('post.input') }}">投稿</a></div>
      <form action="{{ route('post.search') }}" method="get" id="postSearchRequest">
        <div class="">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_like_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
      </form>
      
      <ul class="category_list">
        @foreach($categories as $category)
        <li class="main_category" category_id="{{ $category->id }}">
          <span class="category_title">{{ $category->main_category }}</span>
          <i class="arrow-icon fas fa-chevron-down"></i>
          @if($category->subCategories->isNotEmpty())
          <ul class="sub_category_list" style="display: none;">
            @foreach($category->subCategories as $subCategory)
            <li><a href="{{ route('post.by.sub_category', $subCategory->id) }}">{{ $subCategory->sub_category ?? '未設定' }}</a></li>
            @endforeach
          </ul>
          @endif
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection