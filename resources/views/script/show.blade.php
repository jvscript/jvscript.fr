@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-md-6"> 
        <h1>{{$script->name}}   @if($script->autor != null)
            by {{$script->autor}}
            @endif  
            <a target="_blank" class="btn btn-primary" href="{{route('script.install',$script->slug)}}"> Installer <i class="fa fa-download"></i> </a>
        </h1>

    </div>

</div>

<div class="row">

    <div class="col-md-6"> 



        <!--_TODO : sensibility alert if not safe-->
        <!--_TODO : bouton ton au developpeur -->
        <p>
            <b> Ajouté le : </b>  {{$script->created_at->format('d/m/Y')}} 
        </p> 
        <p>
            <b> Note : </b>  
            <?php $note = round($script->note * 2) / 2; ?>
            @for ($i = 1; $i <= $note ; $i++)
            <a href="{{route('script.note',['slug' => $script->slug , 'note' => $i  ])}}"><i class="fa fa-star" aria-hidden="true"></i></a>
            @endfor
            <?php $stop = $i; ?> 
            @for ($i ; $i <= 5 ; $i++)                    
            @if($i == $stop && $note > ( $i -1 ) )
            <a href="{{route('script.note',['slug' => $script->slug , 'note' => $i  ])}}"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
            @else
            <a href="{{route('script.note',['slug' => $script->slug , 'note' => $i ])}}"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            @endif

            @endfor 

            ({{$script->note_count}} votes)
        </p> 

        <p>
            <b>  Install : </b>   {{$script->install_count}} fois 
        </p> 

        @if( $script->description != '' )
        <p> {!! nl2br(e($script->description)) !!}</p>
        @endif

    </div>

    <div class="col-md-6"> 

        @if ( $script->photo_url != null )
        <p>
            <img class="img-thumbnail img-responsive" src="{{$script->photo_url}}" />
        </p> 
        @endif
        @if ( $script->repo_url != null )
        <p>
            <b>  Contribuer ?   <a target="_blank" href="{{$script->repo_url}}">{{str_limit($script->repo_url,40)}}</a>  </b> 
        </p> 
        @endif

        @if ( $script->don_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$script->don_url}}">Faire un don au développeur</a>  </b> 
        </p> 
        @endif

    </div>
</div>


@endsection
