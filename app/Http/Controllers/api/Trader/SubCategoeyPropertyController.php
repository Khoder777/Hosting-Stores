<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use Illuminate\Http\Request;
use App\Models\SubCategoeyProperty;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class SubCategoeyPropertyController extends Controller
{

    public function index()
    {
        try {
            $subCategoeyProperties = SubCategoeyProperty::all();
            return $this->successResponse($subCategoeyProperties, 'all sub categories properties recived successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'sub_category_id' => 'required|not_in:0|exists:sub_categories,id'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            $subCategory = SubCategoeyProperty::create([
                'name' => $request->name,
                'sub_category_id' => $request->sub_category_id
            ]);
            if ($subCategory) {
                return $this->successResponse($subCategory, 'Sub_Categoey_Property careateded successfully.');
            } else {
                return $this->errorResponse('Sub_Categoey_Property dose not created some thing went wrong try again !', 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $subCategoeyProperty = SubCategoeyProperty::findOrFail($id);
            if ($subCategoeyProperty) {
                return $this->successResponse($subCategoeyProperty, 'your  sub_Categoey_Property :');
            } else
                return $this->successResponse(NULL, 'your  sub_Categoey_Property is not found :');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'sub_category_id' => 'required|not_in:0|exists:sub_categories,id'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            $subCategoeyProperty = SubCategoeyProperty::findOrFail($id);
            $check = true;
            $keys = ['name', 'category_id'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $subCategoeyProperty->$property) {
                    $subCategoeyProperty->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
                if ($check) {
                    $SucssesMesssge['info'] = 'No thing to update';
                    return $this->successResponse($subCategoeyProperty,   $SucssesMesssge);
                } else {
                    $subCategoeyProperty->save();
                    return $this->successResponse($subCategoeyProperty, $SucssesMesssge);
                }
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $subCategoeyProperty = SubCategoeyProperty::find($id);
            if (!$subCategoeyProperty) {
                return $this->errorResponse('subCategoeyProperty not found', 404);
            }
            $subCategoeyProperty->delete();
            return $this->successResponse(NULL, 'subCategoeyProperty deleted Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function ProductAttribute()
    {
        try {
            $attributes = SubCategoeyProperty::get('name')->toArray();
            $collection = collect($attributes);
            $unique = $collection->unique();
            $unique = $unique->values()->all();
            return $this->successResponse($unique, 'all properties recived successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
}