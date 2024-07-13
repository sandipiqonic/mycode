@extends('backend.layouts.page')

@extends('page::backend.pages.components.navbar')

@section('nav-item')
    @foreach ($navs as $nav)
    <li class="nav-item">
        <a class="nav-link" href="{{ route('backend.copyurl',$nav->slug) }}">{{$nav->name}}</a>
    </li>
    @endforeach
@endsection

@section('content')

<div class="container">
    <h1 class="text-center pb-5 " > {!! $data->name !!} </h1> 

    <div class="card">        
        <div class="card-body">
             <p>{!! $data->description !!} </p>
        </div>
    </div>
</div>



@endsection
