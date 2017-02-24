@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">



<style>


</style>

@section('javascript')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "order": [[5, "desc"]],
            "iDisplayLength": 50,
            "language": {
                "lengthMenu": "Afficher _MENU_ objets par page",
                "zeroRecords": "Aucun objet trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Rien à afficher",
                "infoFiltered": "(Filtré sur un total de _MAX_ lignes)",
                "search": "Rechercher : ",
            }
        });
    });

    $(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>

@endsection

<div class="row">

    <h3>Les scripts/skins dont vous êtes propriétaire</h3>
    <br>
    <br>
    <div>
      <div class="panel-body">
        <div class="table-responsive">

            <table id="example" class="table  table-condensed table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th> Action </th>
                        <th> Nom </th>
                        <th> Auteur </th>
                        <th> Note </th>
                        <th> Install </th>
                        <th> Status </th>
                        <th> Version </th>
                        <th> Last update </th>
                        <th> Date Ajout </th>
                        <th> Type </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scripts as $script)
                    <?php
                    if (isset($script->js_url)) {
                        $item = "script";
                    } elseif (isset($script->skin_url)) {
                        $item = "skin";
                    }
                    ?>
                    <tr class='clickable-row' data-href="{{route($item.'.show',['slug' => $script->slug ])}}">
                        <td>
                            <a class="btn btn-sm btn-default" href="http://jvscript.local/skin/darkjvc/edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <td> {{$script->name}} </td>
                        <td> {{$script->autor}}   </td>
                        <td> {{$script->note}}   </td>
                        <td> {{$script->install_count}}   </td>
                        <td> {{$script->statusLabel()}}   </td>
                        <td>
                            @if($script->version == null && $item == "script")
                            <strong class="text-danger">PAS DE VERSION</strong>
                            @else
                            {{$script->version}}
                            @endif
                        </td>
                        <td> {!!$script->last_update != null ? '<span class="hidden">'.$script->last_update.'</span>'. $script->last_update->format('d/m/Y') : '<strong class="text-danger">PAS DE DATE</strong> '!!}  </td>
                        <td> {{$script->created_at->format('d/m/Y - H:i')}}  </td>
                        <td> {{ucfirst($item)}} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>


    </div>

</div>


@endsection
