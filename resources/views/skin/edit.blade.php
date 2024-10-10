@extends('layouts.app')

@section('content') 

@section('javascript')
<script src="/js/bootstrap-typeahead.min.js" type="text/javascript"></script>

<script>

    $('input.typeahead').typeahead({
        ajax: '/ajax-users',
        onSelect: function (user_selected) {
            //on met le libellé de l'auteur
            $('#autor').val(user_selected.text);
            $('#user_id').val(user_selected.value);
            //lock input
            $('#autor').attr("readonly", "true");
            $('#autor').addClass("disabled");
        }
    });
    $('#user_name').keydown(function () {
        if (!this.value) {
            $('#user_id').val('');
            $('#autor').removeAttr("readonly");
            $('#autor').removeClass("disabled");
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

</script>

@endsection

<div class="row">
    <div class="col-md-12">

        <h1>Edition d'un skin</h1>

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ route('skin.update',$skin->slug) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Nom du skin *</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control disabled" name="name" value="{{ old('name', $skin->name) }}" required autofocus>

                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="col-md-4 control-label">Description
                    <br> <a href='https://guides.github.com/features/mastering-markdown/#syntax' target='_blank'>(MarkDown compatible)</a>
                </label>

                <div class="col-md-6">
                    <textarea id="description"   class="form-control" name="description" >{{ old('description',$skin->description) }}</textarea>

                    @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('skin_url') ? ' has-error' : '' }}">
                <label for="skin_url" class="col-md-4 control-label">Lien du skin  *</label>

                <div class="col-md-6">
                    <input id="skin_url" type="text" placeholder="https://userstyles.org/styles/..." class="form-control" name="skin_url" value="{{ old('skin_url',$skin->skin_url) }}" required autofocus>

                    @if ($errors->has('skin_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('skin_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('last_update') ? ' has-error' : '' }}">
                <label for="last_update" class="col-md-4 control-label">Date de dernière MAJ </label>

                <div class="col-md-6">
                    <input id="last_update" type="text" placeholder="ex : 25/10/2016" class="form-control" name="last_update" value="{{ old('last_update',$skin->last_update != null ? $skin->last_update->format('d/m/Y') : null) }}" >

                    @if ($errors->has('last_update'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_update') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('version') ? ' has-error' : '' }}">
                <label for="version" class="col-md-4 control-label">Version </label>

                <div class="col-md-6">
                    <input id="version" type="text" placeholder="" class="form-control" name="version" value="{{ old('version',$skin->version) }}" >

                    @if ($errors->has('version'))
                    <span class="help-block">
                        <strong>{{ $errors->first('version') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('repo_url') ? ' has-error' : '' }}">
                <label for="repo_url" class="col-md-4 control-label">Lien du repository </label>

                <div class="col-md-6">
                    <input id="repo_url" type="text" placeholder="http://github.com/..." class="form-control" name="repo_url" value="{{ old('repo_url',$skin->repo_url) }}" >

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
                    <input id="topic_url" type="text" maxlength="255" placeholder="http://www.jeuxvideo.com/forums/..." class="form-control" name="topic_url" value="{{ old('topic_url',$skin->topic_url) }}" >

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
                    <input id="website_url" type="text" maxlength="255" placeholder="http://..." class="form-control" name="website_url" value="{{ old('website_url',$skin->website_url) }}" >

                    @if ($errors->has('website_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('website_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            @if($skin->photo_url != null)
            <div class="form-group">
                <label for="photo_url" class="col-md-4 control-label">Photo actuelle du script </label>

                <div class="col-md-6">
                    <img class="img-thumbnail img-responsive" style="max-width: 260px; max-height: 260px;" src="{{($skin->photo_url)}}" alt="{{$skin->name}} logo" />
                </div>
            </div> 
            @endif

            <div class="form-group{{ $errors->has('photo_url') ? ' has-error' : '' }}">
                <label for="photo_url" class="col-md-4 control-label">Lien vers le logo/image </label>

                <div class="col-md-6">
                    <input id="photo_url" type="text" placeholder="http://image.noelshack.com/..." class="form-control" name="photo_url" value="{{ old('photo_url') }}"  >

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
                    <input id="don_url" type="text" placeholder="http://www.paypal.me/your_name/" class="form-control" name="don_url" value="{{ old('don_url',$skin->don_url) }}"  >

                    @if ($errors->has('don_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('don_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            @if ((Auth::check() && Auth::user()->isAdmin()))

            <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                <label for="user_id" class="col-md-4 control-label">Auteur du skin (Compte owner) </label>

                <div class="col-md-6">
                    <?php
                    $user_name = '';
                    $user_id = '';
                    if ($skin->user_id != null) {
                        $user_name = $skin->user()->first()->name;
                        $user_id = $skin->user()->first()->id;
                    }
                    ?> 
                    <input id="user_name" type="text" class="form-control typeahead" placeholder="Aucun owner" autocomplete="off" name="user_name" value="{{ old('user_name', $user_name ) }}">
                    <input id="user_id" type="hidden" class="form-control" autocomplete="off" name="user_id" value="{{ old('user_id', $user_id ) }}">

                    @if ($errors->has('user_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('autor') ? ' has-error' : '' }}">
                <label for="autor" class="col-md-4 control-label" >Auteur du skin (libellé) 
                    <span class="fa-stack fa-1x " data-toggle="tooltip"  title="" data-original-title="Si l'auteur n'a pas de compte">
                        <i class="fa fa-stack-2x "></i>
                        <i class="fa fa-question fa-stack-1x "></i>
                    </span>
                </label> 

                <div class="col-md-6">
                    @if($user_id != '' and $user_name != '')
                    <input id="autor" type="text" class="form-control disabled" readonly="true"  name="autor"  value="{{ old('autor',$skin->autor) }}">
                    @else
                    <input id="autor" type="text" class="form-control" name="autor"  value="{{ old('autor',$skin->autor) }}">
                    @endif

                    @if ($errors->has('autor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('autor') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            @endif

            <div class="form-group{{ $errors->has('poster_user') ? ' has-error' : '' }}">
                <label for="poster_user" class="col-md-4 control-label">Posté par </label>

                <div class="col-md-6">
                    <input id="poster_user" type="text" readonly="true" disabled="true" class="form-control disabled" name="poster_user" value="{{ $skin->poster_user()->first()->name }}">
                </div>
            </div> 

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Editer
                    </button>
                </div>
            </div>
        </form>


    </div>

</div>


@endsection
