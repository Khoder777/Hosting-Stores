<?php

namespace App\Http\Controllers\api\Admin;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $customers=Customer::paginate();
            return $this->successResponse($customers,'all customers recived successfully.');
            }
            catch(Exception $e){
                return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )',500);
            }
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

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status'=>'required|boolean'
        ]);
        $customer = Customer::findOrFail($id);
        try {
            $customer->update([
                'status' => (bool) $request->status,
            ]);
            return $this->successResponse($customer, 'customer status changed successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }

    }
}
