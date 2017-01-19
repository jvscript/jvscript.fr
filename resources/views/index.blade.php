@extends('layouts.app')

@section('content') 


<div class="row">
    <div class="col-md-12">

        <!--<h1>Bienvenue sur jvscript.io</h1>-->

        <!--<img style="max-height: 230px" class="img-responsive  center-block" src="/assets/images/jvscript.png"/>-->

        <p class="text-center"> Un site pour regrouper les scripts JVC et rapprocher les développeurs.</p>

    </div>
</div>

<style>
    .image{
        position:relative;
        overflow:hidden;
        padding-bottom:100%;
        height: 100%;
    }
    .image img{
        position:absolute;
        max-width: 100%;
        max-height: 100%;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
    }

    .caption > h4{
        white-space:nowrap;
        overflow: hidden;
    }

</style>

@section('javascript')
<script>
    var selected = '#scripts';
    jQuery(document).ready(function () {
        $('button[data-toggle="tab"]').on('click', function () {
            selected = $(this).attr('href');
            console.log(selected);
        });

        $("#search").keyup(function () {
            $.ajax({
                url: "{{route('index')}}",
                type: "POST",
                data: {'search': $(this).val()},
                success: function (view) {
                    if (view.length > 0) {
                        $("#ajax-content").html(view);
                        //_TODO good tab
                        $('button[href="#' + selected + '"]').tab('show');
                        $('button[href="#' + selected + '"]').click();
                        console.log("show " + selected);
                    }
                    else {
                        console.log("no view");
                    }
                }
            });
        });
    });

</script>
@endsection

<div class="row">

    <div class="btn-group " role="group" >
        <button type="button" href="#scripts" data-toggle="tab" class="btn btn-default">Scripts</button>
        <button type="button" href="#skins" data-toggle="tab" class="btn btn-default">Skins</button>
    </div>

    <div class="btn-group " role="group" >
        <div class="form-inline pull-left"> <input type="text" placeholder="Rechercher" class="pull-left form-control input-sm" name="search" id="search" /> </div>
    </div>
    <!--    <div class="btn-group pull-right" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Date
                    <span class="caret"></span>
                </button>
    
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Note
                    <span class="caret"></span>
                </button>
    
            </div>
    
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Installation
                    <span class="caret"></span>
                </button>
    
            </div>
        </div>-->

    @include('ajax.index')




</div>


@endsection
