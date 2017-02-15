@extends('layouts.app')

@section('title',$skin->name.' | jvscript.io')

@section('javascript')
<script>
    $('[data-toggle=confirmation]').confirmation();


</script>
@endsection

@section('content')


<div class="row">

    <div class="col-md-6">
        <h1>{{$skin->name}}

        </h1>

    </div>

    <div class="col-md-6" style="margin-top: 22px;margin-bottom: 22px;">
        <a target="_blank" class="btn btn-primary btn-lg" href="{{route('skin.install',$skin->slug)}}"> Installer <i class="fa fa-download"></i> </a>

    </div>
</div>

<div class="row">

    <div class="col-md-6">

        <p>
            @if ( $skin->photo_url != null )
            <a href="{{$skin->photo_url}}" target="_blank" data-toggle="modal" data-target="#myModal">
                <img class="img-thumbnail img-responsive" src="{{$skin->photo_url}}" style="max-height: 300px;max-width: 400px;" alt="{{$skin->name}} logo" />
            </a>

            <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-body text-center">
                        <img class="img-thumbnail img-responsive" src="{{$skin->photo_url}}" style="" alt="{{$skin->name}} logo" />
                    </div>

                </div>
            </div>
        </div>

        @else
        <img class="img-thumbnail img-responsive" src="/assets/images/skin.jpg" style="max-height: 300px;" />
        @endif
        </p>

        <p>
            <b> Ajouté le : </b>  {{$skin->created_at->format('d/m/Y')}}
        </p>
        @if(null != $skin->last_update)
        <p>
            <b> Mise à jour le : </b>  {{$skin->last_update->format('d/m/Y')}}
        </p>
        @endif
        @if(null != $skin->user_id)
        <p>
            <b> Auteur : </b> {{$skin->user()->first()->name}}
        </p>
        @elseif($skin->autor != null)
        <p>
            <b> Auteur : </b> {{$skin->autor}}
        </p>
        @endif

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

        @if ( $skin->repo_url != null )
        <p>
            <b>  Contribuer : <a target="_blank" href="{{$skin->repo_url}}">{{str_limit($skin->repo_url,40)}}</a>  </b>
        </p>
        @endif

        @if ( $skin->topic_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$skin->topic_url}}">Voir le topic jvc  <i class="fa fa-gamepad"></i></a>  </b>
        </p>
        @endif

        @if ( $skin->website_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$skin->website_url}}">Voir le site web  <i class="fa fa-globe"></i></a>  </b>
        </p>
        @endif

        @if ( $skin->don_url != null )
        <p>
            <b>   <a target="_blank" class="btn btn-default" href="{{$skin->don_url}}">Faire un don au développeur  <i class="fa fa-heart"></i></a>  </b>
        </p>
        @endif

    </div>

    <div class="col-md-6 description">

        @if( $skin->description != '' )         
        {!! (($skin->description )) !!}
        @endif


    </div>
</div>


@if ((Auth::check() && Auth::user()->isAdmin()))
<div class="row">
    <div class="col-md-6">
        <hr>
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
            <a href="{{route('skin.delete',$skin->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>

            Validation :
            <!--_TODO : confirm dialog-->
            <a href="{{route('skin.validate',$skin->slug)}}" class="btn btn-success" data-toggle="confirmation" >Valider</a>
            <a href="{{route('skin.refuse',$skin->slug)}}" class="btn btn-warning" data-toggle="confirmation" >Refuser</a>
        </p>
        <hr>
    </div>
</div>
@elseif ((Auth::check() && Auth::user()->id == $skin->user_id))
<div class="row">
    <div class="col-md-6">
        <hr>
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
            <a href="{{route('skin.delete',$skin->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>
        </p>
        <hr>
    </div>
</div>
@endif


@endsection
