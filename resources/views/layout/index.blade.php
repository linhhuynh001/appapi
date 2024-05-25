@extends('index')
@section('title')
    Admin dashboard
@endsection

@section('content')
<div class="row">
<div class="col-xl-3 col-lg-3 col-md-6 col-12">
        <div class="card mb-4">
            <a href="#">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semi-bold">User</span>
                        </div>
                        <div>
                            <span class="bg-light-primary icon-shape icon-xl rounded-3 text-dark-primary">
                                <span class="fe fe-users fs-3 text-dark"></span>
                            </span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ $users }}
                    </h2>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection