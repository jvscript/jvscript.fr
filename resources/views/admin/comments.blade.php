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
                        @php
                            $subject = $comment->commentable()->first();

                            if ($subject && isset($subject->js_url)) {
                                $item = "script";
                            } elseif ($subject && isset($subject->skin_url)) {
                                $item = "skin";
                            }
                        @endphp
                        <tr>
                            <td><a class="btn btn-sm btn-default" href="{{route('admin.comment.delete',$comment->id)}}" data-toggle="confirmation">Supprimer</a> </td>
                            <td><a class='table-link' href="{{ url('/search/'.$comment->user()->first()->name) }}"  data-toggle="tooltip" data-placement="bottom" title="Voir le profil de {{$comment->user()->first()->name}}">{{$comment->user()->first()->name}}   </a></td>
                            <td style="max-width:500px;"> {{$comment->comment}}   </td>
                            <td> {{$comment->created_at->format('d/m/Y - H:i')}}   </td>
                            @if ($subject = $comment->commentable()->first())
                                <td><a class="btn btn-sm btn-default" href="{{route($item . '.show',['slug' => $subject->slug ])}}" data-toggle="tooltip" data-placement="bottom" title="Voir le {{ $item }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                            @else
                                <td>* Script supprimé *</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $comments->links() }}
            </div>


    </div>

</div>


@endsection
