<div class="col-xs-6 col-sm-3 col-md-3"  onclick="window.location ='{{route('skin.show',['slug' => $skin->slug ])}}';" style="cursor: pointer;">
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
         <p class="text-right"> 
             <span class=" label label-warning">Skin</span> 
        </p>
    </div>
</div>
</div> 