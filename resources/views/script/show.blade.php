@extends('layouts.app')

@section('title',$script->name.' | jvscript.io')

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

    <div class="col-md-6">
        <h1>{{$script->name}}
        </h1>
    </div>

    <div class="col-md-6" style="margin-top: 22px;margin-bottom: 22px;">

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
            $message = "Attention, ce script est sensible, son utilisation peut mener à des sanctions.";
            $icon = "fa-exclamation-triangle";
        }
        ?>
        <span class="sensibility sensibility-{{$class}} " >
            <span class="fa-stack fa-1x "  data-toggle="tooltip" data-placement="right" title="{{$message}}">
                <i class="fa fa-stack-2x "></i>
                <i class="fa {{$icon}} fa-stack-1x "></i>
            </span>
        </span>

    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="panel-body">
            <div class="desc-img">
                <p>
                    @if ( $script->photo_url != null )
                    <a href="{{$script->photo_url}}"  data-toggle="modal" data-target="#myModal">
                        <img class="img-thumbnail img-responsive" src="{{$script->photo_url}}" alt="{{$script->name}} logo" />
                    </a>
                    @else
                    <img class="img-thumbnail img-responsive" src="/assets/images/script.jpg" />
                    @endif
                </p>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-body text-center">
                            <img class="img-thumbnail img-responsive" src="{{$script->photo_url}}" style="" alt="{{$script->name}} logo" />
                        </div>

                    </div>
                </div>
            </div>




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
                    <b> Auteur : </b> {{$script->user()->first()->name}}
                </p>
                @elseif($script->autor != null)
                <p>
                    <b> Auteur : </b> {{$script->autor}}
                </p>
                @endif

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
    </div>

    <div class="col-md-6">
        <div class="panel-body desc">
            @if( $script->description != '' )
            {!! (($script->description )) !!}
            @endif
        </div>
    </div>
</div>


@if ((Auth::check() && Auth::user()->isAdmin()))
<div class="row">
    <div class="col-md-6">
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
    </div>
</div>
@elseif ((Auth::check() && Auth::user()->id == $script->user_id))
<div class="row">
    <div class="col-md-6">
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
    </div>
</div>

@endif


@endsection
