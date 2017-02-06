<div class="col-xs-6 col-sm-3 col-md-3">
    <div class="thumbnail">
        <a href="{{route('skin.show',['slug' => $skin->slug ])}}" class="">
            <div class="hover-caption">
                <p class="pull-left">
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

                @if(null != $script->user_id)                
                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$skin->user()->first()->name}} </p>
                @elseif($script->autor != null)                
                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$skin->autor}}</p>
                @endif


                <p class="text-right">
                    <span class=" label label-skin">Skin</span>
                </p>
                @if($skin->description != null)
                <p class="desc">{{str_limit($skin->description,350)}}</p>
                @else
                @if($skin->autor != null)
                <p>Proposé par {{$skin->autor}}</p>
                @endif
                <p>
                    Ajouté le :   {{$skin->created_at->format('d/m/Y')}}
                </p>
                @endif
            </div>

            <?php $src = $skin->photo_url == null ? "/assets/images/skin.jpg" : $skin->photo_url ?>
            <div class="image">
                <img src="{{$src}}" class=" " alt="{{$skin->name}} logo" />
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
        </a>
    </div>
</div>
