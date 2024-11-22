<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

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
            $category = Category::create([
                'name' => $request->name,
                'image' => $name,
            ]);

            $filename->storeAs('public/trader/Category', $name);

            return $this->successResponse($category, 'category created Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
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
            $category = Category::find($id);
            $check = true;

            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/trader/Category/') . $category->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $category->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/trader/Category', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }

            $keys = ['name'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $category->$property) {
                    $category->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($category,   $SucssesMesssge);
            } else {
                $category->save();
                return $this->successResponse($category, $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function edit($id)
    {
        try {
            $category = Category::find($id);
            if ($category) {
                return $this->successResponse($category, 'your  category ');
            } else
                return $this->successResponse(NULL, 'your  category is not found ');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->errorResponse('Brand not found', 404);
            }
            if ($category->subCategories->isEmpty()) {
                $imagePath = storage_path('app/public/trader/Category/') . $category->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $category->delete();
                return $this->successResponse(NULL, 'category deleted Successfully');
            } else {
                return $this->errorResponse(' there is subcategory joind with category please delete it and try again later ', 402);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function products($id)
    {
        try {
            $category = Category::findOrFail($id);
            $products = $category->products;
            $productsArray = [];
            foreach ($products as $product) {
                $productsArray[] = new ProductResource($product);
            }
            return $this->successResponse($productsArray, 'Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
}
