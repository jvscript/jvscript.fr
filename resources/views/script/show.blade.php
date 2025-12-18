@extends('layouts.app')

@section('title',$script->name.' | jvscript.fr')

@section('javascript')

<script type="text/javascript">
    $('[data-toggle=confirmation]').confirmation();
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });  
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
        <h1>{{$script->name}}
            @if(null != $script->version)
            <span class="label" style="background-color: #555; font-size: 50%; vertical-align: middle;">v{{$script->version}}</span>
            @endif
        </h1>
    </div>

    <div class="col-md-6" style="margin-top: -8px;margin-bottom: 22px;">    
        <a target="_blank" class="btn btn-primary btn-lg" href="{{route('script.install',$script->slug)}}"> <i class="fa fa-download"></i> Installer </a>
    </div>
</div>

<div class="row">

    <div class="col-md-6" id="item-info">
        <div class="panel-body">
            @if ( $script->photo_url != null )
            <div class="desc-img">
                <p>
                    <a href="#" data-toggle="modal" data-target="#myModal">
                        <img class="img-thumbnail img-responsive" src="{{($script->photo_url)}}" alt="{{$script->name}} logo" />
                    </a>
                </p>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img class="img-thumbnail img-responsive" src="{{($script->photo_url)}}" alt="{{$script->name}} logo" />
                        </div>
                    </div>

                </div>
            </div>
            @endif

            <div class="stats">
                <p>
                     Créé le {{$script->created_at->format('d/m/Y')}}
                    @if(null != $script->user_id)
                    par <a href="{{url('/search/'.$script->user()->first()->name)}}" data-toggle="tooltip" data-placement="right" title="Voir tous les scripts de {{$script->user()->first()->name}}">{{$script->user()->first()->name}}</a>
                    @elseif($script->autor != null)
                    par <a href="{{url('/search/'.$script->autor)}}" data-toggle="tooltip" data-placement="right" title="Voir tous les scripts de {{$script->autor}}">{{$script->autor}}</a>
                    @endif
                    
                    @if(null != $script->last_update)
                     |    Mis à jour le {{$script->last_update->format('d/m/Y')}}
                    @endif
                </p>

                <p>
                    <?php $note = round($script->note * 2) / 2; ?>
                    @for ($i = 1; $i <= $note ; $i++)
                        <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star" aria-hidden="true"></i></a>
                        @endfor
                        <?php $stop = $i; ?>
                        @for ($i ; $i <= 5 ; $i++)
                            @if($i==$stop && $note> ( $i -1 ) )
                            <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                            @else
                            <a href="#" onclick="document.getElementById('note-{{$i}}').submit(); return false;"><i class="fa fa-star-o" aria-hidden="true"></i></a>
                            @endif
                            @endfor
                            ({{$script->note_count}} votes) &nbsp; | &nbsp; <i class="fa fa-download"></i> {{$script->install_count}} install

                </p>

                @for ($i = 1; $i <= 5 ; $i++)
                    <form id="note-{{$i}}" action="{{route('script.note',['slug' => $script->slug , 'note' => $i  ])}}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="submit" name="note-{{$i}}" style="display: none;" />
                    </form>
                    @endfor

                    @if ( $script->repo_url != null || $script->topic_url != null || $script->website_url != null || $script->don_url != null )
                    <div class="btn-group-responsive" style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px;">
                        @if ( $script->repo_url != null )
                        <a target="_blank" class="btn btn-default" href="{{$script->repo_url}}"><i class="fa fa-code"></i> Contribuer</a>
                        @endif

                        @if ( $script->topic_url != null )
                        <a target="_blank" class="btn btn-default" href="{{$script->topic_url}}"><i class="fa fa-gamepad"></i> Topic JVC</a>
                        @endif

                        @if ( $script->website_url != null )
                        <a target="_blank" class="btn btn-default" href="{{$script->website_url}}"><i class="fa fa-globe"></i> Site web</a>
                        @endif

                        @if ( $script->don_url != null )
                        <a target="_blank" class="btn btn-default" href="{{$script->don_url}}"><i class="fa fa-heart"></i> Don</a>
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
                    <a href="{{route('script.delete',$script->slug)}}" class="btn btn-danger" data-toggle="confirmation">Supprimer</a>

                    Validation :
                    <!--_TODO : confirm dialog-->
                    <a href="{{route('script.validate',$script->slug)}}" class="btn btn-success" data-toggle="confirmation">Valider</a>
                    <a href="{{route('script.refuse',$script->slug)}}" class="btn btn-warning" data-toggle="confirmation">Refuser</a>
                </p>
            </div>
        </div>
        <!--    </div>
        </div>-->
        @elseif ((Auth::check() && ( Auth::user()->id == $script->user_id || Auth::user()->id == $script->poster_user_id )))
        <!--<div class="row">
            <div class="col-md-6">-->
        <div class="panel-body">
            <div class="admin">
                @if($script->status == 0)
                <div class="alert alert-warning" role="alert">
                    Votre script est en attente de validation
                </div>
                @elseif($script->status == 1)
                <div class="alert alert-success" role="alert">
                    Votre script a été validé.
                </div>
                @elseif($script->status == 2)
                <div class="alert alert-danger" role="alert">
                    Votre script a été refusé.
                </div>
                @endif
                <p>
                    Action :
                    <a href="{{route('script.edit',$script->slug)}}" class="btn btn-primary">Editer</a>
                    <a href="{{route('script.delete',$script->slug)}}" class="btn btn-danger" data-toggle="confirmation">Supprimer</a>
                </p>

            </div>
        </div>
        <!--    </div>
        </div>-->

        @endif

        @include('global.comments', ['commentClass' => 'hidden-xs hidden-sm' , 'recaptcha' => 1])

    </div>

    <div class="col-md-6" id="item-description">
        <div class="panel-body desc">
            @if( $script->description != '' )
            {!! (($script->description )) !!}
            @endif
        </div>
    </div>

    @include('global.comments', ['commentClass' => 'hidden-md hidden-lg col-md-6', 'recaptcha' => 2])




</div>


@endsection