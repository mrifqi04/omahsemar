@extends('layouts.app')

@section('title', 'Create Purchase')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active">Good Receipt</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-purchase />
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('utils.alerts')
                        <form id="purchase-form" action="{{ route('gr.store') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="reference">Purchase Reference <span class="text-danger">*</span></label>
                                        <input id="reference_input" type="text" class="form-control" name="reference"
                                            required readonly value="PR">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="supplier_id">Purchase Supplier <span
                                                    class="text-danger">*</span></label>
                                            <select readonly class="form-control" name="supplier_id" id="supplier_id"
                                                required>
                                                @foreach (\Modules\People\Entities\Supplier::all() as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Purchase Date <span class="text-danger">*</span></label>
                                            <input readonly id="purchase_date" type="date" class="form-control"
                                                name="date" required value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="item_location">Item Stored Location <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="item_location" id="item_location" required>
                                            @foreach (\Modules\Adjustment\Entities\AdjustedProduct::select('item_location')->distinct()->get() as $itemLocation)
                                                <option value="{{ $itemLocation->item_location }}">
                                                    {{ $itemLocation->item_location }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Date Received <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 mb-4 text-danger font-sm">*Note : Make sure to click
                                <i class="bi bi-check text-info"></i> after
                                add quantity & notes
                                to calculate
                                sub
                                total and add note
                            </div>

                            <livewire:gr-cart :cartInstance="'gr'" />

                            <div class="form-group">
                                <label for="note">Note (If Needed)</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Create Good Receipt <i class="bi bi-check"></i>
                                </button>
                            </div>
                            <input type="hidden" name="purchase_id" id="purchase_id" value="">
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
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('purchaseSelected', (data) => {
                data = data[0];
                console.log('Purchase selected:', data);

                // Update reference
                $('#reference_input').val(data.reference);

                // Update supplier
                $('#supplier_id').val(data.supplier_id).trigger('change');

                // Update date
                $('#purchase_date').val(data.date);

                // Update purchase_id
                $('#purchase_id').val(data.id);
            });
        });
    </script>
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
