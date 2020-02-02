<?php

namespace App\Http\Controllers\Api\V1;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supplier::orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|max:15',
            'address' => 'required'
        ]);

        return Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($request->has('name')) {
            $request->validate([
                'name' => 'required|max:100'
            ]);
            $supplier->name = $request->name;
        }

        if ($request->has('email')) {
            $request->validate([
                'email' => 'required|email|unique:suppliers,email,' . $supplier->id
            ]);
            $supplier->email = $request->email;
        }

        if ($request->has('phone')) {
            $request->validate([
                'phone' => 'required|max:15'
            ]);
            $supplier->phone = $request->phone;
        }

        if ($request->has('address')) {
            $request->validate([
                'address' => 'required'
            ]);
            $supplier->address = $request->address;
        }
        $supplier->save();

        return $supplier;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::destroy($id);

        return response()->json([
            'message' => 'success delete supplier data'
        ], 200);
    }
}
