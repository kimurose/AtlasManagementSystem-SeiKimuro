@extends('layouts.sidebar')
@section('content')

<div class="pt-5" style="background:#ECF1F6;">
      <div class="w-75 m-auto pt-5 pb-5 d-flex" style="align-items:center; justify-content:center; background: #fff;">
        <div class="w-100 m-auto" style="border-radius:5px;">
        <p class="text-center">{{ $calendar->getTitle() }}</p>
          {!! $calendar->render() !!}
          <div class="adjust-table-btn m-auto text-right">
            <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
          </div>
        </div>
      </div>
</div>
@endsection