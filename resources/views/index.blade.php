@extends('layouts.app')

@section('content') 


<style>

</style>

<div class="header" id="banner">
    <div class="row">
        <div class="col-md-12">

            <h1>Bienvenue sur jvscript.io</h1>

            <img style="max-height: 230px" class="img-responsive  center-block" src="/assets/images/jvscript.png"/>

            <p class="text-center"> Un site pour regrouper les scripts JVC et rapprocher les développeurs.</p>

        </div>
    </div>

</div>

<div class="row">

    @foreach( $scripts as $script ) 
    <div class="col-sm-3 col-md-3">
        <div class="thumbnail">
            <a href="{{route('script.show',['slug' => $script->slug ])}}"><img src="/assets/images/jvscript-nb.png" class="img-thumbnail" alt="{{$script->name}} logo" /></a>
            <div class="caption">
                <h4>{{$script->name}}
                    @if($script->autor != null)
                    by {{$script->autor}}
                    @endif                                
                </h4>
                <p class="pull-left">
                    @for ($i = 0; $i < $script->note ; $i++)
                    <i class="fa fa-star" aria-hidden="true"></i>  
                    @endfor
                    @for ($i ; $i < 5 ; $i++)
                    <i class="fa fa-star-o" aria-hidden="true"></i>  
                    @endfor 
                </p>
                <p class="text-right"><i class="fa fa-download" aria-hidden="true"></i> {{$script->install_count}} </p>
            </div>
        </div>
    </div> 
    @endforeach

</div>


@endsection
