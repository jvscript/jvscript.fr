@extends('layouts.app')

@section('content') 

<div class="row">
    <div class="col-md-12">
<div class="panel-body">
        <h2>Nous contacter par email </h2>

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif



        <form class="form-horizontal" role="form" method="POST" action="{{ route('contact.send') }}">
            {{ csrf_field() }} 

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">Votre email </label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" autofocus value="{{ old('email') }}">

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('message_body') ? ' has-error' : '' }}">
                <label for="message_body" class="col-md-4 control-label">Message *</label>

                <div class="col-md-6">
                    <?php $message_body = isset($message_body) ? $message_body : old('message_body'); ?>
                    <textarea id="message_body"   class="form-control" name="message_body" required>{{ $message_body }}</textarea>

                    @if ($errors->has('message_body'))
                    <span class="help-block">
                        <strong>{{ $errors->first('message_body') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('recaptcha') ? ' has-error' : '' }}">
                <div class="col-md-6 col-md-offset-4">
                    <div class="g-recaptcha" data-sitekey="6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS"></div>

                    @if ($errors->has('recaptcha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('recaptcha') }}</strong>
                    </span>
                    @endif

                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Envoyer
                    </button>
                </div>
            </div>
        </form>

        <hr>

        <h2>Nous contacter par JVC </h2>

        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <a class='btn btn-primary' target='_blank' href='http://www.jeuxvideo.com/messages-prives/nouveau.php?all_dest=Toray;Cogis;ProblemePerso;Tartiflette54;DarkJVC'>Envoyer un MP par JVC</a> 
                    <p style="padding: 15px 0 8px 0;">
                       
                        Ou alors 
                    </p>
                    <a class='btn btn-primary' target='_blank' href='http://www.jeuxvideo.com/forums/42-1000021-50084630-1-0-1-0-site-jvscript-io.htm'>Via notre topic JVC</a> 
                </div>
            </div>
        </div>



</div>
    </div>
</div>




@endsection
