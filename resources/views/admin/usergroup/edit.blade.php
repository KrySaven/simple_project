@extends('layouts.app')
@section('title','Edit Group User')
@section('content')
        <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>
                    FORM VALIDATION
                    <small>Taken from <a href="https://jqueryvalidation.org/" target="_blank">jqueryvalidation.org</a></small>
                </h2>
            </div> -->
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Edit Group User</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" action="{{route('usergroup.update',['id'=> $usergroup->id])}}">  
                            @csrf
                            @include('admin.includes.error')
                            <br>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="group_name" value="{{$usergroup->group_name}}">
                                        <label class="form-label">Group Name</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="description" cols="30" rows="5" class="form-control no-resize">{{$usergroup->description}}</textarea>
                                        <label class="form-label">Other</label>
                                    </div>
                                </div>
                                <div class="icon-and-text-button-demo">
                                <button type="submit" class="btn btn-success waves-effect"​​>
                                    <i class="material-icons">save</i>
                                    <span>Save</span>
                                </button>
                                     
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    
@endsection