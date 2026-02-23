<?php

namespace App\Http\Controllers;

use App\Models\OutgoingGood;
use Illuminate\Http\Request;

class OutgoingGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('outgoinggood.index');
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
    public function show(OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingGood $outgoingGood)
    {
        //
    }
}
