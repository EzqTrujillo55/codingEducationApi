<?php

namespace App\Http\Controllers;

use App\Models\SchoolHasStudent;
use App\Http\Requests\StoreSchoolHasStudentRequest;
use App\Http\Requests\UpdateSchoolHasStudentRequest;

class SchoolHasStudentController extends Controller
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
     * @param  \App\Http\Requests\StoreSchoolHasStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolHasStudentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolHasStudent  $schoolHasStudent
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolHasStudent $schoolHasStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchoolHasStudent  $schoolHasStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolHasStudent $schoolHasStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSchoolHasStudentRequest  $request
     * @param  \App\Models\SchoolHasStudent  $schoolHasStudent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSchoolHasStudentRequest $request, SchoolHasStudent $schoolHasStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolHasStudent  $schoolHasStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolHasStudent $schoolHasStudent)
    {
        //
    }
}
