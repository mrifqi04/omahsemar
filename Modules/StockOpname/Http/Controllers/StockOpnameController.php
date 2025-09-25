<?php

namespace Modules\StockOpname\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Adjustment\Entities\Adjustment;
use Modules\Stock\Entities\Stock;
use Modules\Adjustment\Entities\AdjustedProduct;
use Modules\Product\Entities\Product;
use Modules\StockOpname\Entities\StockOpname;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('stockopname::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('stockopname::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
   public function store(Request $request)
{
    $request->validate([
        'date'           => 'required|date',
        'note'           => 'required|string|max:1000',
        'product_ids'    => 'required|array',
        'quantities'     => 'required|array',
        'item_locations' => 'required|array',
        'types'          => 'required|array',
    ]);

    try {
        DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'date' => Carbon::now(),
                'note' => $request->note
            ]);

            foreach ($request->product_ids as $key => $id) {
                $product       = Product::findOrFail($id); // selalu ada karena valid id
                $quantity      = (int) $request->quantities[$key];
                $itemLocation  = $request->item_locations[$key];
                $type          = $request->types[$key];

                $soF = StockOpname::where('product_id', $id)->orderBy('id', 'desc')->first();
   
                StockOpname::create([
                    'date' => $request->date,
                    'product_id' => $id,
                    'beg_stock' => $soF ? $soF->stock : $product->product_quantity,
                    'end_stock' => $request->quantities[$key]
                ]);

                if ($type === 'add') {
                    $stock = Stock::firstOrCreate(
                        [
                            'product_id'      => $id,
                            'item_location_id'=> $itemLocation,
                        ],
                        [
                            'stock' => 0,
                            'stock_date' => $request->date
                        ]
                    );

                    $stock->update([
                        'stock' => $stock->stock + $quantity,
                        'stock_date' => now(),
                    ]);
                } elseif ($type === 'sub') {
                    $stock = Stock::where('product_id', $id)
                        ->where('item_location_id', $itemLocation)
                        ->first();

                    if (!$stock) {
                        throw new \Exception("Product {$product->product_name} not found on selected location!");
                    }

                    $stock->update([
                        'stock' => $stock->stock - $quantity,
                        'stock_date' => now(),
                    ]);
                }

                // Save adjusted product
                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id'    => $id,
                    'quantity'      => $quantity,
                    'type'          => $type,
                    'item_location' => $itemLocation
                ]);

                // Update product global stock
                if ($type === 'add') {
                    $product->increment('product_quantity', $quantity);
                } elseif ($type === 'sub') {
                    $product->decrement('product_quantity', $quantity);
                }
            }
        });
    } catch (\Exception $e) {
        toast($e->getMessage(), 'error');
        return redirect()->back();
    }

    toast('Stock opnames created!', 'success');
    return redirect()->route('stock-opnames.create');
}


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('stockopname::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('stockopname::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
