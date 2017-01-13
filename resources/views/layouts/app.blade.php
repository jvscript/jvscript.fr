<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>jvscript.io</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="/assets/stylesheets/jvscript.css" media="screen">
        <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">

        <!-- Scripts -->
        <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
        </script>
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="../" class="navbar-brand">jvscript.io</a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav"> 
                        <li>
                            <a href="{{url('comment-installer')}}">Comment installer un script</a>
                        </li>                       
                        <li>
                            <a href="{{url('developpeurs')}}">Développeurs</a>
                        </li>
                        <li>
                            <a href="{{url('contact')}}">Contact</a>
                        </li>
                    </ul>
<!--                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Built With Bootstrap</a></li>
                    </ul>-->

                </div>
            </div>
        </div>

        <div class="container">

            @yield('content')

            <footer>
                <div class="row">
                    <div class="col-lg-12">

                        <p>&COPY; {{ date('Y') }} jvscript.io   <a target="_blank" href="https://github.com/jvscript"><i class="fa fa-github fa-2x" aria-hidden="true"></i></a> </p>
                    </div>
                </div>

            </footer>
        </div>

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="/assets/javascripts/bootstrap.min.js"></script> 
    </body>
</html>
