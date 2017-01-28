<!-- Tab panes -->
<div id="ajax-content" class="tab-content">


    <!--_TODO Attention à la taille des images --> 

    @if(count($scripts) == 0)
    Aucun script trouvé.
    @endif

    <div id="scripts">
        <div class="btn-group " role="group">
            <button type="button" target="reset" class="filter btn btn-default">Tous</button>
            <button type="button" target="script" class="filter btn btn-default">Scripts</button>
            <button type="button" target="skin" class="filter btn btn-default">Skins</button>
        </div>

        <div class="btn-group hidden" role="group" >
            <div class="form-inline pull-left">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" id="search-page" class="search form-control input-sm" placeholder="" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>


        <div class="btn-group pull-right" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button"   class="btn btn-default dropdown-toggle sort" data-sort="date" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Date <i class="fa fa-sort" aria-hidden="true"></i>
                </button>

            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle sort" data-sort="note" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Note <i class="fa fa-sort" aria-hidden="true"></i>
                </button>

            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle sort" data-sort="install_count" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Installation <i class="fa fa-sort" aria-hidden="true"></i>
                </button>

            </div>
        </div>


        <div class="list">

            @foreach( $scripts as $script ) 
            <?php
            if (isset($script->js_url)) {
                $item = "script";
            } elseif (isset($script->skin_url)) {
                $item = "skin";
                $skin = $script;
            }
            ?>
            <div id="{{$script->id}}-{{$item}}">
                <span class="date display_none">{{$script->created_at}}</span>
                <span class="note display_none">{{$script->note}}</span>
                <span class="name display_none">{{$script->name}}</span>
                <span class="autor display_none">{{$script->autor}}</span>
                <span class="install_count display_none">{{$script->install_count}}</span>


                <span class="category display_none">{{$item}}</span>
                @include('home.'.$item)


            </div>

            @endforeach
        </div>

        <span class="no-result display_none">
            Aucun résultat.
        </span>
    </div>



</div>