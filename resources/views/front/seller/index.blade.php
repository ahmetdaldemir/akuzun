@extends('layouts.app')

@section('content')
    <div>
        <div class="d-none d-md-inline-flex justify-center align-items-center float-end"><a href="{{ route('seller.create') }}"
                                                                                  class="btn btn-color-scheme btn-sm fs-11 fw-400 mr-l-40 pd-lr-10 mr-l-0-rtl mr-r-40-rtl hidden-xs hidden-sm ripple">New
                Seller Add</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Work</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($seller as $itm)
                        <tr>
                            <th scope="row">{{ $itm->seller_name }}</th>
                            <td>
                                <a type="button" class="btn btn-outline-primary">Edit</a>
                                <a type="button" class="btn btn-outline-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
