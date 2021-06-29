@extends('admin.layouts.layout', ['title' => $module, 'pageHeading'=> $module])

@section('content')
<div class="card-body card mb-4">
@if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <form method="post" action="{{route('user_update')}}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>First Name<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Last Name<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="text" class="form-control" value="{{$user->email}}" name="email" required readonly/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" value="{{$user->phone}}" class="form-control" name="phone" />
                </div>
            </div>
        </div>
        
        <h4>Update Password</h4>
        <hr>
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Password<span class="text-danger" style="user-select: auto;">*</span></label>
                <input type="password" class="form-control" value="" name="password"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" value="" class="form-control" name="phone" />
            </div>
        </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary w-md">Update Info</button>
        </div>
    </form>

</div>
@endsection
