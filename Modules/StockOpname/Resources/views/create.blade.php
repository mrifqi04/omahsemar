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
                                <div class="col-8"></div>
                                <div class="col-2">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Bulan Stock Opname <span class="text-danger">*</span></label>
                                            <select class="form-control" name="so_month" id="so_month">
                                                    <option selected value="">-- Pilih Bulan --</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Tahun Stock Opname <span class="text-danger">*</span></label>
                                            <select class="form-control" name="so_year" id="so_date">
                                                <option selected value="">-- Pilih Tahun --</option>
                                                @for ($y = now()->year; $y <= now()->year + 3; $y++)
                                                    <option value="{{ $y }}">{{ $y }}</option>
                                                @endfor
                                            </select>
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
