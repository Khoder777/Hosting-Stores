<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::paginate();
            return ProductResource::collection($products);
            if ($products) {
                return $this->successResponse($products, 'products reciverd successfully');
            } else {
                return $this->successResponse(Null, 'No products yet');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|integer|min:0|',
                'image' => 'required|:png,jpg|max:8000',
                'desc' => 'required|string',
                'rate' => 'integer|min:0|max:5',
                'market_id' => 'required|not_in:0|exists:markets,id',
                'category_id' => 'required|not_in:0|exists:categories,id'

            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'desc' => $request->desc,
                'rate' => 0,
                'market_id' => $request->market_id,
                'category_id' => $request->category_id,
                'image' => $name,
            ]);
            $filename->storeAs('public/trader/Products', $name);
            if ($product) {
                return $this->successResponse(new ProductResource($product));
            }
        } catch (Exception $e) {
            return $this->errorResponse('internal server error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product) {
                return $this->successResponse(new ProductResource($product));
            } else {
                return $this->errorResponse('product Not Found', 403);
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
        try {
            $vali = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|integer|min:0|',
                'image' => 'required|:png,jpg|max:8000',
                'desc' => 'required|string',
                'rate' => 'integer|min:0|max:5',
                'market_id' => 'required|not_in:0|exists:markets,id',
                'category_id' => 'required|not_in:0|exists:categories,id'

            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            $product = Product::find($id);
            $check = true;

            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/trader/Product/') . $product->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $product->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/trader/Product', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }

            $keys = ['name', 'price', 'desc', 'market_id', 'category_id'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $product->$property) {
                    $product->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse(new ProductResource($product),   $SucssesMesssge);
            } else {
                $product->save();
                return $this->successResponse(new ProductResource($product), $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->errorResponse('product not found', 403);
            }

            $imagePath = storage_path('app/public/trader/Product/') . $product->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $product->delete();
            return $this->successResponse(NULL, 'Product deleted Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
   
}