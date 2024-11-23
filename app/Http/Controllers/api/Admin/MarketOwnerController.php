<?php

namespace App\Http\Controllers\api\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\MarketOwner;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Spatie\FlareClient\Time\Time;
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
                'email' => 'required|email|unique:market_owners,email',
                'password' => 'required|max:255',
                'market_id' => 'required|exists:markets,id'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            $MarketOwner = MarketOwner::create([
                'market_id' => $request->market_id,
                'email' => $request->email,
                'password' => $request->password,
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
                'email' => 'required|email|unique:market_owners,email,' . $id,
                'password' => 'required|max:255',
                'market_id' => 'required|exists:markets,id',
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }

            $marketOwner = MarketOwner::find($id);
            $check = true;
            $keys = ['email', 'password', 'market_id'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $marketOwner->$property) {
                    $marketOwner->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }

            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($marketOwner,   $SucssesMesssge);
            } else {
                $marketOwner->save();
                return $this->successResponse($marketOwner, $SucssesMesssge);
            }
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
    public function login(Request $request)
    {
        $vali = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($vali->fails()) {
            return $this->errorResponse($vali->errors(), 403);
        }
        $marketOwner = MarketOwner::where('email', $request->email)->first();
        if (!$marketOwner || !Hash::check($request->password, $marketOwner->password)) {
            return $this->errorResponse('The provided credentials are incorrect', 401);
        }
        $expiration = Carbon::now()->addMinutes(10);

        return response()->json([
            'marketOwner' => $marketOwner,
            'token' => $marketOwner->createToken('mobile', ['role:marketOwner'], $expiration)->plainTextToken
        ]);
    }
}