@extends('admin.layouts.app')
@section('panel')
<div style="text-align:right">
    Select All <input type="checkbox" id="all">
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <form method="post" action="{{ route('admin.request.approved') }}" id="approve">
                        @csrf
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Event Name')</th>

                                    <th>@lang('Approve')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $products->firstItem() + $loop->index }}</td>
                                    <td data-label="@lang('Product Name')">{{ $product->name }}</td>

                                    <td data-label="@lang('Event Name')">{{ $product->event->name.' '.$product->event->sname }}</td>

                                    <td data-label="@lang('Approve')">
                                        <input type="checkbox" form="approve" name="products[]" value="{{$product->id}}">
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                </tr>
                                @endforelse
                                @if($products->count()>0)
                                <tr>

                                    <td></td>

                                    <td colspan="2">
                                        <input type="hidden" name="event_id" value="{{$ev_id}}">
                                        <input type="hidden" name="user_id" value="{{$us_id}}">
                                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                                    <td>
                                </tr>
                                @endif
                            </tbody>
                        </table><!-- table end -->

                    </form>
                </div>
            </div>
            <!-- @if ($products->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($products) }}
                </div>
            @endif -->
        </div>
    </div>
</div>


{{-- APPROVE MODAL --}}
<!--<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Approve Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.product.approve')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this event') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

@endsection

@push('breadcrumb-plugins')

<!-- <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Event')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.event.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
</div> -->

@endpush

@push('style')
<style>
    .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center
    }

    .header-search-wrapper {
        gap: 15px
    }


    @media (max-width:400px) {
        .header-search-form {
            width: 100%
        }
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        $('.approveBtn').on('click', function() {
            var modal = $('#approveModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

        $("#all").on('change',function() {
            if (this.checked) {
                var ele=document.getElementsByName('products[]');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=true;  
                }  
            }else{
                var ele=document.getElementsByName('products[]');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=false;  
                }  
            }
        });
    })(jQuery);
</script>
@endpush