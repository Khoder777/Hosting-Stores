<?php

namespace App\Http\Controllers\api\trader;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\productSubCategoryValue;
use Illuminate\Support\Facades\Validator;
use App\Models\product_sub_category_values;
use App\Http\Resources\productSubCategoryValueResource;

class productSubCategoryValueController extends Controller
{

    public function index()
    {
        try {
            $productsubcategoryvalues = productSubCategoryValue::all();


            if ($productsubcategoryvalues) {
                return $this->successResponse(productSubCategoryValueResource::collection($productsubcategoryvalues), 'data recived successfully');
            } else {
                return $this->successResponse(Null, '  Empty data ');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $vali = Validator::make($request->all(), [
                'value' => 'required|string',
                'image' => ':png,jpg|max:8000',
                'product_id' => 'required|not_in:0|exists:categories,id',
                'sub_category_property_id' => 'required|not_in:0|exists:categories,id',
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $productsubcategoryvalues = productSubCategoryValue::create([
                'value' => $request->value,
                'product_id' => $request->product_id,
                'sub_category_property_id' => $request->sub_category_property_id,
                'image' => $name,
            ]);
            $filename->storeAs('public/trader/ProductAttriuteValue', $name);
            if ($productsubcategoryvalues) {
                return $this->successResponse(new productSubCategoryValueResource($productsubcategoryvalues), 'success operation');
            }
        } catch (Exception $e) {
            return $this->errorResponse('internal server error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $productsubcategoryvalues = productSubCategoryValue::findOrFail($id);
            if ($productsubcategoryvalues) {
                return $this->successResponse(new productSubCategoryValueResource($productsubcategoryvalues), 'success operation');
            } else {
                return $this->errorResponse('product sub category values Not Found', 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse('internal server error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // try {
        $vali = Validator::make($request->all(), [
            'value' => 'required|string',
            'image' => ':png,jpg|max:8000',
            'product_id' => 'required|not_in:0|exists:categories,id',
            'sub_category_property_id' => 'required|not_in:0|exists:categories,id',
        ]);
        if ($vali->fails()) {
            return $this->errorResponse($vali->errors(), 422);
        }
        $productsubcategoryvalues = productSubCategoryValue::find($id);
        $check = true;

        if ($request->hasFile('image')) {
            $imagePath = storage_path('app/public/trader/ProductAttriuteValue/') . $productsubcategoryvalues->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $filename = $request->image;
            $name = $request->product_id . '.' . $request->value . '.' .   time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            $productsubcategoryvalues->update([
                'image' => $name,
            ]);
            $filename->storeAs('public/trader/ProductAttriuteValue', $name);
            $SucssesMesssge['image'] = 'image updated successfully';
        }

        $keys = ['value', 'product_id', 'sub_category_property_id',];
        for ($i = 0; $i < count($keys); $i++) {
            $property = $keys[$i];
            if ($request->$property != $productsubcategoryvalues->$property) {
                $productsubcategoryvalues->$property = $request->$property;
                $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                $check = false;
            }
        }
        if ($check) {
            $SucssesMesssge['info'] = 'No thing to update';
            return $this->successResponse(new productSubCategoryValueResource($productsubcategoryvalues), $SucssesMesssge['info']);
        } else {
            $productsubcategoryvalues->save();
            return $this->successResponse($productsubcategoryvalues, $SucssesMesssge);
        }
        // } catch (Exception $e) {
        //     return $this->errorResponse('Internal server error', 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
