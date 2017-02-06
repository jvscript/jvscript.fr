<div class="col-xs-6 col-sm-3 col-md-3"  onclick="window.location ='{{route('skin.show',['slug' => $skin->slug ])}}';" style="cursor: pointer;">
    <div class="thumbnail">
      <div class="hover-caption">
        <p class="pull-left">
          <h4>{{$skin->name}}</h4>
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
        <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$script->autor}} </p>
        <p class="text-right">
            <span class=" label label-skin">Script</span>
        </p>
      @if($script->description != null)
      <p class="desc">{{str_limit($script->description,350)}}</p>
      @else
       @if($script->autor != null)
      <p>Proposé par {{$script->autor}}</p>
       @endif
      <p>
           Ajouté le :   {{$script->created_at->format('d/m/Y')}}
      </p>
      @endif
      </div>
        <a href="{{route('skin.show',['slug' => $skin->slug ])}}">
            <?php $src = $skin->photo_url == null ? "/assets/images/skin.jpg" : $skin->photo_url ?>
            <div class="image">
            <img src="{{$src}}" class=" " alt="{{$skin->name}} logo" /></a>
    </div>
    <div class="caption">
        <h4>{{$skin->name}}</h4>
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
        <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$skin->autor}} </p>
        <p class="text-right">
            <span class=" label label-skin">Skin</span>
        </p>
    </div>
</div>
</div>
