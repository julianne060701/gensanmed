<?php

namespace App\Http\Controllers\Purchaser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        return view('purchaser.home');
    }

    public function index()
    {
        return view('purchaser.purchase.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchaser.purchase.create');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
