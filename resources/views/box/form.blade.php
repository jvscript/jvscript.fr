@extends('layouts.app')

@section('content')

@section('javascript')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


<script>
    $(function () {

    });

</script>

@endsection


<div class="row">
    <div class="col-md-12">

        <h1 style="margin-bottom: 22px">Proposer une idée de script/skin</h1>
        <div class="panel-body">
            @if (session('message'))
            <script>
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
                    $("#success-alert").slideUp(500);
                });
            </script>
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif

            <form id="add_form" class="form-horizontal" role="form" method="POST"   action="{{ route('box.store') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    <label for="type" class="col-md-4 control-label">Idée de ?</label>

                    <div class="col-md-6">
                        <select id="type" class="form-control" name="type" required>
                            <?php
                            $types = [0, 1];
                            $types_label = ['Script', 'Skin'];
                            ?>
                            @foreach ($types as $type)
                            <option {{$type ==  old('type') ? 'selected' : '' }} value="{{$type}}">{{$types_label[$type]}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="col-md-4 control-label">Titre de l'idée *</label>

                    <div class="col-md-6">
                        <input id="title" type="text" maxlength="46" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                        @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="col-md-4 control-label">Description *
                        <br> <a href='https://guides.github.com/features/mastering-markdown/#syntax' target='_blank'>(MarkDown compatible)</a>
                    </label>

                    <div class="col-md-6">
                        <textarea id="description" required  class="form-control" name="description" >{{ old('description') }}</textarea>

                        @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
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
                            Ajouter l'idée
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>


@endsection
