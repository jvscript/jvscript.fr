@extends('layouts.app')

@section('javascript')
<script>
    $('[data-toggle=confirmation]').confirmation();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endsection

@section('content') 


<div class="row">

    <div class="col-md-12"> 
        <h1>{{$script->name}}  
            @if($script->autor != null)
            <span class="autor">  by {{$script->autor}} </span>
            @endif   

            <!--install -->
            <a target="_blank" class="btn btn-primary btn-lg" href="{{route('script.install',$script->slug)}}"> Installer <i class="fa fa-download"></i> </a>
            <?php
            if ($script->sensibility == 0) {
                $class = "success";
                $message = "Ce script est jugé safe à l'utilisation.";
                $icon = "fa-check";
            } else if ($script->sensibility == 1) {
                $class = "warning";
                $message = "On ne peut dire si ce script est autorisé dans les forums de JVC.";
                $icon = "fa-exclamation-triangle";
            } else if ($script->sensibility == 2) {
                $class = "danger";
                $message = "Attention, ce script est sensible, son utilisation peu provoquer un ban.";
                $icon = "fa-exclamation-triangle";
            }
            ?>
            <span class="sensibility sensibility-{{$class}} " >
                <span class="fa-stack fa-1x "  data-toggle="tooltip" data-placement="right" title="{{$message}}">
                    <i class="fa fa-circle fa-stack-2x "></i>
                    <i class="fa {{$icon}} fa-stack-1x fa-inverse"></i>
                </span>               
            </span>   
        </h1>
    </div>
</div>

<div class="row">

    <div class="col-md-6">  
        <p>
            @if ( $script->photo_url != null )
            <img class="img-thumbnail img-responsive" src="{{$script->photo_url}}" style="max-height: 450px;" alt="{{$script->name}} logo" />
            @else
            <img class="img-thumbnail img-responsive" src="/assets/images/script.png" style="max-height: 200px;" />
            @endif
        </p> 

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

        @if ( $script->repo_url != null )
        <p>
            <b>  Contribuer : <a target="_blank" href="{{$script->repo_url}}">{{str_limit($script->repo_url,40)}}</a>  </b> 
        </p> 
        @endif

        @if ( $script->don_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$script->don_url}}">Faire un don au développeur <i class="fa fa-heart"></i></a>  </b> 
        </p> 
        @endif 

    </div>

    <div class="col-md-6"> 

        @if( $script->description != '' )
        <p> <br> {!! nl2br(e($script->description)) !!}</p>
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
            <a href="{{route('script.delete',$script->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>  

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
