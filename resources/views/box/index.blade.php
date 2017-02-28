@extends('layouts.app')

@section('title','La boite à idées | jvscript.io')

@section('content')

@section('javascript')
<script>
    $(document).ready(function () {
        //envoie du formulaire de commentaire en ajax
        $(document).on('submit', '.ajax-comment', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id_item = $this.attr("id-item"); //id de l'idée
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
//                dataType: 'json', // JSON
                success: function (data) {
                    if (data != "") {
                        $("#comment-" + id_item).html(data);
                    }
                    else {
                        //_TODO show error wait 30 seconde
                    }
                }
            });
        });
//            //_TODO pagination commentaire
//                    function getAnomalies(page) {
//                    $.ajax({
//                    url : '?page=' + page,
//                            dataType: 'json',
//                    }).done(function (data) {
//                    $('#anomalies').html(data);
////            location.hash = '?page=' + page;
//                    }).fail(function () {
//                    alert("Erreur lors de l'affichage des anomalies.");
//                    });
//                    }
//            var page = 1;
//                    console.log(page);
//                    $(document).ready(function() {
//            $(document).on('click', '.pagination a', function (e) {
//            page = $(this).attr('href').split('page=')[1];
//                    getAnomalies(page);
//                    e.preventDefault();
//                    console.log(page);
//            });
    });
</script>
@endsection

<style>
    .vcenter {
        display: flex;
        align-items: center;
    }
</style>

<div class="row">

    <div class="col-md-8 col-md-offset-2">

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <p class="text-center">
            <a href="{{route("box.form")}}" class="btn btn-default">Proposer une idée <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
            </a><br><br>
        </p>
        <?php
        $types_label = ['Script', 'Skin'];
        ?>

        @foreach ($ideas as $key => $idea)

        <div class="panel-body vcenter">
            <div class="col-xs-2 text-center" style=" ">
                <a class="like center-block"  href="{{route('box.like',['id' => $idea->id])}}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <b class="note center-block"> 
                    {{$idea->likes()->where('liked',1)->count() - $idea->likes()->where('liked',0)->count()}}
                </b> <a class="dislike center-block" href="{{route('box.dislike',['id' => $idea->id,'dislike' => true])}}"> <i class="fa fa-arrow-down" aria-hidden="true"></i> </a>
            </div>

            <div class="col-xs-10">
                <div class="panel idea">
                    <div class="panel-heading idea " style='text-align: left'>
                        [{{$types_label[$idea->type]}}] 
                        <!-- TODO: supprimer ce lien quand les commentaires accordéon sont prêts-->
                        <a href='{{route('box.show',$idea->id)}}'>{{str_limit($idea->title,50)}}</a>

                        <span class="date pull-right hidden-xs">
                            Par {{$idea->user()->first()->name}} le
                            {{$idea->created_at->format('d/m/Y')}}
                        </span>
                    </div> 
                    <div class="panel-body idea" style="  word-wrap: break-word;  ">{{str_limit($idea->description,150)}}
                    </div>
                    <div class="panel-body idea">  
                        <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#comment-{{$idea->id}}" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-comment" aria-hidden="true"></i>   {{$idea->comments()->count()}} 
                        </button>
                        <div class="collapse" id="comment-{{$idea->id}}">
                            @include('global.comments-idea', [ 'comments' =>  $idea->comments()->latest()->paginate(5) , 'commentClass' => ' ' , 'recaptcha' => 1])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>



</div>


@endsection
