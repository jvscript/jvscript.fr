@extends('layouts.app')

@section('title',$script->name.' | jvscript.fr')

@section('javascript')

<script type="text/javascript">
    $('[data-toggle=confirmation]').confirmation();
            $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            });
            $(".script-danger").click(function() {
    var r = confirm("Ce script est interdit sur JVC, attention à vous.");
            if (r == true) {
    return true;
    }
    else {
    return false;
    }
    });</script>
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
        </h1>
    </div>

    <div class="col-md-6" style="margin-top: -8px;margin-bottom: 22px;">

        <!--install -->

        <?php
        if ($script->sensibility == 0) {
            $class = "success";
            $message = "Ce script est jugé safe à l'utilisation.";
            $icon = "fa-check";
            $extra = "script-safe";
        } else if ($script->sensibility == 1) {
            $class = "warning";
            $message = "On ne peut dire si ce script est autorisé dans les forums de JVC.";
            $icon = "fa-exclamation-triangle";
            $extra = "script-warning";
        } else if ($script->sensibility == 2) {
            $class = "danger";
            $message = "Attention, ce script est sensible, son utilisation peut mener à des sanctions.";
            $icon = "fa-exclamation-triangle";
            $extra = "script-danger";
        }
        ?>
        
        <a target="_blank" class="btn btn-primary btn-lg {{$extra}}" href="{{route('script.install',$script->slug)}}"> Installer <i class="fa fa-download"></i> </a>
 
        <span class="sensibility sensibility-{{$class}} " >
            <span class="fa-stack fa-1x "  data-toggle="tooltip" data-placement="right" title="{{$message}}">
                <i class="fa fa-stack-2x "></i>
                <i class="fa {{$icon}} fa-stack-1x "></i>
            </span>
        </span>

    </div>
</div>

<div class="row">

    <div class="col-md-6" id="item-info">
        <div class="panel-body">
            @if ( $script->photo_url != null )
            <div class="desc-img">
                <p>
                    <a href="#"  data-toggle="modal" data-target="#myModal">
                        <img class="img-thumbnail img-responsive" src="{{($script->photo_url)}}" alt="{{$script->name}} logo" />
                    </a>
                </p>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img class="img-thumbnail img-responsive" src="{{($script->photo_url)}}"   alt="{{$script->name}} logo" />
                        </div>
                    </div>

                </div>
            </div>
            @endif

            <div class="stats">
                <p>
                    <b> Ajouté le : </b>  {{$script->created_at->format('d/m/Y')}}
                </p>

                @if(null != $script->version)
                <p>
                    <b> Version : </b>  {{$script->version}}
                </p>
                @endif

                @if(null != $script->last_update)
                <p>
                    <b> Mise à jour le : </b>  {{$script->last_update->format('d/m/Y')}}
                </p>
                @endif
                @if(null != $script->user_id)
                <p>
                    <b> Auteur : </b> <a href="{{url('/search/'.$script->user()->first()->name)}}"  data-toggle="tooltip" data-placement="right" title="Voir tous les scripts de {{$script->user()->first()->name}}">{{$script->user()->first()->name}}</a>
                </p>
                @elseif($script->autor != null)
                <p>
                    <b> Auteur : </b> <a href="{{url('/search/'.$script->autor)}}"  data-toggle="tooltip" data-placement="right" title="Voir tous les scripts de {{$script->autor}}">{{$script->autor}}</a>
                </p>
                @endif

                <p>
                    <b> Note : </b>
                    <?php $note = round($script->note * 2) / 2; ?>
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
                    ({{$script->note_count}} votes)

                </p>

                @for ($i = 1; $i <= 5 ; $i++)
                <form id="note-{{$i}}" action="{{route('script.note',['slug' => $script->slug , 'note' => $i  ])}}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="submit" name="note-{{$i}}" style="display: none;" />
                </form>
                @endfor

                <p>
                    <b>  Install : </b>   {{$script->install_count}} fois
                </p>

                @if ( $script->repo_url != null )
                <p>
                    <b>  Contribuer : <a target="_blank" href="{{$script->repo_url}}">{{str_limit($script->repo_url,40)}}</a>  </b>
                </p>
                @endif

                @if ( $script->topic_url != null )
                <p>
                    <b>   <a target="_blank" class="btn btn-default" href="{{$script->topic_url}}">Voir le topic jvc  <i class="fa fa-gamepad"></i></a>  </b>
                </p>
                @endif

                @if ( $script->website_url != null )
                <p>
                    <b>   <a target="_blank" class="btn btn-default" href="{{$script->website_url}}">Voir le site web  <i class="fa fa-globe"></i></a>  </b>
                </p>
                @endif

                @if ( $script->don_url != null )
                <p>
                    <b>   <a target="_blank" class="btn btn-default" href="{{$script->don_url}}">Faire un don au développeur <i class="fa fa-heart"></i></a>  </b>
                </p>
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
                    <a href="{{route('script.delete',$script->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>

                    Validation :
                    <!--_TODO : confirm dialog-->
                    <a href="{{route('script.validate',$script->slug)}}" class="btn btn-success" data-toggle="confirmation" >Valider</a>
                    <a href="{{route('script.refuse',$script->slug)}}" class="btn btn-warning" data-toggle="confirmation" >Refuser</a>
                </p>
            </div>
        </div>
        <!--    </div>
        </div>-->
        @elseif ((Auth::check() && Auth::user()->id == $script->user_id))
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
                    <a href="{{route('script.delete',$script->slug)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>
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
