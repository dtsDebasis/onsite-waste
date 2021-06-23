
@extends('admin.layouts.layout')


@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card">
          <div class="card-body">
              <div class="d-flex flex-column flex-md-row justify-content-between">
                  <div class="input-group mw-30">
                      <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                      <button class="btn btn-primary" type="button" id="button-addon2">Search</button>
                  </div>
                  <a href="{{route('users.create')}}" type="submit" class="btn btn-primary w-md">Add Employee</a>
              </div>
              <div class="tab-content mt-3 text-muted">
                  <div class="tab-pane active" id="home1" role="tabpanel">
                      <div class="table-responsive">
                          <table class="table table-centered table-nowrap mb-0">
                              <thead class="thead-light">
                                  <tr>
                                      <th>
                                          <div class="custom-control custom-checkbox">
                                              <input type="checkbox" class="custom-control-input" id="customCheck1">
                                              <label class="custom-control-label" for="customCheck1"></label>
                                          </div>
                                      </th>
                                      <th>First Name</th>
                                      <th>Last Name</th>
                                      <th>Email</th>
                                      <th>Phone</th>
                                      <th>Designation</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @if(!empty($users))
                                @foreach($users as $user)
                                  <tr class="">
                                      <td>
                                          <div class="custom-control custom-checkbox">
                                              <input type="checkbox" class="custom-control-input" id="customCheck2">
                                              <label class="custom-control-label" for="customCheck2"></label>
                                          </div>
                                      </td>
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
                                          $designation = 'Superviser';
                                        }elseif($user->designation=='ex'){
                                          $designation = 'Executive';
                                        }elseif($user->designation=='exa'){
                                          $designation = 'Executive Assistants';
                                        }
                                        @endphp
                                        {{$designation}}
                                      </td>
                                      <td>
                                          <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                              <i class="far fa-eye"></i>
                                          </button>
                                          <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                              <i class="bx bx-edit-alt"></i>
                                          </button>
                                          <button type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light">
                                              <i class="bx bx-trash-alt"></i>
                                          </button>
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
              {{$users->links()}}
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

