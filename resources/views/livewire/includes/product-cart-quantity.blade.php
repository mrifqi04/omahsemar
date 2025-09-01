<div class="input-group d-flex justify-content-center">
    <input type="number" class="form-control" min="1" value="{{ $cart_item->qty }}"
        wire:model.defer="quantity.{{ $cart_item->id }}" />

    <div class="input-group-append">
        <button type="button" wire:click="updateQuantity('{{ $cart_item->rowId }}', {{ $cart_item->id }})"
            class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div>
</div>
