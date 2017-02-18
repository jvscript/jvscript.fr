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
                <?php
                $autor = "";
                if (null != $skin->user_id)
                    $autor = $skin->user()->first()->name;
                elseif ($skin->autor != null)
                    $autor = $skin->autor;

                $autor = str_limit($autor, 14);
                ?>

                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$autor}}</p>

                <p class="text-right">
                    <span class=" label label-skin">Skin</span>
                </p>
                <p class="desc">{{str_limit($skin->description,350)}}</p>

            </div>

            <?php $src = $skin->photo_url == null ? "/assets/images/skin.jpg" : $skin->photo_url ?>
            <div class="image">
                {{-- @if($lazy)
                <img data-src="{{$src}}" class="b-lazy" alt="{{$skin->name}} logo" /> 
                @else
                <img src="{{$src}}" class="" alt="{{$skin->name}} logo" /> 
                @endif --}}
                <img src="{{$src}}" class="" alt="{{$skin->name}} logo" /> 
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

                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$autor}}</p>

                <p class="text-right">
                    <span class=" label label-skin">Skin</span>
                </p>
            </div>
        </a>
    </div>
</div>
