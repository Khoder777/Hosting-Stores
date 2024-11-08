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
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }


    public function store(Request $request)
    {
        $vali = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:png,jpg|max:8000',
            'category_id' => 'required|not_in:0'
        ]);
        try {
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {

                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());

                $filename->storeAs('public/trader/subCategory', $name);

            }
            $subCategory = SubCategory::create([
                'name' => $request->name,
                'image' => $name,
                'category_id' => $request->category_id
            ]);
            return $this->successResponse($subCategory, 'subCategory careateded successfully.');

        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $vali = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|mimes:png,jpg|max:8000',
            'category_id' => 'required|not_in:0'
        ]);
        try {
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }
            $Subcategory = SubCategory::find($id);
            $Subcategory->update([
                'name' => $request->name,
                'category_id' => $request->category_id
            ]);
            if ($request->hasFile('image')) {
                File::delete(public_path('storage/SubCategory/') . $Subcategory->image);
                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $Subcategory->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/SubCategory', $name);
            }
            return $this->successResponse($Subcategory, 'SubCategory updated Successfully');

        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السيرفر يا اخوان بالصلاة عالنبي)', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Subcategory = subCategory::find($id);
            File::delete(public_path('storage/subCategory/') . $Subcategory->image);
            $Subcategory->delete();
            return $this->successResponse($Subcategory, 'SubCategory deleted Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السيرفر يا اخوان بالصلاة عالنبي)', 500);
        }
    }
}
