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

        <p>
			Envoyez nous un email à : <br>  contact @ plkproduction.com 
		</p>


</div>
    </div>
</div>




@endsection
