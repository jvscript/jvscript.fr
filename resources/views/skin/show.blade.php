@extends('layouts.app')

@section('title',$skin->name.' | jvscript.fr')

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

    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif


    <div class="col-md-6">
        <h1>{{$skin->name}}
            @if(null != $skin->version)
            <span class="label" style="background-color: #555; font-size: 50%; vertical-align: middle;">v{{$skin->version}}</span>
            @endif
        </h1>
    </div>

    <div class="col-md-6" style="margin-top: -8px;margin-bottom: 22px;">
        
        
        <a target="_blank" class="btn btn-primary btn-lg" href="{{route('skin.install',$skin->slug)}}"  onclick="document.getElementById('install').submit(); return false;"> <i class="fa fa-download"></i> Installer </a>
        <form id="install" action="{{route('skin.install',$skin->slug)}}" target="_blank" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="panel-body">
            @if ( $skin->photo_url != null )
            <div class="desc-img">
                <p>
                    <a href="#" target="_blank" data-toggle="modal" data-target="#myModal">
                        <img class="img-thumbnail img-responsive" src="{{$skin->photo_url}}" alt="{{$skin->name}} logo" />
                    </a>
                </p>
            </div>
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
            @endif


            <div class="stats">
                <p>
                    Créé le {{$skin->created_at->format('d/m/Y')}}
                    @if(null != $skin->user_id)
                    par <a href="{{url('/search/'.$skin->user()->first()->name)}}" data-toggle="tooltip" data-placement="right" title="Voir tous les skins de {{$skin->user()->first()->name}}">{{$skin->user()->first()->name}}</a>
                    @elseif($skin->autor != null)
                    par <a href="{{url('/search/'.$skin->autor)}}" data-toggle="tooltip" data-placement="right" title="Voir tous les skins de {{$skin->autor}}">{{$skin->autor}}</a>
                    @endif

                    @if(null != $skin->last_update)
                    | Mis à jour le {{$skin->last_update->format('d/m/Y')}}
                    @endif
                </p>

                <p>
                    <?php $note = round($skin->note * 2) / 2; ?>
                    @for ($i = 1; $i <= $note ; $i++)
                    <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star" aria-hidden="true"></i></a>
                    @endfor
                    <?php $stop = $i; ?>
                    @for ($i ; $i <= 5 ; $i++)
                    @if($i == $stop && $note > ( $i -1 ) )
                    <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                    @else
                    <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star-o" aria-hidden="true"></i></a>
                    @endif
                    @endfor
                    ({{$skin->note_count}} votes) &nbsp; | &nbsp; <i class="fa fa-download"></i> {{$skin->install_count}} install
                </p>

                @for ($i = 1; $i <= 5 ; $i++)
                <form id="note-{{$i}}" action="{{route('skin.note',['slug' => $skin->slug , 'note' => $i  ])}}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="submit" name="note-{{$i}}" style="display: none;" />
                </form>
                @endfor

                @if ( $skin->repo_url != null || $skin->topic_url != null || $skin->website_url != null || $skin->don_url != null )
                <div class="btn-group-responsive" style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px;">
                    @if ( $skin->repo_url != null )
                    <a target="_blank" class="btn btn-default" href="{{$skin->repo_url}}"><i class="fa fa-code"></i> Contribuer</a>
                    @endif

                    @if ( $skin->topic_url != null )
                    <a target="_blank" class="btn btn-default" href="{{$skin->topic_url}}"><i class="fa fa-gamepad"></i> Topic JVC</a>
                    @endif

                    @if ( $skin->website_url != null )
                    <a target="_blank" class="btn btn-default" href="{{$skin->website_url}}"><i class="fa fa-globe"></i> Site web</a>
                    @endif

                    @if ( $skin->don_url != null )
                    <a target="_blank" class="btn btn-default" href="{{$skin->don_url}}"><i class="fa fa-heart"></i> Don</a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        @if ((Auth::check() && Auth::user()->isAdmin()))
        <!--<div class="row">
            <div class="col-md-6">-->
        <div class="panel-body">
            <div class="admin">
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
            </div>
        </div>
        <!--    </div>
        </div>-->
        @elseif ((Auth::check() && ( Auth::user()->id == $skin->user_id || Auth::user()->id == $skin->poster_user_id )))
        <!--<div class="row">
            <div class="col-md-6">-->
        <div class="panel-body">
            <div class="admin">

                @if($skin->status == 0)
                <div class="alert alert-warning" role="alert">
                    Votre skin est en attente de validation
                </div>
                @elseif($skin->status == 1)
                <div class="alert alert-success" role="alert">
                    Votre skin a été validé.
                </div>
                @elseif($skin->status == 2)
                <div class="alert alert-danger" role="alert">
                    Votre skin a été refusé.
                </div>
                @endif
                <p>
                    Edition :
                    <a href="{{route('skin.edit',$skin->slug)}}" class="btn btn-primary">Editer</a>
                    <a href="{{route('skin.delete',$skin->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>
                </p>
            </div>
        </div>

        <!--    </div>
        </div>-->

        @endif 

        @include('global.comments', ['commentClass' => 'hidden-xs hidden-sm', 'recaptcha' => 1])
    </div>

    <div class="col-md-6">
        <div class="panel-body desc">
            @if( $skin->description != '' )
            {!! (($skin->description )) !!}
            @endif
        </div>

    </div>


    @include('global.comments', ['commentClass' => 'hidden-md hidden-lg col-md-6', 'recaptcha' => 2])
</div>



@endsection
