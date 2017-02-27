@extends('layouts.app')

@section('content')



<style>


</style>

@section('javascript')

@endsection

<div class="row">

    <div class="col-md-6 col-md-offset-3">

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <p>
            <a href="{{route("box.form")}}" class="btn btn-default">Proposer une idée <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
            </a>
        </p>

        @foreach ($ideas as $key => $idea)

        <div class="panel panel-default">

            <div class="panel-heading text-left" style='text-align: left'>
                <a href='{{route('box.show',$idea->id)}}'>{{$idea->title}}</a>

                <span class="date pull-right">
                    Posté par {{$idea->user()->first()->name}} le 
                    {{$idea->created_at->format('d/m/Y à H:i')}} 
                </span>
            </div>

            <div class="panel-body">{{$idea->description}}
            </div> 

        </div>

        @endforeach

    </div>



</div>


@endsection
