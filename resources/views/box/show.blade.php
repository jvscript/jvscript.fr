@extends('layouts.app')

@section('title',$idea->title.' | jvscript.fr')

@section('javascript')

<script type="text/javascript">
    $('[data-toggle=confirmation]').confirmation();
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endsection

@section('content')


<div class="row">

    <div class="col-md-6">
        <h1>{{$idea->title}}
        </h1>
    </div>

</div>

<div class="row">

    <div class="col-md-6" id="item-info">
        <div class="panel-body">

            <div class="stats">
                <p>
                    <b> Ajouté le : </b>  {{$idea->created_at->format('d/m/Y')}}
                </p> 
                <p>
                    <b> Posté par : </b>  {{$idea->user()->first()->name}}
                </p> 
            </div>
        </div> 

        @if ((Auth::check() && Auth::user()->isAdmin()))

        <div class="panel-body">
            <div class="admin"> 
                @if($idea->status == 0)
                <div class="alert alert-warning" role="alert">
                    Cette idée est en attente de validation
                </div>
                @elseif($idea->status == 1)
                <div class="alert alert-success" role="alert">
                    Cette idée a été validé.
                </div>
                @elseif($idea->status == 2)
                <div class="alert alert-danger" role="alert">
                    Cette idée a été refusé.
                </div>
                @endif
                <p>
                    Edition :
                                        <!--<a href="{{route('script.edit',$idea->slug)}}" class="btn btn-primary">Editer</a>-->
                                        <a href="{{route('box.delete',$idea->id)}}" class="btn btn-danger" data-toggle="confirmation" >Supprimer</a>

                    Validation :
                    <!--_TODO : confirm dialog-->
                    <a href="{{route('box.validate',$idea->id)}}" class="btn btn-success" data-toggle="confirmation" >Valider</a>
                    <a href="{{route('box.refuse',$idea->id)}}" class="btn btn-warning" data-toggle="confirmation" >Refuser</a>
                </p>
            </div>
        </div> 
        @endif

    </div> 
    <div class="col-md-6" id="item-description">
        <div class="panel-body desc">
            @if( $idea->description != '' )
            {!! (($idea->description )) !!}
            @endif
        </div>

        @include('global.comments-idea', ['commentClass' => ' ' , 'recaptcha' => 1])
    </div>




</div>


@endsection
