@extends('layouts.app')

@section('javascript')
<script>
    $('[data-toggle=confirmation]').confirmation();
</script>
@endsection

@section('content')


<div class="row">

    <div class="col-md-6"> 
        <h1>{{$script->name}}   @if($script->autor != null)
            by {{$script->autor}}
            @endif  
            <a target="_blank" class="btn btn-primary" href="{{route('script.install',$script->slug)}}"> Installer <i class="fa fa-download"></i> </a>
        </h1>

        <?php
        if ($script->sensibility == 0) {
            $class = "success";
            $message = "Ce script est jugé safe à l'utilisation.";
        } else if ($script->sensibility == 1) {
            $class = "warning";
            $message = "On ne peut dire si ce script est autorisé dans les forums de JVC.";
        } else if ($script->sensibility == 2) {
            $class = "danger";
            $message = "Attention, ce script est sensible, son utilisation peu provoquer un ban.";
        }
        ?>
        <div class="alert alert-{{$class}} alert-dismissible" role="alert"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{$message}}
        </div> 

    </div>


</div>

<div class="row">

    <div class="col-md-6"> 

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

@if ((Auth::check() && Auth::user()->isAdmin())) 
<div class="row">
    <div class="col-md-6"> 
         <hr>
        @if($script->status == 0)
        <div class="alert alert-warning" role="alert">
            Ce script est en attente de validation
        </div>
        @elseif($script->status == 1)
        <div class="alert alert-success" role="alert">
            Ce script a été validé.
        </div>         
        @elseif($script->status == 2)
        <div class="alert alert-danger" role="alert">
            Ce script a été refusé.
        </div>
        @endif 
        <p>
            Edition : 
            <a href="{{route('script.edit',$script->slug)}}" class="btn btn-primary">Editer</a>
            <a href="#" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>  

            Validation : 
            <!--_TODO : confirm dialog-->
            <a href="{{route('script.validate',$script->slug)}}" class="btn btn-success" data-toggle="confirmation" >Valider</a>
            <a href="{{route('script.refuse',$script->slug)}}" class="btn btn-warning" data-toggle="confirmation" >Refuser</a>
        </p> 
        <hr>
    </div> 
</div>
@endif


@endsection
