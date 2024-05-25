@extends('index')
@section('title')
    Admin dashboard
@endsection

@section('content')
<div class="row ">
    <div class="col-lg-12 col-md-12 col-12">
        <!-- Page header -->
        <div class="border-bottom pb-3 d-lg-flex align-items-center justify-content-between">
            <div class="mb-2 mb-lg-0">
                <h1 class="mb-0 h2 fw-bold">All Accounts </h1>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.url') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void()">Accounts</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="py-3">
    <div class="row">
        <div class="col-md-12 col-12 mb-5">
            <div class="card mt-2">
                <div class="card-body pt-2">
                    <div id="dataTableBasic_wrapper" class="no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="reponsive">
                                    <table id="dataTableBasic" class="table dataTable no-footer dtr-inline"
                                    style="width: 100%;" aria-describedby="dataTableBasic_info">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $key => $users)
                                            <tr class="odd">
                                                <td class="dtr-control">{{ $key + 1 }}</td>
                                                <td class="sorting_1">
                                                    {{ $users->email }}</td>
                                                <td class="sorting_1">{{ $users->email }}</td>
                                                <td>
                                                    @if($users->role == 0)
                                                        Client
                                                    @else 
                                                        Admin
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection