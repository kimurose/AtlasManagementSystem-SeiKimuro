@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-100 m-auto h-75">
    <p style="margin-left: 10%;"><span>{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}</span><span class="ml-3">{{ $part }}部</span></p>
    <div class="custom-container">
      <div class="h-75  custom-table-container">
        <table class="table custom-table">
          <tr class="text-center">
            <th class="w-25">ID</th>
            <th class="w-25">名前</th>
            <th class="w-25">場所</th>
          </tr>
          @foreach($reservePersons as $reservePerson)
            @foreach($reservePerson->users as $user)
              <tr class="text-center">
                <td class="w-25">{{ $user->id }}</td>
                <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
                <td class="w-25">リモート</td>
              </tr>
            @endforeach
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>
@endsection