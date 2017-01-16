@extends('layouts.app')

@section('content')

<div class="header" id="banner">
    <div class="row">
        <div class="col-md-6">

            <h1>{{$script->name}}   @if($script->autor != null)
                by {{$script->autor}}
                @endif  
                <a target="_blank" class="btn btn-primary" href="{{$script->js_url}}"> Installer <i class="fa fa-download"></i> </a>
            </h1>

            <!--_TODO : sensibility alert if not safe-->
            <!--_TODO : bouton ton au developpeur -->
            <p>
                <b> Ajouté le : </b>  {{$script->created_at->format('d/m/Y')}} 
            </p> 
            <p>
                <b> Note : </b>  
                @for ($i = 0; $i < $script->note ; $i++)
                <i class="fa fa-star" aria-hidden="true"></i>  
                @endfor
                @for ($i ; $i < 5 ; $i++)
                <i class="fa fa-star-o" aria-hidden="true"></i>  
                @endfor  
                ({{$script->note_count}} votes)
            </p> 

            <p>
                <b>  Install :   {{$script->install_count}} fois </b> 
            </p> 

            @if( $script->description != '' )
            <p> {!! nl2br(e($script->description)) !!}</p>
            @endif

        </div>

        <div class="col-md-6 header"> 

            @if ( $script->photo_url != null )
            <p>
                <img class="img-thumbnail img-responsive" src="{{$script->photo_url}}" />
            </p> 
            @endif
            @if ( $script->repo_url != null )
            <p>
                <b>  Contribuer ?   <a target="_blank" href="{{$script->repo_url}}">{{$script->repo_url}}</a>  </b> 
            </p> 
            @endif

        </div>
    </div>
</div>


@endsection
