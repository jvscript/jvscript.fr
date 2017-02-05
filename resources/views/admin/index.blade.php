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
</script>

@endsection

<div class="row">

    <div>

        <div class="table-responsive">
            <table id="example" class="table  table-condensed table-hover" cellspacing="0" width="100%"> 
                <thead>
                    <tr>
                        <th> Nom </th>
                        <th> Auteur </th>
                        <th> Posté par </th>
                        <th> Note </th>
                        <th> Install </th>
                        <th> Status </th>
                        <th> Date </th>
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
                    <tr onclick="window.location ='{{route($item.'.show',['slug' => $script->slug ])}}';" style="cursor: pointer;">
                        <td> {{$script->name}} </td>
                        <td> {{$script->autor}}   </td>
                        <td> {{$script->poster_user()->first() != null ? $script->poster_user()->first()->name : null}}   </td>
                        <td> {{$script->note}}   </td>
                        <td> {{$script->install_count}}   </td>
                        <td> {{$script->statusLabel()}}   </td>                        
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
