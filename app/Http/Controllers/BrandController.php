<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('crud-permission');
        $data = Brand::all();
        return view('brand.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('crud-permission');

        $data = new Brand();

        $data->name = $request->get('name');
        $data->save();
        return redirect()->route('brand.index')->with('status', 'Brand is added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $this->authorize('crud-permission');

        $data = $brand;
        return view("brand.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $this->authorize('crud-permission', $brand);

        $brand->name = $request->get('name');
        $brand->save();
        return redirect()->route('brand.index')->with('status', 'Data brand succesfully changed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('crud-permission', $brand);

        try {
            $brand->delete();
            return redirect()->route('brand.index')->with('status', 'Data brand succesfully deleted');
        } catch (\PDOException $e) {
            $msg =  $this->handleAllRemoveChild($brand);
            return redirect()->route('brand.index')->with('error', $msg);
        }
    }

    public function showEditModal(Request $request)
    {
        $this->authorize('crud-permission');

        $id = $request->get('brandId');
        $data = Brand::find($id);
        return response()->json(array(
            'msg' => view('brand.modal', compact('data'))->render()
        ), 200);
    }
}
