@extends('layouts.app')

@section('content') 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

<!--
<div class="row">
    <div class="col-md-12">

        <h1>Bienvenue sur jvscript.io</h1>

        <img style="max-height: 230px" class="img-responsive  center-block" src="/assets/images/jvscript.png"/>

        <p class="text-center"> Un site pour regrouper les scripts JVC et rapprocher les développeurs.</p>

    </div>
</div>
-->
@section('javascript')
<script src="/js/blazy.min.js"></script>
<script>
    var bLazy = new Blazy();

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
//        console.log("updated");
        list.matchingItems.forEach(function (element) {
            var id = element.elm.id;
//            $('#' + id).addClass('animated fadeIn');
        });

        if (list.matchingItems.length > 0) {
            $('.no-result').hide();
        } else {
            $('.no-result').show();
        }
//        bLazy.revalidate();
    });

    //filter
    scriptList.on('filterComplete', function (list) {
//        console.log("filterComplete");
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

    //sort
    scriptList.on('sortComplete', function (list) {
//        alert("sortComplete");
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
     * Bouton sort asc/desc icon
     */
    $(".sort").click(function () {
        $("button.sort > i").removeClass("fa-sort-asc");
        $("button.sort > i").removeClass("fa-sort-desc");
        $("button.sort").children("span").text(" ");
        $("button.sort > i").addClass("fa-sort");
        if ($(this).hasClass('asc')) {
            $(this).children("i").addClass("fa-sort-asc");
            if ($(this).attr('data-sort') == "date") {
                $(this).children("span").text("(ancien)")
            }
        }
        else {
            $(this).children("i").addClass("fa-sort-desc");
            if ($(this).attr('data-sort') == "date") {
                $(this).children("span").text("(récent)")
            }
        }
        return false;
    });




</script>
@endsection

<div class="row">

    @include('ajax.index')

</div>


@endsection
