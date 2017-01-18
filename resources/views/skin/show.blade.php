@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-md-6"> 
        <h1>{{$skin->name}}   @if($skin->autor != null)
            by {{$skin->autor}}
            @endif  
            <a target="_blank" class="btn btn-primary" href="{{route('skin.install',$skin->slug)}}"> Installer <i class="fa fa-download"></i> </a>
        </h1>

    </div>

</div>

<div class="row">

    <div class="col-md-6"> 

        <!--_TODO : sensibility alert if not safe-->
        <!--_TODO : bouton ton au developpeur -->
        <p>
            <b> Ajouté le : </b>  {{$skin->created_at->format('d/m/Y')}} 
        </p> 
        <p>
            <b> Note : </b>  
            <?php $note = round($skin->note * 2) / 2; ?>
            @for ($i = 1; $i <= $note ; $i++)
            <a href="{{route('skin.note',['slug' => $skin->slug , 'note' => $i  ])}}"><i class="fa fa-star" aria-hidden="true"></i></a>
            @endfor
            <?php $stop = $i; ?> 
            @for ($i ; $i <= 5 ; $i++)                    
            @if($i == $stop && $note > ( $i -1 ) )
            <a href="{{route('skin.note',['slug' => $skin->slug , 'note' => $i  ])}}"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
            @else
            <a href="{{route('skin.note',['slug' => $skin->slug , 'note' => $i ])}}"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            @endif

            @endfor 

            ({{$skin->note_count}} votes)
        </p> 

        <p>
            <b>  Install : </b>   {{$skin->install_count}} fois 
        </p> 

        @if( $skin->description != '' )
        <p> {!! nl2br(e($skin->description)) !!}</p>
        @endif

    </div>

    <div class="col-md-6"> 

        @if ( $skin->photo_url != null )
        <p>
            <img class="img-thumbnail img-responsive" src="{{$skin->photo_url}}" />
        </p> 
        @endif
        @if ( $skin->repo_url != null )
        <p>
            <b>  Contribuer ?   <a target="_blank" href="{{$skin->repo_url}}">{{str_limit($skin->repo_url,40)}}</a>  </b> 
        </p> 
        @endif

        @if ( $skin->don_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$skin->don_url}}">Faire un don au développeur</a>  </b> 
        </p> 
        @endif

    </div>
</div>


@endsection
