@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message')
    你沒有相關權限.
   <div>
       <a href="/manage">
        反回主頁
       </a>
   </div>
@endsection

{{-- @section('message', __($exception->getMessage() ?: 'Forbidden')) --}}
