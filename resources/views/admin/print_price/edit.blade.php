@extends('admin.layouts.app')
@section('title','Print price')
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
                                        href="{{route('admin.print_price.index')}}">{{__('msg.print_price')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('msg.edit')}}
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
                                @if(admin()->check_route_permission('admin.print_price.edit') == 1)
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form">{{__('msg.print_price')}}</h4>
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
                                            @if($print_price)
                                                <form class="form"
                                                      action="{{route('admin.print_price.update',$print_price->id)}}"
                                                      method="post"
                                                      enctype="multipart/form-data" novalidate>
                                                    @csrf
                                                    <div class="form-body">
                                                        <h4 class="form-section"></h4>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="url">{{__('msg.num_paper')}} (1 {{__('msg.sar')}})</label>

                                                                    <input type="text" id="url"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.num_paper')}}"
                                                                           value="{{ $print_price->num_paper }}"
                                                                           name="num_paper">
                                                                    <span class="text-danger">في حال كان النوع تغليف يتم اخذ هذا الحقل عبارة عن سعر التغليف</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="from_paper">{{__('msg.from_paper')}}</label>

                                                                    <input type="number" id="from_paper"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.from_paper')}}"
                                                                           value="{{ $print_price->from_paper }}"
                                                                           name="from_paper">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-md-3 label-control"
                                                                           for="sort_id">{{__('msg.is_unlimeted')}}</label>
                                                                    <div class="col-md-9">
                                                                        <div class="float-left">
                                                                            <input type="checkbox" onchange="changeCheck()" name="is_unlimeted"
                                                                                   class="switchBootstrap is_unlimeted"
                                                                                   id="switchBootstrap2"
                                                                                {{$print_price->is_unlimeted == 1 ? 'checked' : ''}}
                                                                            />
                                                                        </div>
                                                                        @error('active')
                                                                        <span class="text-danger">{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="to_paper_div">
                                                                <div class="form-group">
                                                                    <label class=" label-control"
                                                                           for="to_paper">{{__('msg.to_paper')}}</label>
                                                                    <input type="number" id="to_paper"
                                                                           class="form-control border-primary"
                                                                           placeholder="{{__('msg.to_paper')}}"
                                                                           value="{{ $print_price->to_paper }}"
                                                                           name="to_paper">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="label-control">{{__('msg.paper_size')}}</label>
                                                                    <select class="select2 form-control" name="paper_size">
                                                                        @for($i = 3 ; $i <= 4 ;$i++)
                                                                            <option value="{{$i}}" {{$print_price->paper_size == $i ? 'selected' : ''}}>A {{$i}}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="label-control">{{__('msg.printing_type')}}</label>
                                                                    <select class="select2 form-control" id="printing_type" name="printing_type_id">
                                                                        <option value="1" {{$print_price->printing_type_id == 1 ? 'selected' : ''}}>{{__('msg.printing')}}</option>
                                                                        <option value="2" {{$print_price->printing_type_id == 2 ? 'selected' : ''}}>{{__('msg.packaging')}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 d-none packaging_type_div">
                                                                <div class="form-group">
                                                                    <label class="label-control">{{__('msg.packaging_type')}}</label>
                                                                    <select class="select2 form-control" name="packaging_type">
                                                                        <option value="0" {{$print_price->packaging_type == 0 ? 'selected' : ''}}>{{__('msg.select_packaging_type')}}</option>
                                                                        <option value="1" {{$print_price->packaging_type == 1 ? 'selected' : ''}}>{{__('msg.without_packaging')}}</option>
                                                                        <option value="2" {{$print_price->packaging_type == 2 ? 'selected' : ''}}>{{__('msg.stapling_packaging_with_a_tape')}}</option>
                                                                        <option value="3" {{$print_price->packaging_type == 3 ? 'selected' : ''}}>{{__('msg.wire')}}</option>
                                                                        <option value="3" {{$print_price->packaging_type == 4 ? 'selected' : ''}}>{{__('msg.one_perforated_paper')}}</option>
                                                                        <option value="3" {{$print_price->packaging_type == 5 ? 'selected' : ''}}>{{__('msg.plastic_snail_packaging')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-actions">
                                                            <a href="{{route('admin.print_price.index')}}" type="button"
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
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/vendors/css/forms/selects/select2.min.css')}}">
    <style>
        .select2-container--default {
            width: 100% !important;
        }
        .flatpickr{
            padding: 6px;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('public/assets/vendors/js/forms/select/select2.full.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('public/assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
    <script>
        function changeCheck(){

            if($('.is_unlimeted').is(':checked'))
                $('#to_paper_div').addClass('d-none')
            else
                $('#to_paper_div').removeClass('d-none')
        }
        $(document).ready(function (){
            changeCheck();
            $('#printing_type').change();
        })
        $(document).on('change','#printing_type',function (){
            var val = $('#printing_type :selected').val();
            if(val == 2)
                $('.packaging_type_div').removeClass('d-none');
            else
                $('.packaging_type_div').addClass('d-none');
        });
    </script>
@endsection
