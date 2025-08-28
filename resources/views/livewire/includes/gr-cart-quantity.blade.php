<div class="input-group d-flex justify-content-center">
    <input style="min-width: 40px;max-width: 90px;" type="number" class="form-control"
        value="{{ $cart_item->options->qty_gr }}" wire:model.defer="qty_gr.{{ $cart_item->id }}" min="1" />
    <div class="input-group-append">
        <button type="button" wire:click="updateQuantityGr('{{ $cart_item->rowId }}', {{ $cart_item->id }})"
            class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div>
</div>
