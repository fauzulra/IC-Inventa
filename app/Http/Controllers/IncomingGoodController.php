<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use Illuminate\Http\Request;

class IncomingGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('incominggood.index');
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
    public function show(IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingGood $incomingGood)
    {
        //
    }
}
