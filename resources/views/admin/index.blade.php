@extends('layouts.app')

@section('content')

 

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
</script>

@endsection

<div class="row">

    <div>


              <p>
                <a href="{{route('admin.comments')}}">Gestion des commentaires</a>
                <br>
            </p>

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
                        <tr>
                            <td>
                                <a class="btn btn-sm btn-default" href="{{route($item.'.edit',['slug' => $script->slug ])}}" data-toggle="tooltip" data-placement="bottom" title="Editer le {{ucfirst($item)}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <td><a class='table-link' href="{{route($item.'.show',['slug' => $script->slug ])}}" data-toggle="tooltip" data-placement="bottom" title="Voir le {{ucfirst($item)}}"> {{$script->name}} </a></td>
                            <td> <a class='table-link' href="{{url('/search/'.$script->autor)}}"  data-toggle="tooltip" data-placement="bottom" title="Voir le profil de {{$script->autor}}">{{$script->autor}}   </a></td>
                            <td> {{$script->note}}   </td>
                            <td> {{$script->install_count}}  </td>
                            <td> {{$script->statusLabel()}}  </td>
                            <td>
                                @if($script->version == null && $item == "script")
                                <strong class="text-danger">PAS DE VERSION</strong>
                                @else
                                {{$script->version}}
                                @endif

                            </td>
                            <td> {!!$script->last_update != null ? '<span class="hidden">'.$script->last_update.'</span>'. $script->last_update->format('d/m/Y') : '<strong class="text-danger">PAS DE DATE</strong> '!!} </td>
                            <td> {{$script->created_at->format('d/m/Y - H:i')}}  </td>
                            <td> {{ucfirst($item)}} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



    </div>

</div>


@endsection
