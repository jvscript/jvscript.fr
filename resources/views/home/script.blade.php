<div class="col-xs-6 col-sm-3 col-md-3">
    <div class="thumbnail">
        <a href="{{route('script.show',['slug' => $script->slug ])}}" class="">

            <div class="hover-caption">
                <p class="pull-left">
                <h4>{{$script->name}}</h4>
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

                <?php
                if (null != $script->user_id)
                    $autor = $script->user()->first()->name;
                elseif ($script->autor != null)
                    $autor = $script->autor;

                $autor = str_limit($autor, 14);
                ?>

                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$autor}}</p>

                <p class="text-right">
                    <span class=" label label-script">Script</span>
                </p>

                <p class="desc">{{str_limit($script->description,350)}}</p>

            </div>

            <?php $src = $script->photo_url == null ? "/assets/images/script.jpg" : $script->photo_url ?>
            <div class="image "> <img src="{{$src}}" class="  " alt="{{$script->name}} logo" /> </div>
            <div class="caption">
                <h4>{{$script->name}}</h4>
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

                <p class="pull-left"><i class="fa fa-user" aria-hidden="true"></i> {{$autor}}</p>

                <p class="text-right">
                    <span class=" label label-script">Script</span>
                </p>
            </div>
        </a>
    </div>
</div>
