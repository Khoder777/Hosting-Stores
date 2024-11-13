<?php

namespace App\Http\Controllers\api\Admin;

use Exception;
use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required',
                'image' => 'required|image|mimes:png,jpg|max:8000',
                'status' => 'nullable',
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {

                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $market = Market::create([
                'name' => $request->name,
                'image' => $name,
                'status' => $request->status ? true : false,
            ]);

            $filename->storeAs('public/Admin/Market', $name);

            return $this->successResponse($market, 'Market created Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */

    public function edit($id)
    {
        try {
            $market = Market::find($id);
            if ($market) {
                return $this->successResponse($market, 'your  market ');
            } else
                return $this->successResponse(NULL, 'your  market is not found ');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $vali = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:png,jpg|max:8000',
            'status' => 'nullable',
        ]);
        try {
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }
            $market = Market::find($id);
            $check = true;
            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/Admin/Market/') . $market->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $market->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/Admin/Market', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }

            $keys = ['name', 'status'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $market->$property) {
                    $market->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($market,   $SucssesMesssge);
            } else {
                $market->save();
                return $this->successResponse($market, $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $market = Market::find($id);
            if (!$market) {
                return $this->errorResponse('market not found', 402);
            }

            if ($market->MarketOwners->isEmpty()) {
                $imagePath = storage_path('app/public/Admin/Market/') . $market->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $market->delete();
                return $this->successResponse(NULL, 'market deleted Successfully');
            } else {
                return $this->errorResponse('market has owners please delete them and try again', 402);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }


    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);
        $market = Market::findOrFail($id);
        try {
            $market->update([
                'status' => (bool) $request->status,
            ]);
            return $this->successResponse($market, 'market status blocked successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }
}
