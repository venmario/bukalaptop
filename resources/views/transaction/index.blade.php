@extends('layout.sbadmin')
@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif
<div class="container-fluid">
    <ol class="breadcrumb mt-4">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">History</li>
    </ol>
    <table class="table table-bordered mt-5" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Status</th>
                <th>Total</th>

                @if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Pegawai')
                <th>User</th>
                <th>Detail</th>
                <th>Action</th>
                @else
                <th>Detail</th>
                @endif

            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($data as $d)
            <tr>
                <td>{{$i}}</td>
                <td>{{$d->status}}</td>
                <td>{{number_format($d->total)}}</td>
                @if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Pegawai')
                <td>{{$d->user->fullname}}</td>
                <td><button class="btn btn-info" onclick="showDetail({{$d->id}})" data-toggle="modal"
                        data-target="#modalDetail">Lihat Detail</button></td>
                <td>
                    @if ($d->status == 'ditolak')
                    <button type="button" class="btn btn-danger" disabled>TERTOLAK</button>
                    @else
                    <form method="post" action="{{url('transaction/'.$d->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex">
                            <select class="form-control" name="status" id="status">
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="pending">Pending</option>
                            </select>
                            <button type="submit" class="btn btn-warning ml-2">Submit</button>
                        </div>
                    </form>
                    @endif

                </td>
                @else
                <td><button onclick="showDetail({{$d->id}})" data-toggle="modal" data-target="#modalDetail">Lihat
                        Detail</button></td>
                @endif
            </tr>
            <?php 
                    $i++
                ?>
            @endforeach
        </tbody>
    </table>

</div>

@endsection

@section('modal')
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetail" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetail">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="modalContent">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ajax')
<script>
    function showDetail(transactionId) {
        $.ajax({
            type: 'POST',
            url: '{{route("transaction.showDetail")}}',
            data: {
                '_token': '<?php echo csrf_token() ?>',
                'transactionId': transactionId,
            },
            success: function (data) {
                $("#modalContent").html(data.msg);
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }

</script>
@endsection
