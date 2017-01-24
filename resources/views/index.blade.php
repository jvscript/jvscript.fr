@extends('layouts.app')

@section('content') 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">


<div class="row">
    <div class="col-md-12">

        <!--<h1>Bienvenue sur jvscript.io</h1>-->

        <!--<img style="max-height: 230px" class="img-responsive  center-block" src="/assets/images/jvscript.png"/>-->

        <p class="text-center"> Un site pour regrouper les scripts JVC et rapprocher les développeurs.</p>

    </div>
</div>

<style>
    ul{
        margin-bottom: 0px;
        -webkit-padding-start: 0px;    
    }
    ul li{
        list-style: none;
        display: inherit;
    }
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
    .display_none{
        display:none; 
    }
    .list{
        margin-top:40px;
    }

</style>

@section('javascript')

<script>
    var options = {
        valueNames: ['date', 'note', 'install_count', 'name', 'autor', 'category']
    };
    var scriptList = new List('scripts', options);

    /**
     * Filter action
     */
    $('.filter').click(function () {
        var target = $(this).attr('target');
        //reset filter
        if (target == "reset") {
            scriptList.filter();
            return false;
        }
        //or filter category
        scriptList.filter(function (item) {
            if (item.values().category == target) {
                return true;
            } else {
                return false;
            }
        });
        return false;
    });

    /**
     * Update : fadeIn & No result message
     */
    scriptList.on('updated', function (list) {
        list.matchingItems.forEach(function (element) {
            var id = element.elm.id;
            $('#' + id).addClass('animated fadeIn');
        });

        if (list.matchingItems.length > 0) {
            $('.no-result').hide();
        } else {
            $('.no-result').show();
        }
    });


    /**
     * Bouton sort asc/desc
     */
    $(".sort").click(function () {
        $("button.sort > i").removeClass("fa-sort-asc");
        $("button.sort > i").removeClass("fa-sort-desc");
        $("button.asc > i").addClass("fa-sort");
        if ($(this).hasClass('asc')) {
            $(this).children("i").addClass("fa-sort-asc");
        }
        else {
            $(this).children("i").addClass("fa-sort-desc");
        }
        return false;
    });


//    var selected = '#scripts';
//        $("#search").keyup(function () {
//            $.ajax({
//                url: "{{route('index')}}",
//                type: "POST",
//                data: {'search': $(this).val()},
//                success: function (view) {
//                    if (view.length > 0) {
//                        $("#ajax-content").html(view);
//                        $('button[href="#' + selected + '"]').tab('show');
//                        $('button[href="#' + selected + '"]').click();
//                        console.log("show " + selected);
//                    }
//                    else {
//                        console.log("no view");
//                    }
//                }
//            });
//        });
//    });


</script>
@endsection

<div class="row">

    @include('ajax.index')

</div>


@endsection
