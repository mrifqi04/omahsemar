<?php

namespace Modules\StockOut\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Setting;
use Modules\ItemLocation\Entities\ItemLocation;
use Illuminate\Support\Facades\DB;
use Modules\Stockout\Entities\Stockout;
use Modules\Stockout\Entities\StockoutDetail;
use Modules\People\Entities\Supplier;
use Modules\DeliveryNote\Entities\DeliveryNote;
use Modules\Stock\Entities\Stock;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('stockout::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['setting'] = Setting::first();
        $data['itemLocations'] = ItemLocation::all();

        Cart::instance('stock-out-cart')->destroy();

        return view('stockout::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
       DB::transaction(function () use ($request) {
            $grand_total = 0;
            $so = Stockout::create([
                'delivery_note_id' => $request->dn_id,
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'supplier_name' => Supplier::findOrFail($request->supplier_id)->supplier_name,
                'note' => $request->note,
                'total' => $grand_total
            ]);
            $dn = DeliveryNote::findOrFail($request->dn_id);
            $dn->status = 'Completed';
            $dn->save();

            foreach (Cart::instance('stock-out-cart')->content() as $cart_item) {
                $grand_total += $cart_item->options->sub_total;
              
                StockoutDetail::create([
                    'stockout_id' => $so->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'qty_out' => $cart_item->options->qty_out,
                    'qty_stockout' => $cart_item->options->qty_stockout,
                    'note' => $cart_item->options->note,
                    'total' => $cart_item->options->sub_total
                ]);

                Stock::updateOrCreate(
                    [
                        'product_id' => $cart_item->id,
                        'item_location_id' => $request->item_location
                    ],
                    [
                        'stock' => Stock::where('product_id', $cart_item->id)->where('item_location_id', $request->item_location)->sum('stock') - ((int) $cart_item->options->qty_out),
                        'stock_date' => Carbon::now(),
                    ]
                );
            }

            $so->total = $grand_total;
            $so->save();
            Cart::instance('stock-out-cart')->destroy();
        });

        toast('Stock Out Created!', 'success');

        return redirect()->route('stockouts.create');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('stockout::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('stockout::edit');
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
