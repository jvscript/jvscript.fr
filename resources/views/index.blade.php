@extends('layouts.app')

@section('content') 


<div class="row">
    <div class="col-md-12">

        <!--<h1>Bienvenue sur jvscript.io</h1>-->

        <!--<img style="max-height: 230px" class="img-responsive  center-block" src="/assets/images/jvscript.png"/>-->

        <p class="text-center"> Un site pour regrouper les scripts JVC et rapprocher les développeurs.</p>

    </div>
</div>

<div class="row">

    @foreach( $scripts as $script ) 
    <div class="col-xs-6 col-sm-3 col-md-3">
        <div class="thumbnail">
            <a href="{{route('script.show',['slug' => $script->slug ])}}">
                <?php $src = $script->photo_url == null ? "/assets/images/jvscript-nb.png" : $script->photo_url ?>
                <img src="{{$src}}" class="img-thumbnail" alt="{{$script->name}} logo" /></a>
            <div class="caption">
                <h4>{{$script->name}}
                    @if($script->autor != null)
                    by {{$script->autor}}
                    @endif                                
                </h4>
                <p class="pull-left">
                    <?php $note = round($script->note * 2) / 2; ?>
                    @for ($i = 1; $i <= $note ; $i++)
                    <i class="fa fa-star" aria-hidden="true"></i>
                    @endfor

                    <?php $stop = $i; ?>                  

                    @for ($i ; $i <= 5 ; $i++)                    
                    @if($i == $stop && $note > ( $i -1 ) )
                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    @endif

                    @endfor 
                </p>
                <p class="text-right"><i class="fa fa-download" aria-hidden="true"></i> {{$script->install_count}} </p>
            </div>
        </div>
    </div> 
    @endforeach

</div>

<div class="row">

    @foreach( $skins as $skin ) 
    <div class="col-xs-6 col-sm-3 col-md-3">
        <div class="thumbnail">
            <a href="{{route('skin.show',['slug' => $skin->slug ])}}">
                <?php $src = $skin->photo_url == null ? "/assets/images/jvscript-nb.png" : $skin->photo_url ?>
                <img src="{{$src}}" class="img-thumbnail" alt="{{$skin->name}} logo" /></a>
            <div class="caption">
                <h4>{{$skin->name}}
                    @if($skin->autor != null)
                    by {{$skin->autor}}
                    @endif                                
                </h4>
                <p class="pull-left">
                    <?php $note = round($skin->note * 2) / 2; ?>
                    @for ($i = 1; $i <= $note ; $i++)
                    <i class="fa fa-star" aria-hidden="true"></i>
                    @endfor

                    <?php $stop = $i; ?>                  

                    @for ($i ; $i <= 5 ; $i++)                    
                    @if($i == $stop && $note > ( $i -1 ) )
                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    @endif

                    @endfor 
                </p>
                <p class="text-right"><i class="fa fa-download" aria-hidden="true"></i> {{$skin->install_count}} </p>
            </div>
        </div>
    </div> 
    @endforeach

</div>


@endsection
