<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::paginate();
            return $this->successResponse($categories, 'all categories recived successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    public function store(Request $request)
    {
        $vali = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:png,jpg|max:8000',
        ]);
        try {

            if ($request->hasFile('image')) {

                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $categry = Category::create([
                'name' => $request->name,
                'image' => $name,
            ]);

            $filename->storeAs('public/trader/categry', $name);

            return $this->successResponse($categry, 'category created Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $vali = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg|max:8000',
        ]);
        try {
            if (!$vali) {
                // $this->apiValidation($request,$vali);
                // return $vali->errors();
                return 'error';
            }
            $category = category::find($id);
            $category->update([
                'name' => $request->name,
            ]);
            if ($request->hasFile('image')) {
                File::delete(public_path('storage/category/') . $category->image);
                $filename = $request->image;

                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $category->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/category', $name);
            }
            return $this->successResponse($category, 'categry updated Successfully');

        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
        }
    }

    public function destroy($id)
    {
        try {
        $category = category::find($id);
        if (!$category->subCategories()->count()) {
            File::delete(public_path('storage/category/') . $category->image);
            $category->delete();
            return $this->successResponse($category, 'categry deleted Successfully');
        }
        return $this->errorResponse('this category is bind with another Sub Category', 403);
    }catch (Exception $e) {
        return $this->errorResponse('Internal Server Error(فرش السرفر يا اخوان )', 500);
    }
    }
}
