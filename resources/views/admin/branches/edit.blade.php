@extends('admin.layouts.app')
@section('title','Branches')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('dashboard.index')}}">{{__('msg.dashboard')}} </a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.branches.index')}}">{{__('msg.branches')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.edit_branch')}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                @if(admin()->check_route_permission('admin.branches.edit') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.branch')}}</h4>
                                        <a class="heading-elements-toggle"><i
                                                class="la la-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse show">
                                        <div class="card-body">
                                            @if($branch)
                                                <form class="form"
                                                      action="{{route('admin.branches.update',$branch->id)}}"
                                                      method="post"
                                                      enctype="multipart/form-data" novalidate>
                                                    @csrf
                                                    <div class="form-body">
                                                        <h4 class="form-section"></h4>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <div class="form-group row" style="    justify-content: center;">
                                                                    <div class="avatar-upload">
                                                                        <div class="avatar-edit">
                                                                            <input type="file" name="image" id="imageUpload"
                                                                                   accept=".png, .jpg, .jpeg"/>
                                                                            <label for="imageUpload"
                                                                                   class="imageUploadlabel"><i
                                                                                    class="la la-pencil edit_user"></i></label>
                                                                        </div>
                                                                        <div class="avatar-preview">
                                                                            <div id="imagePreview"
                                                                                 style="background-image: url({{$branch->image ? asset('public'.$branch->image) :asset('public/assets/images/plus-96.png')}});">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <ul class="nav nav-tabs nav-top-border no-hover-bg nav-justified">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" id="activeAr-tab1"
                                                                       data-toggle="tab" href="#activeAr"
                                                                       aria-controls="activeAr" aria-expanded="true"><i
                                                                            class="flag-icon flag-icon-sa"></i>العربية</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" id="activeEn-tab1"
                                                                       data-toggle="tab"
                                                                       href="#activeEn" aria-controls="activeEn"
                                                                       aria-expanded="false"><i
                                                                            class="flag-icon flag-icon-us"></i>EN </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content px-1 pt-1">
                                                                <div role="tabpanel" class="tab-pane active"
                                                                     id="activeAr"
                                                                     aria-labelledby="activeAr-tab1"
                                                                     aria-expanded="true">
                                                                    <div class="form-group row">
                                                                        <label class=" label-control"
                                                                               for="url">{{__('msg.name')}}</label>

                                                                        <input type="text" id="url"
                                                                               class="form-control border-primary"
                                                                               placeholder="{{__('msg.name')}}"
                                                                               value="{{ $branch->name_ar }}"
                                                                               name="name_ar">
                                                                        @error('name_ar')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class=" label-control"
                                                                               for="url">{{__('msg.address')}}</label>

                                                                        <input type="text" id="url"
                                                                               class="form-control border-primary"
                                                                               placeholder="{{__('msg.address')}}"
                                                                               value="{{ $branch->address_ar }}"
                                                                               name="address_ar">
                                                                        @error('address_ar')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="activeEn" role="tabpanel"
                                                                     aria-labelledby="activeEn-tab1"
                                                                     aria-expanded="false">
                                                                    <div class="form-group row">
                                                                        <label class="label-control"
                                                                               for="url">{{__('msg.name')}}</label>
                                                                        <input type="text" id="url"
                                                                               class="form-control border-primary"
                                                                               placeholder="{{__('msg.name')}}"
                                                                               value="{{ $branch->name_en }}"
                                                                               name="name_en">
                                                                        @error('name_en')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class=" label-control"
                                                                               for="url">{{__('msg.address')}}</label>

                                                                        <input type="text" id="url"
                                                                               class="form-control border-primary"
                                                                               placeholder="{{__('msg.address')}}"
                                                                               value="{{ $branch->address_en }}"
                                                                               name="address_en">
                                                                        @error('address_en')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 user_div">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.st_time')}}</label>

                                                                    <input type="text" class="form-control st_time"
                                                                           name="st_time" placeholder="H:i"
                                                                           value="{{ date('H:i',strtotime($branch->st_time)) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 user_div">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.end_time')}}</label>

                                                                    <input type="text" class="form-control end_time"
                                                                           name="end_time" placeholder="H:i"
                                                                           value="{{ date('H:i',strtotime($branch->end_time)) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-md-3 label-control"
                                                                           for="sort_id">{{__('msg.active')}}</label>
                                                                    <div class="col-md-9">
                                                                        <div class="float-left">
                                                                            <input type="checkbox" name="active"
                                                                                   class="switchBootstrap"
                                                                                   id="switchBootstrap2"
                                                                                {{$branch->active == 1 ? 'checked' : ''}}/>
                                                                        </div>
                                                                        @error('active')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-actions">
                                                            <a href="{{route('admin.branches.index')}}" type="button"
                                                               class="btn btn-warning mr-1 block-page"
                                                               onclick="history.back();">
                                                                <i class="ft-x"></i> {{__('msg.back')}}
                                                            </a>
                                                            <button type="submit" class="btn btn-primary block-page">
                                                                <i class="la la-check-square-o"></i> {{__('msg.save_close')}}
                                                            </button>
                                                            <button type="submit" name="save" value="1"
                                                                    class="btn btn-primary block-page">
                                                                <i class="la la-check-square-o"></i> {{__('msg.save')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="alert alert-danger">{{__('msg.not_found')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @include('admin.layouts.alerts.error_perm')
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('scripts')
    <script src="{{asset('public/assets/js/file_upload.js')}}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".st_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        flatpickr(".end_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    </script>
@endsection
