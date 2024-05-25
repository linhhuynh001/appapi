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
                <h1 class="mb-0 h2 fw-bold">All Playlist </h1>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.url') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void()">Playlist</a>
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
            <div class="d-flex justify-content-end pb-2">
                <a href="#" class="btn btn-dark btn-sm btn-click-modal" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to album"><i class="fas fa-plus"></i></a>
            </div>
            <div class="show-content" style="display:none">
                <form action="{{ route('get.playlist.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">User</label>
                            <select name="user_id" class="form-control">
                                @foreach($user as $users)
                                    <option value="{{ $users->id }}">{{ $users->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Type</label>
                            <select name="type  " class="form-control">
                                @foreach($category as $categorys)
                                    <option value="{{ $categorys->id }}">{{ $categorys->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
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
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>User</th>
                                            <th>Type</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($playlist as $key => $playlists)
                                            <tr class="odd">
                                                <td class="dtr-control">{{ $key + 1 }}</td>
                                                <td><?= $playlists->name ?></td>
                                                <td><?= $playlists->name ?></td>
                                                <td>
                                                    <?php
                                                        $users = \DB::table('users')->where('id',$playlists->user_id)->first();
                                                        print $users->email
                                                    ?>      
                                                </td>
                                                <td>
                                                    <?php
                                                        $category = \DB::table('category')->where('id',$playlists->type)->first();
                                                        print $category->name
                                                    ?>      
                                                </td>
                                                <td><?= date('d/m/Y',strtotime($playlists->created_at)) ?></td>
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
<script>
    $(document).ready(function(){
        $('body').on('click','.btn-click-modal', function(){
            $('.show-content').toggle();
        });
    });
</script>
@endsection