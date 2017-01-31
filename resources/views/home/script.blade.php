<div class="col-xs-6 col-sm-3 col-md-3" onclick="window.location ='{{route('script.show',['slug' => $script->slug ])}}';" style="cursor: pointer;">
    <div class="thumbnail">
        <div class="hover-caption"> 
        <h4>{{$script->name}}  </h4>
        @if($script->description != null)
        <p>{{str_limit($script->description,500)}}</p>
        @else
         @if($script->autor != null)
        <p>Proposé par {{$script->autor}}</p>
         @endif
        <p>
             Ajouté le :   {{$script->created_at->format('d/m/Y')}} 
        </p> 
        @endif
        </div>
        <a href="{{route('script.show',['slug' => $script->slug ])}}">
            <?php $src = $script->photo_url == null ? "/assets/images/script.png" : $script->photo_url ?>
            <div class="image "> <img src="{{$src}}" class="  " alt="{{$script->name}} logo" /></a> </div>
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
        <p class="text-right"> 
            <span class=" label label-primary">Script</span> 
        </p>
    </div>
</div>
</div> 