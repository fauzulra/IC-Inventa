<?php

namespace App\Http\Controllers;

use App\Models\ItemTransfer;
use Illuminate\Http\Request;

class ItemTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return view('itemTransfer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemTransfer $itemTransfer)
    {
        //
    }
}
