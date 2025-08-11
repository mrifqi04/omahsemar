<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Purchase\Entities\Purchase;

class SearchPurchase extends Component
{
    public $query;
    public $search_results;
    public $how_many;

    public function mount()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function render()
    {
        return view('livewire.search-purchase');
    }

    public function updatedQuery()
    {
        $this->search_results = Purchase::where('reference', 'like', '%' . $this->query . '%')
            ->whereDoesntHave('goodReceipt')
            ->take($this->how_many)->get();
    }

    public function loadMore()
    {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function selectPurchase($purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);

        $products = $purchase->purchaseDetails->map(function ($detail) {
            return [
                'product_id' => $detail->product_id,
                'product_name' => $detail->product_name,
                'product_code' => $detail->product_code,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'unit_price' => $detail->unit_price,
                'sub_total' => $detail->sub_total,
            ];
        })->toArray();

        $this->dispatch('purchaseSelected', [
            'id'   => $purchaseId,
            'reference'   => $purchase->reference,
            'supplier_id' => $purchase->supplier_id,
            'date'        => \Carbon\Carbon::parse($purchase->date)->format('Y-m-d'),
        ]);

        $this->dispatch('purchaseProductsSelected', $products)
            ->to('gr-cart');
    }
}
