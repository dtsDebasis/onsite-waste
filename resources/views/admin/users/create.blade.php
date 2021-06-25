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
    <form method="post" action="{{route('users.store')}}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>First Name<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="text" class="form-control" name="first_name" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Last Name<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="text" class="form-control" name="last_name" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email<span class="text-danger" style="user-select: auto;">*</span></label>
                    <input type="email" class="form-control" name="email" required/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Designation</label>
                    <select class="form-control" name="designation" id="designation">
                <option value="">Select</option>
                <option value="m" >Manager</option>
                <option value="s" >Supervisor</option>
                <option value="ex" >Executive</option>
                <option value="exa" >Executive Assistants</option>
            </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Access Permission</label>
                    <select class="form-control" name="role" id="role">
                <option value="">Select</option>
                @if (!empty($roles))
                @foreach($roles as $role)
                <option value="{{$role->id}}">{{$role->title}}</option>
                @endforeach
                @endif
            </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch custom-switch-md mb-3" dir="ltr">
                        <input type="checkbox" name="status" class="custom-control-input" id="customSwitchsizemd">
                        <label class="custom-control-label" for="customSwitchsizemd">Active</label>
                    </div>
                </div>
            </div>

        </div>
        <hr>
        <div class="text-right">
            <button type="submit" class="btn btn-primary w-md">Add Employee</button>
        </div>
    </form>

</div>
@endsection
