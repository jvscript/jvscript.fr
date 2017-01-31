@extends('layouts.app')

@section('javascript')
<script>
    $('[data-toggle=confirmation]').confirmation();
</script>
@endsection

@section('content')

 @if ((Auth::check() && Auth::user()->isAdmin())) 
<div class="row">
    <div class="col-md-6"> 
        @if($skin->status == 0)
        <div class="alert alert-warning" role="alert">
            Ce skin est en attente de validation
        </div>
         @elseif($skin->status == 1)
        <div class="alert alert-success" role="alert">
            Ce skin a été validé.
        </div>         
        @elseif($skin->status == 2)
        <div class="alert alert-danger" role="alert">
            Ce skin a été refusé.
        </div>
        @endif

        <p>
            Edition : 
            <a href="{{route('skin.edit',$skin->slug)}}" class="btn btn-primary">Editer</a>
            <a href="#" class="btn btn-danger"  data-toggle="confirmation">Supprimer</a>  

            Validation : 
            <!--_TODO : confirm dialog-->
            <a href="{{route('skin.validate',$skin->slug)}}" data-toggle="confirmation" class="btn btn-success">Valider</a>
            <a href="{{route('skin.refuse',$skin->slug)}}"  data-toggle="confirmation" class="btn btn-warning">Refuser</a>
        </p> 
        <hr>
    </div> 
</div>
@endif

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
