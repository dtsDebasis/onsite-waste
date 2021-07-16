@extends('admin.layouts.layout')


@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if (can('Employee Search'))
                <form method="get" action="{{route('users.index')}}">

                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="input-group mw-30">
                            <input type="text" value="{{$search}}" name="name" class="form-control" placeholder="Search"
                                aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                        </div>
                        <a href="{{route('users.create')}}" type="submit" class="btn btn-primary w-md">Add Employee</a>
                    </div>
                </form>
                @endif

                @if (can('Employee List'))
                                    <div class="tab-content mt-3 text-muted">
                    <div class="tab-pane active" id="home1" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        {{-- <th>
                                          <div class="custom-control custom-checkbox">
                                              <input type="checkbox" class="custom-control-input" id="customCheck1">
                                              <label class="custom-control-label" for="customCheck1"></label>
                                          </div>
                                      </th> --}}
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($data))
                                    @foreach($data as $user)
                                    <tr class="">
                                        {{-- <td>
                                          <div class="custom-control custom-checkbox">
                                              <input type="checkbox" class="custom-control-input" id="customCheck2">
                                              <label class="custom-control-label" for="customCheck2"></label>
                                          </div>
                                      </td> --}}
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->last_name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td><a href="tel:(986)-457-8564">{{$user->phone}}</a></td>
                                        <td>
                                            @php
                                            $designation = '';
                                            if($user->designation=='m'){
                                            $designation = 'Manager';
                                            }elseif($user->designation=='s'){
                                            $designation = 'Supervisor';
                                            }elseif($user->designation=='ex'){
                                            $designation = 'Executive';
                                            }elseif($user->designation=='exa'){
                                            $designation = 'Executive Assistants';
                                            }
                                            @endphp
                                            {{$designation}}
                                        </td>
                                        <td>{{$user->status == 1 ? 'Active' : 'Inactive'}}</td>
                                        <td>
                                            {{-- <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary
                                            btn-sm btn-rounded waves-effect waves-light">
                                            <i class="far fa-eye"></i>
                                            </a> --}}
                                            @if (can('Employee Edit'))
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            @endif

                                            @if (can('Employee Delete'))
                                                <a data-toggle="tooltip" title=""
                                                data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                                                data-confirm-yes="event.preventDefault();
                  document.getElementById('delete-form-{{$user->id}}').submit();" data-original-title="Delete"
                                                class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                            @endif

                                            {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                            'users.destroy',
                                            $user->id
                                            ],
                                            'style' => 'display:inline',
                                            'id' => 'delete-form-' . $user->id
                                            ]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="">
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{$data->links()}}
                @endif

                <!-- <div class="row">
                  <div class="col-lg-12">
                      <ul class="pagination pagination-rounded justify-content-center mt-4">
                          <li class="page-item disabled">
                              <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                          </li>
                          <li class="page-item">
                              <a href="#" class="page-link">1</a>
                          </li>
                          <li class="page-item active">
                              <a href="#" class="page-link">2</a>
                          </li>
                          <li class="page-item">
                              <a href="#" class="page-link">3</a>
                          </li>
                          <li class="page-item">
                              <a href="#" class="page-link">4</a>
                          </li>
                          <li class="page-item">
                              <a href="#" class="page-link">5</a>
                          </li>
                          <li class="page-item">
                              <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                          </li>
                      </ul>
                  </div>
              </div> -->
                <!-- end table-responsive -->
            </div>
        </div>
    </div>
</div>


@endsection
