<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Traits\GeneralTrait;
use Exception;
use App\Models\MarketOwner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MarketOwnerController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $marketowners = MarketOwner::paginate();
            return $this->successResponse($marketowners, 'all Market Owners recived successfully.');
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
            $vali = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|max:255',
                'market_id' => 'required|exists:markets,id'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            $MarketOwner = MarketOwner::create([
                'market_id' => $request->market_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->successResponse($MarketOwner, 'Market Owner careateded successfully.');
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
        try {
            $vali = Validator::make($request->all(), [
                'email' => 'required|email|unique:market_owners,email,'.$id,
                'password' => 'required|max:255',
                'market_id' => 'required|not_in:0'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }

            $MarketOwner = MarketOwner::find($id);
            $MarketOwner->update([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'market_id' => $request->market_id,
            ]);
            return $this->successResponse($MarketOwner, 'Market Owner updated Successfully');

        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السيرفر يا اخوان بالصلاة عالنبي)', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
