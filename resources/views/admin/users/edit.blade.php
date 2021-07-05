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
        <input name="id" type="hidden" value="{{$id}}"/>
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
                    <input type="text" class="form-control" value="{{$user->email}}" name="email" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" value="{{$user->phone}}" class="form-control" name="phone" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control" name="designation" id="designation">
                <option value="">Select</option>
                <option value="m" {{$user->designation == 'm'?'selected':''}}>Manager</option>
                <option value="s" {{$user->designation == 's'?'selected':''}}>Supervisor</option>
                <option value="ex" {{$user->designation == 'ex'?'selected':''}}>Executive</option>
                <option value="exa" {{$user->designation == 'exa'?'selected':''}}>Executive Assistants</option>
            </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Access Permission<span class="text-danger" style="user-select: auto;">*</span></label>
                    <select class="form-control" name="role" id="role" required>
                <option value="">Select</option>
                @if (!empty($roles))
                @foreach($roles as $role)
                <option value="{{$role->id}}" {{$role_id == $role->id ? 'selected':''}}>{{$role->title}}</option>
                @endforeach
                @endif
            </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch custom-switch-md mb-3" dir="ltr">
                        <input type="checkbox" name="status" class="custom-control-input" id="customSwitchsizemd" {{$user->status == 1 ? 'checked':''}}>
                        <label class="custom-control-label" for="customSwitchsizemd">Active</label>
                    </div>
                </div>
            </div>

        </div>
        <hr>
        <div class="text-right">
            <button type="submit" class="btn btn-primary w-md">Update Employee</button>
        </div>
    </form>

</div>
@endsection
