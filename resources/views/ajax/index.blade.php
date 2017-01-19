<!-- Tab panes -->
<div id="ajax-content" class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="scripts">

        <br>
        <br>
        <!--_TODO Attention à la taille des images --> 

        @if(count($scripts) == 0)
        Aucun script trouvé.
        @endif
        @foreach( $scripts as $script ) 
        <div class="col-xs-6 col-sm-3 col-md-3">
            <div class="thumbnail">
                <a href="{{route('script.show',['slug' => $script->slug ])}}">
                    <?php $src = $script->photo_url == null ? "/assets/images/jvscript-nb.png" : $script->photo_url ?>
                    <div class="image ">
                    <img src="{{$src}}" class="  " alt="{{$script->name}} logo" /></a>
            </div>
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
<div role="tabpanel" class="tab-pane" id="skins">

    <br>
    <br>
    @if(count($scripts) == 0)
    Aucun skins trouvé.
    @endif
    @foreach( $skins as $skin ) 
    <div class="col-xs-6 col-sm-3 col-md-3">
        <div class="thumbnail">
            <a href="{{route('skin.show',['slug' => $skin->slug ])}}">
                <?php $src = $skin->photo_url == null ? "/assets/images/jvscript-nb.png" : $skin->photo_url ?>
                <div class="image">
                <img src="{{$src}}" class=" " alt="{{$skin->name}} logo" /></a>
        </div>
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

</div>