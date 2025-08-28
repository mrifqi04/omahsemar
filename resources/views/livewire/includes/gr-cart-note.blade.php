<div class="input-group d-flex justify-content-center">
    <input style="min-width: 70px;max-width: 120px;" type="text" class="form-control"
        wire:model.defer="notes.{{ $cart_item->id }}" />
    <div class="input-group-append">
        <button type="button" wire:click="updateQuantityGr('{{ $cart_item->rowId }}', {{ $cart_item->id }})"
            class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div>
</div>
