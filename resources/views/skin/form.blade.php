@extends('layouts.app')

@section('content')

@section('javascript')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


<script>
    $(function () {
        $('#is_autor').change(function () {
            var checked = $(this).prop('checked');
            if (checked) {
                $('#autor').val("{{Auth::user()->name}}");
                $('#autor').attr("readonly", "true");
                $('#autor').addClass("disabled");
            }
            else {
                $('#autor').removeAttr("readonly");
                $('#autor').removeClass("disabled");
                $('#autor').val("");
            }
        });
    });
</script>

@endsection



<div class="row">
    <div class="col-md-12">

        <h1 style="margin-bottom: 22px">Ajouter un skin</h1>
<div class="panel-body">
        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ route('skin.store') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Nom du skin *</label>

                <div class="col-md-6">
                    <input id="name" type="text" maxlength="50" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="col-md-4 control-label">Description *
                <br> <a href='https://guides.github.com/features/mastering-markdown/#syntax' target='_blank'>(MarkDown compatible)</a>
                </label>

                <div class="col-md-6">
                    <textarea id="description"   class="form-control" required name="description" >{{ old('description') }}</textarea>

                    @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('is_autor') ? ' has-error' : '' }}">
                <label for="is_autor" class="col-md-4 control-label"> Vous êtes l'auteur ?  </label>

                <div class="col-md-6">

                    <p>
                        <input name="is_autor" id="is_autor" type="checkbox" {{ old('is_autor') ? 'checked' : ''}}  data-toggle="toggle" data-on="Oui" data-off="Non">
                    </p>

                    @if ($errors->has('is_autor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_autor') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('autor') ? ' has-error' : '' }}">
                <label for="autor" class="col-md-4 control-label">Auteur du script </label>

                <div class="col-md-6">
                    <?php
                    $is_autor = $is_autor_class = $autor = '';
                    if (old('autor')) {
                        $autor = old('autor');
                    }
                    if (old('is_autor')) {
                        $is_autor_class = "disabled";
                        $is_autor = 'readonly="true"';
                    }
                    ?>
                    <input id="autor" type="text" maxlength="255" class="form-control {{$is_autor_class}}" name="autor" {{$is_autor}} value="{{ $autor }}">

                    @if ($errors->has('autor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('autor') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('skin_url') ? ' has-error' : '' }}">
                <label for="skin_url" class="col-md-4 control-label">Lien du skin  *</label>

                <div class="col-md-6">
                    <input id="skin_url" type="text" maxlength="255" placeholder="https://userstyles.org/styles/..." class="form-control" name="skin_url" value="{{ old('skin_url') }}" required autofocus>

                    @if ($errors->has('skin_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('skin_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('repo_url') ? ' has-error' : '' }}">
                <label for="repo_url" class="col-md-4 control-label">Lien du repository </label>

                <div class="col-md-6">
                    <input id="repo_url" type="text" maxlength="255" placeholder="http://github.com/..." class="form-control" name="repo_url" value="{{ old('repo_url') }}" >

                    @if ($errors->has('repo_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('repo_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('topic_url') ? ' has-error' : '' }}">
                <label for="topic_url" class="col-md-4 control-label">Lien du topic jvc </label>

                <div class="col-md-6">
                    <input id="topic_url" type="text" maxlength="255" placeholder="http://www.jeuxvideo.com/forums/..." class="form-control" name="topic_url" value="{{ old('topic_url') }}" >

                    @if ($errors->has('topic_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('topic_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('website_url') ? ' has-error' : '' }}">
                <label for="website_url" class="col-md-4 control-label">Lien du site web</label>

                <div class="col-md-6">
                    <input id="website_url" type="text" maxlength="255" placeholder="http://..." class="form-control" name="website_url" value="{{ old('website_url') }}" >

                    @if ($errors->has('website_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('website_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('photo_url') ? ' has-error' : '' }}">
                <label for="photo_url" class="col-md-4 control-label">URL du logo/image </label>

                <div class="col-md-6">
                    <input id="photo_url" type="text" maxlength="255"  placeholder="http://image.noelshack.com/..." class="form-control" name="photo_url" value="{{ old('photo_url') }}"  >

                    @if ($errors->has('photo_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('photo_file') ? ' has-error' : '' }}">
                <label for="photo_file" class="col-md-4 control-label">Ou le fichier du logo/image </label>

                <div class="col-md-6">
                    <input id="photo_file" type="file"   placeholder="Votre image" class="form-control" name="photo_file" value="{{ old('photo_file') }}"  >

                    @if ($errors->has('photo_file'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_file') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('don_url') ? ' has-error' : '' }}">
                <label for="don_url" class="col-md-4 control-label">Lien de don à l'auteur </label>

                <div class="col-md-6">
                    <input id="don_url" type="text" maxlength="255" placeholder="http://www.paypal.me/your_name/" class="form-control" name="don_url" value="{{ old('don_url') }}"  >

                    @if ($errors->has('don_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('don_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('recaptcha') ? ' has-error' : '' }}">
                <div class="col-md-6 col-md-offset-4">
                    @if (App::environment('local'))
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-theme="dark"></div>
                    @else
                    <div class="g-recaptcha" data-sitekey="6LdaMRMUAAAAAN08nMXHLEe_gULU6wRyGSyENHkS" data-theme="dark"></div>
                    @endif

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
                        Ajouter
                    </button>
                </div>
            </div>
        </form>
</div>

    </div>

</div>


@endsection
