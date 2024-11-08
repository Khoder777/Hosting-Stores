<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Traits\GeneralTrait;
use Exception;
use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MarketController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $markets = Market::paginate();
            return $this->successResponse($markets, 'all markets recived successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
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
        try {
            $vali = $request->validate([
                'name' => 'required',
                'image' => 'required|image|mimes:png,jpg|max:8000',
                'status' => 'nullable',
            ]);
            if ($request->hasFile('image')) {

                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $market = Market::create([
                'name' => $request->name,
                'image' => $name,
                'status' => $request->status ? true : false,
            ]);

            $filename->storeAs('public/admin/market', $name);

            return $this->successResponse($market, 'Market created Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Market $market)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Market $market)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
        $vali = $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:png,jpg|max:8000',
            'status' => 'nullable',
        ]);
        if (!$vali) {
            // $this->apiValidation($request,$vali);
            // return $vali->errors();
            return 'error';
        }

        $market = Market::find($id);
        $market->update($request->only(['name','status']));
        if ($request->hasFile('image')) {
            File::delete(public_path('storage/market/') . $market->image);
            $filename = $request->image;

            $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
        }
        $market->update([
            'image' => $name,
        ]);
        $filename->storeAs('public/market', $name);

        return $this->successResponse($market, 'Market updated Successfully');

        }
        catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Market $market)
    {
        //
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status'=>'required|boolean'
        ]);
        $market = Market::findOrFail($id);
        try {
            $market->update([
                'status' => (bool) $request->status,
            ]);
            return $this->successResponse($market, 'market status blocked successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }

    }
}
