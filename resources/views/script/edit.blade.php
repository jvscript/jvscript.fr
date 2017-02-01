@extends('layouts.app')

@section('content') 



<div class="row">
    <div class="col-md-12">

        <h1>Edition d'un script</h1>

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
 

        <form id="add_form" class="form-horizontal" role="form" method="POST" action="{{ route('script.update',$script->slug) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group{{ $errors->has('sensibility') ? ' has-error' : '' }}">
                <label for="sensibility" class="col-md-4 control-label">Sensibilité du script ?</label>

                <div class="col-md-6">
                    <select id="sensibility" class="form-control" name="sensibility" required>
                        <?php
                        $sensibilitys = [0, 1, 2];
                        $sensibilitys_label = ['Clean', 'Attention', 'Dangereux'];
                        ?>
                        @foreach ($sensibilitys as $sensibility)
                        <option {{$sensibility == $script->sensibility ? 'selected' : '' }} value="{{$sensibility}}">{{$sensibilitys_label[$sensibility]}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('sensibility'))
                    <span class="help-block">
                        <strong>{{ $errors->first('sensibility') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Nom du script *</label>

                <div class="col-md-6">
                    <input id="name" type="text" readonly="true" disabled="true" class="form-control disabled" name="name" value="{{ old('name',$script->name) }}" required autofocus>

                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('autor') ? ' has-error' : '' }}">
                <label for="autor" class="col-md-4 control-label">Auteur du script </label>

                <div class="col-md-6">
                    <input id="autor" type="text" class="form-control" name="autor" value="{{ old('autor',$script->autor) }}">

                    @if ($errors->has('autor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('autor') }}</strong>
                    </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="col-md-4 control-label">Description</label>

                <div class="col-md-6">
                    <textarea id="description"   class="form-control" name="description" >{{ old('description',$script->description) }}</textarea>

                    @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('js_url') ? ' has-error' : '' }}">
                <label for="js_url" class="col-md-4 control-label">Lien du script (.js) *</label>

                <div class="col-md-6">
                    <input id="js_url" type="text" placeholder="http://.../usercript.js" class="form-control" name="js_url" value="{{ old('js_url',$script->js_url) }}" required autofocus>

                    @if ($errors->has('js_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('js_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('repo_url') ? ' has-error' : '' }}">
                <label for="repo_url" class="col-md-4 control-label">Lien du repository </label>

                <div class="col-md-6">
                    <input id="repo_url" type="text" placeholder="http://github.com/..." class="form-control" name="repo_url" value="{{ old('repo_url',$script->repo_url) }}" >

                    @if ($errors->has('repo_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('repo_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('photo_url') ? ' has-error' : '' }}">
                <label for="photo_url" class="col-md-4 control-label">Lien vers le logo/image </label>

                <div class="col-md-6">
                    <input id="photo_url" type="text" placeholder="http://image.noelshack.com/..." class="form-control" name="photo_url" value="{{ old('photo_url',$script->photo_url) }}"  >

                    @if ($errors->has('photo_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('photo_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('don_url') ? ' has-error' : '' }}">
                <label for="don_url" class="col-md-4 control-label">Lien de don à l'auteur </label>

                <div class="col-md-6">
                    <input id="don_url" type="text" placeholder="http://www.paypal.me/your_name/" class="form-control" name="don_url" value="{{ old('don_url',$script->don_url) }}"  >

                    @if ($errors->has('don_url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('don_url') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('user_email') ? ' has-error' : '' }}">
                <label for="user_email" class="col-md-4 control-label">Votre email pour être notifié de la publication du script </label>

                <div class="col-md-6">
                    <input id="user_email" type="email" placeholder="email@domaine.fr" readonly="true" disabled="true" class="form-control disabled" name="user_email" value="{{ old('user_email',$script->user_email) }}"  >

                    @if ($errors->has('user_email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user_email') }}</strong>
                    </span>
                    @endif
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
