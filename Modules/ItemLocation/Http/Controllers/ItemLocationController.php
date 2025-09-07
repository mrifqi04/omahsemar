<?php

namespace Modules\ItemLocation\Http\Controllers;

use Modules\ItemLocation\DataTables\ItemLocationDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ItemLocation\Entities\ItemLocation;

class ItemLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ItemLocationDataTable $dataTable)
    {
        return $dataTable->render('itemlocation::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('itemlocation::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255|unique:item_locations,location_name',
        ]);

        ItemLocation::create([
            'location_name' => $request->location_name
        ]);

        toast('Currency Created!', 'success');

        return redirect()->route('item-locations.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('itemlocation::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ItemLocation $itemLocation)
    {
        return view('itemlocation::edit', compact('itemLocation'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, ItemLocation $itemLocation)
    {
        $request->validate([
            'location_name' => 'required|string|max:255|unique:item_locations,location_name',
        ]);

        $itemLocation->update([
            'location_name' => $request->location_name,
        ]);

        toast('Location Updated!', 'info');

        return redirect()->route('item-locations.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ItemLocation $itemLocation)
    {
        $itemLocation->delete();

        toast('Location Deleted!', 'warning');

        return redirect()->route('item-locations.index');
    }
}
