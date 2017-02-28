@extends('layouts.app')

@section('title','La boite à idées | jvscript.io')

@section('content')


@section('javascript')

@endsection

<div class="row">

    <div class="col-md-8 col-md-offset-2">

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <p class="text-center">
            <a href="{{route("box.form")}}" class="btn btn-default">Proposer une idée <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
            </a>
        </p>
        <?php

        $types_label = ['Script', 'Skin'];
        ?>

        @foreach ($ideas as $key => $idea)
        <div class="row panel-body">
            <div class="col-xs-1" style="padding-top:18px">
                <p>
                    <a class="like" href="{{route('box.like',['id' => $idea->id])}}"> <i class="fa fa-arrow-up" aria-hidden="true"></i>  </a>
                    <br><b class="note">{{$idea->likes()->where('liked',1)->count() - $idea->likes()->where('liked',0)->count()}}</b><br>
                    <a class="dislike" href="{{route('box.dislike',['id' => $idea->id,'dislike' => true])}}"> <i class="fa fa-arrow-down" aria-hidden="true"></i> </a>
                </p>
            </div>

            <div class="col-xs-10">
                <div class="panel idea">
                    <div class="panel-heading idea" style='text-align: left'>
                        [{{$types_label[$idea->type]}}]

                        <!-- TODO: supprimer ce lien quand les commentaires accordéon sont prêts-->
                        <a href='{{route('box.show',$idea->id)}}'>{{$idea->title}}</a>

                        <span class="date pull-right">
                            Par {{$idea->user()->first()->name}} le
                            {{$idea->created_at->format('d/m/Y')}}
                        </span>
                    </div>

                    <div class="panel-body idea">{{$idea->description}}
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>



</div>


@endsection
