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
                @if (Auth::guest())
                <div style="display:block ; display:inline-block" data-toggle="tooltip" data-placement="right" title="Connectez-vous pour commenter">
                    <button type="submit" disabled="true"class="btn btn-sm btn-primary disabled" style="pointer-events: none;">
                        Commenter
                    </button></div>
                @else
                <button type="submit" class="btn btn-sm btn-primary">
                    Commenter
                </button>
                @endif

            </div>
        </div>

    </form>

    <?php
//    $comments = $script->comments()->orderBy('created_at', 'desc')->get();
    ?>
    @if(!count($comments))

    <div class="panel comments">
        <div class="panel-body text-center comments">
            Aucun commentaire.
        </div>
    </div>
    @else

    @foreach($comments as $comment)
    <div class="row">
        <div class="col-md-12">
            <div class="panel comments">
                <div class="panel-heading comments" style="text-align: left;">
                    <b>{{$comment->user()->first()->name}}</b>

                    <span class="date pull-right">
                        {{$comment->created_at->format('d/m/Y à H:i')}}
                    </span>

                </div>
                <div class="panel-body comments">
                    {{$comment->comment}}

                    @if (Auth::check() && ( Auth::user()->isAdmin() ||  Auth::user()->id == $comment->user_id ))
                     <span class="pull-right ">
                         <br>
                         <a data-toggle="confirmation" data-btn-ok-label="Oui" data-btn-cancel-label="Non" title="Supprimer le commentaire ?" href="{{ route($item.'.comment.delete', ['slug' => $script->slug, 'comment_id' => $comment->id ])}}"><i class="fa fa-trash fa-2x text-danger"></i></a>
                    </span>
                    @endif

                </div>
            </div>
        </div>

    </div>

    @endforeach

    {{ $comments->fragment('comments')->links() }}




    @endif


</div>
