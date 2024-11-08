<?php

namespace App\Http\Controllers\api\Admin;

use Exception;
use App\Models\Ship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ships = Ship::paginate();
            return $this->successResponse($ships, 'all Shipping recived successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $vali = $request->validate([
                'city' => 'required|string',
                'shipping' => 'required',
                'status' => 'nullable|boolean',
            ]);

            $ship = ship::create([
                'city' => $request->city,
                'shipping' => $request->shipping,
                'status' => $request->status ? true : false,
            ]);
            return $this->successResponse($ship, 'ship careateded successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
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
        $request->validate([
            'city' => 'required',
                'shipping' => 'required',
                'status' => 'nullable',
        ]);
        $ship = Ship::findOrFail($id);
        try {
                $ship->update([
                    'city' => $request->city,
                    'shipping' => $request->shipping,
                    'status' => $request->status ? true : false,
                ]);
                return response()->json([
                    'seccess' => true,
                    'message' => 'admin updated',
                    'admin' => $ship
                ]);
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        $ship=ship::find($id);
        if($ship)
        {
        $ship->delete();
        return $this->successResponse($ship, 'ship deleted successfully.');
        }else  return $this->errorResponse('حذف شي محذوف يا استاذ؟ مو حلوة منك',  401);
        }catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status'=>'required|boolean'
        ]);
        $ship = ship::findOrFail($id);
        try {
            $ship->update([
                'status' => (bool) $request->status,
            ]);
            return $this->successResponse($ship, 'market status blocked successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }

    }
}
