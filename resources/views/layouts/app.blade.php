<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title> @yield('title','jvscript.io - Le site regroupant les scripts JVC')</title>
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
                    <a class="navbar-brand" rel="home" href="/">
                        <img style="max-width:140px; margin-top: -7px;"
                             src="/assets/images/banniere2.png">
                    </a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <li>
                            <div class="btn-group " role="group" style="margin-top: 10px;" >
                                <div class="form-inline pull-left">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" id="search-navbar" class="search form-control input-sm" placeholder="Recherche" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">                        
                        <li>
                            <a href="{{route('aide')}}">Aide</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ajouter <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li> <a href="{{route('script.form')}}">Ajouter un script <i class="fa fa-code text-right" aria-hidden="true"></i></a></li>
                                <li><a href="{{route('skin.form')}}">Ajouter un skin <i class="fa fa-paint-brush text-right" aria-hidden="true"></i></a></li>
                            </ul>
                        </li>
                        @if (!Auth::guest())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bonjour {{Auth::user()->name}} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->isAdmin())
                                <li>
                                    <a href="{{ route('admin_index') }}">
                                        Admin                                         
                                    </a>
                                </li>
                                @endif
                                <li>

                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                        Déconnexion <i class="fa fa-sign-out text-right" aria-hidden="true"></i>
                                    </a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>

                        @else
                        <li><a href="{{ url('/login') }}">Connexion</a></li>
                        <li><a href="{{ url('/register') }}">Inscription</a></li>
                        @endif
                    </ul>



                </div>
            </div>
        </div>

        <div class="container">

            <div class="content">
                @yield('content')
            </div>

            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-right">&COPY; {{ date('Y') }} jvscript.io  -
                            <a target="_blank" href="https://github.com/jvscript"><i class="fa fa-github fa-2x" aria-hidden="true"></i></a> -
                            <a href="{{url('contact')}}">Nous contacter</a> - 
                            <a target="_blank" href="https://github.com/jvscript/jvscript.github.io/blob/master/changelog.md">Changelog</a> -
                            <a href="{{url('developpeurs')}}">Développeurs</a>  
                        </p>
                    </div>
                </div>

            </footer>
        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/assets/javascripts/bootstrap.min.js"></script>
        <script src="/js/list.min.js"></script>
        <script src="/js/confirm.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        @yield('javascript')

        <script>
                                           $.ajaxSetup({
                                               headers: {
                                                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                               }
                                           });
                                           //sync up 2 search bar
                                           $("#search-navbar").keyup(function () {
                                               var keyword = $(this).val().trim();
                                               $("#search-page").val(keyword);
                                               if (typeof scriptList == 'undefined') {
                                                   //redirect home with keyword
                                                   window.location.href = "/search/" + keyword;

                                               }
                                               else {
                                                   scriptList.search(keyword);
                                               }
                                           });

//if we're not on the homepage
                                           @if (isset($keyword) && strlen($keyword) > 0)
                                                   var keyword = '{{$keyword}}';
                                           scriptList.search(keyword);
                                           $("#search-page").val(keyword);
                                           $("#search-navbar").val(keyword);
                                           $("#search-navbar").focus();

                                           @endif

        </script>
        <script>
                    (function (i, s, o, g, r, a, m) {
                        i['GoogleAnalyticsObject'] = r;
                        i[r] = i[r] || function () {
                            (i[r].q = i[r].q || []).push(arguments)
                        }, i[r].l = 1 * new Date();
                        a = s.createElement(o),
                                m = s.getElementsByTagName(o)[0];
                        a.async = 1;
                        a.src = g;
                        m.parentNode.insertBefore(a, m)
                    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-91627552-1', 'auto');
            ga('send', 'pageview');

        </script>
    </body>
</html>
