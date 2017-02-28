@extends('layouts.app')

@section('content')


@section('javascript')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
//    $(document).ready(function () {
//
//    });

    $('[data-toggle=confirmation]').confirmation();
</script>

@endsection

<div class="row">

    <div>
            <div class="table-responsive">

                <table id="example" class="table  table-condensed table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th> Action </th>
                            <th> Auteur </th>
                            <th> Commentaire </th>
                            <th> Date </th>
                            <th> Sujet </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)

                        <tr>
                            <td>
                                <a class="btn btn-sm btn-default" href="{{route('admin.comment.delete',$comment->id)}}" data-toggle="confirmation">Supprimer</a> </td>
                            <td> {{$comment->user()->first()->name}} </td>
                            <td style="max-width:500px;"> {{$comment->comment}}   </td>
                            <td> {{$comment->created_at}}   </td>
                            <td> {{ $comment->commentable()->first() ?  $comment->commentable()->first()->name : '* Script supprimé *' }}   </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $comments->links() }}
            </div>


    </div>

</div>


@endsection
