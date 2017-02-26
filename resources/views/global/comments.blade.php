<?php
if (isset($script->js_url)) {
    $item = "script";
} elseif (isset($skin->skin_url)) {
    $item = "skin";
    $script = $skin;
}
?>

<div id="comments" class="{{$commentClass}} ">
    <div class="panel-body">
        <h4 style="margin-top: 0px; margin-bottom: -10px">Commentaires</h4>
        <hr>
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
            @if($show_captcha)
            <div class="form-group{{ $errors->has('recaptcha') ? ' has-error' : '' }}">
                <div class="col-md-6 ">
                    <!--<div class="g-recaptcha" data-sitekey="6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS"></div>-->
                    <div id="recaptcha-{{$recaptcha}}"></div> 

                    @if ($errors->has('recaptcha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('recaptcha') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            @endif
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
        <hr>

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

                        @if($comment->user_id == $script->user_id)
                        <i class="fa fa-check" aria-hidden="true" style="padding-right: 5px" data-toggle="tooltip" data-placement="right" title="Créateur du {{$item}}"></i>
                        @endif

                        <span class="date pull-right">
                            {{$comment->created_at->format('d/m/Y à H:i')}} 
                        </span>

                    </div>
                    <div class="panel-body comments" style="word-wrap: break-word;">
                        {{$comment->comment}}  
                        <span class="pull-right ">                             
                            @if (Auth::check() && ( Auth::user()->isAdmin() ||  Auth::user()->id == $comment->user_id ))
                            <a name="delete-comment" title="Supprimer le commentaire ?" href="{{ route($item.'.comment.delete', ['slug' => $script->slug, 'comment_id' => $comment->id ])}}"><i class="fa fa-times text-danger" style="font-size: 15px; padding-left: 5px"></i></a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

        </div>

        @endforeach

        {{ $comments->fragment('comments')->links() }}




        @endif


    </div>
</div>

@section('recaptcha')
@if($show_captcha)
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<script type="text/javascript">
var CaptchaCallback = function () {
    grecaptcha.render('recaptcha-1', {'sitekey': '6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS'});
    grecaptcha.render('recaptcha-2', {'sitekey': '6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS'});
};</script>
@endif
@endsection