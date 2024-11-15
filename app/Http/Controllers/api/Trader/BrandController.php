<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function index()
    {

        try {
            $brands = Brand::all();
            if ($brands) {
                return $this->successResponse($brands);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error: ', 500);
        }
    }
    public function store(Request $request)
    {
        $vali = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:png,jpg|max:8000',
        ]);

        try {
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $brand = Brand::create([
                'name' => $request->name,
                'image' => $name,
            ]);
            $filename->storeAs('public/trader/Brand', $name);
            return $this->successResponse($brand, 'Brand created Successfully!');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error: ', 500);
        }
    }
    public function update(Request $request, string $id)
    {

        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'image' => 'nullable|mimes:png,jpg|max:8000',

            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }
            $brand = Brand::find($id);
            $check = true;

            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/trader/Brand/') . $brand->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $brand->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/trader/Brand', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }

            $keys = ['name'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $brand->$property) {
                    $brand->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($brand,   $SucssesMesssge);
            } else {
                $brand->save();
                return $this->successResponse($brand, $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function edit($id)
    {
        try {
            $brand = Brand::find($id);
            if ($brand) {
                return $this->successResponse($brand, 'your  Brand');
            } else
                return $this->successResponse(NULL, 'your  Brand');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                return $this->errorResponse('Brand not found', 404);
            }

            $imagePath = storage_path('app/public/trader/Brand/') . $brand->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $brand->delete();
            return $this->successResponse(NULL, 'Brand deleted Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
}