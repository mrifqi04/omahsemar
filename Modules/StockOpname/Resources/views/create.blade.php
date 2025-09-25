@extends('layouts.app')

@section('title', 'Create Purchase')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Stock Opname</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-product />
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('utils.alerts')
                        <form id="purchase-form" action="{{ route('stock-opnames.store') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-4"></div>
                                <div class="col-4"></div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 mb-4 text-danger font-sm">*Note : Make sure to click
                                <i class="bi bi-check text-info"></i> after
                                add quantity
                                to calculate
                                sub
                                total
                            </div>
                            <livewire:adjustment.product-table :cartInstance="'stock-opname-cart'" />

                            <div class="form-group">
                                <label for="note">Note (If Needed)<span class="text-danger">*</span></label>
                                <textarea name="note" id="note" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Create Stock Opname <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#paid_amount').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
                allowZero: true,
            });

            $('#getTotalAmount').click(function() {
                $('#paid_amount').maskMoney('mask', {{ Cart::instance('purchase')->total() }});
            });

            $('#purchase-form').submit(function() {
                var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                $('#paid_amount').val(paid_amount);
            });
        });
    </script>
@endpush
