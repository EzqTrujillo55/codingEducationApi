<?php

namespace App\Http\Controllers;

use App\Models\Familyparent;
use App\Http\Requests\StoreFamilyparentRequest;
use App\Http\Requests\UpdateFamilyparentRequest;

class FamilyparentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFamilyparentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFamilyparentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Familyparent  $familyparent
     * @return \Illuminate\Http\Response
     */
    public function show(Familyparent $familyparent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Familyparent  $familyparent
     * @return \Illuminate\Http\Response
     */
    public function edit(Familyparent $familyparent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFamilyparentRequest  $request
     * @param  \App\Models\Familyparent  $familyparent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFamilyparentRequest $request, Familyparent $familyparent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Familyparent  $familyparent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Familyparent $familyparent)
    {
        //
    }
}
