<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\DeliveryNote\Entities\DeliveryNote;

class SearchDeliveryNote extends Component
{
    public $query;
    public $search_results;
    public $how_many;
    public $selectedDnID;

    public function mount()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function render()
    {
        return view('livewire.search-delivery-note');
    }

    public function updatedQuery()
    {
        $this->search_results = DeliveryNote::where('reference', 'like', '%' . $this->query . '%')
            ->whereDoesntHave('stockout')
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

    public function selectPurchase($dnID)
    {
        $deliveryNote = DeliveryNote::findOrFail($dnID);
        $this->selectedDnID= $dnID;
      
        $products = $deliveryNote->deliveryNoteDetails->map(function ($detail) {
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
        
        $this->dispatch('deliveryNoteSelected', [
            'id'   => $dnID,
            'reference'   => $deliveryNote->reference,
            'supplier_id' => $deliveryNote->supplier_id,
            'date'        => \Carbon\Carbon::parse($deliveryNote->date)->format('Y-m-d'),
        ]);

        $this->dispatch('deliveryNoteProductsSelected', [
            'purchase_id' => $dnID,
            'products' => $products,
        ])->to('so-cart');
    }
}
