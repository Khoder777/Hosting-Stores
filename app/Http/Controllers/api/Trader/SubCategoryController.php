<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        try {
            $subcategories = SubCategory::paginate();
            return $this->successResponse($subcategories, 'all sub categories recived successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }


    public function store(Request $request)
    {
        $vali = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:png,jpg|max:8000',
            'category_id' => 'required|not_in:0|exists:categories,id'
        ]);
        try {
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {

                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $subCategory = SubCategory::create([
                'name' => $request->name,
                'image' => $name,
                'category_id' => $request->category_id
            ]);
            $filename->storeAs('public/trader/subCategory', $name);

            return $this->successResponse($subCategory, 'subCategory careateded successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {


            $vali = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'image' => 'required|mimes:png,jpg|max:8000',
                'category_id' => 'required|not_in:0|exists:categories,id'
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }
            $Subcategory = SubCategory::find($id);
            $check = true;

            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/trader/subCategory/') . $Subcategory->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $Subcategory->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/trader/subCategory', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }

            $keys = ['name', 'category_id'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $Subcategory->$property) {
                    $Subcategory->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($Subcategory,   $SucssesMesssge);
            } else {
                $Subcategory->save();
                return $this->successResponse($Subcategory, $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function edit($id)
    {
        try {
            $Subcategory = SubCategory::find($id);
            if ($Subcategory) {
                return $this->successResponse($Subcategory, 'your  Subcategory ');
            } else
                return $this->successResponse(NULL, 'your  Subcategory is not found ');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Subcategory = SubCategory::find($id);
            if (!$Subcategory) {
                return $this->errorResponse('Subcategory not found', 404);
            }
            if ($Subcategory->SubCategoeyProperties->isEmpty()) {
                $imagePath = storage_path('app/public/trader/subCategory/') . $Subcategory->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $Subcategory->delete();
                return $this->successResponse(NULL, 'Subcategory deleted Successfully');
            } else {
                return $this->errorResponse('Subcategory cant delete because there is properties belong to it delete its and try again later ', 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
}
