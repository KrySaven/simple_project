@extends('layouts.app')
@section('title','Change Password')
@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Change Password</h2>

                    </div>
                    <div class="body">
                        <form id="form_validation" method="POST"
                              action="{{route('user.changepassword',['id'=>Auth::user()->id])}}">
                            @csrf
                            @include('admin.includes.error')
                            <div class="form-group form-float​​">
                                <div class="form-line">
                                    <input type="password" class="form-control" name="password" id="password">
                                    <label class="form-label">New password</label>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group form-float ">
                                <div class="form-line">
                                    <input type="password" class="form-control" name="password_confirm"
                                           id="password_confirm">
                                    <label class="form-label">Confirm password</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success waves-effect" ​​>
                                <i class="material-icons">save</i>
                                <span>Change password</span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
