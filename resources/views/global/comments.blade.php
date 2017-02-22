<?php
if (isset($script->js_url)) {
    $item = "script";
} elseif (isset($skin->skin_url)) {
    $item = "skin";
    $script = $skin;
}

?> 
<div id="comments" class="{{$commentClass}}">

    <h2>Commentaires</h2>
    <form id="add_form" class="form-horizontal" role="form" method="POST" action="{{ route($item.'.comment', $script->slug) }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">

            <div class="col-md-12 ">
                <textarea maxlength="254" id="comment" required placeholder="Votre commentaire" class="form-control" name="comment" >{{ old('comment') }}</textarea>

                @if ($errors->has('comment'))
                <span class="help-block">
                    <strong>{{ $errors->first('comment') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('recaptcha') ? ' has-error' : '' }}">
            <div class="col-md-6 "> 
                <div class="g-recaptcha" data-sitekey="6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS"></div>

                @if ($errors->has('recaptcha'))
                <span class="help-block">
                    <strong>{{ $errors->first('recaptcha') }}</strong>
                </span>
                @endif 
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 ">
                <button type="submit" class="btn btn-sm btn-primary">
                    Commenter
                </button>
            </div>
        </div>

    </form>

    <?php
//    $comments = $script->comments()->orderBy('created_at', 'desc')->get();
    ?> 
    @if(!count($comments))

    <div class="panel panel-default">      
        <div class="panel-body text-center">
            Pas encore de commentaire pour ce {{$item}}.
        </div>
    </div>
    @else

    <style>
        .date{
            font-size: 13px;
        }
    </style>
    @foreach($comments as $comment)
    <div class="row">
        <div class="col-md-12">


            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: left;">
                    <b>{{$comment->user()->first()->name}}</b> 
                    <span class="date pull-right">
                        {{$comment->created_at->format('d/m/Y à H:i')}}
                    </span> 

                </div>
                <div class="panel-body">
                    {{$comment->comment}}
                </div>
            </div>
        </div>

    </div>

    @endforeach
    
    {{ $comments->fragment('comments')->links() }}




    @endif


</div>