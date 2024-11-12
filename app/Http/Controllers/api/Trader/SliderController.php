<?php

namespace App\Http\Controllers\api\Trader;

use Exception;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $slider = Slider::all();
            if ($slider) {
                return $this->successResponse($slider);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Intetnal Server Error', 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $vali = Validator::make($request->all(), [
                'status' => 'required|boolean',
                'type' => 'required|in:main,end,side',
                'image' => 'required|mimes:png,jpg|max:8000',
            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 422);
            }
            if ($request->hasFile('image')) {
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
            }
            $slider = Slider::create([
                'status' => $request->status,
                'type' => $request->type,
                'image' => $name,
            ]);
            $filename->storeAs('public/Admin/Slider', $name);
            return $this->successResponse($slider, 'Slider created Successfully!');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error: ', 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $slider = Slider::find($id);
            if ($slider) {
                return $this->successResponse($slider, 'your  slider return successfully');
            } else
                return $this->successResponse(NULL, 'your  slider is empty');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $vali = Validator::make($request->all(), [
                'status' => 'required|boolean',
                'type' => 'required|in:main,end,side',
                'image' => 'required|mimes:png,jpg|max:8000',

            ]);
            if ($vali->fails()) {
                return $this->errorResponse($vali->errors(), 403);
            }
            $slider = Slider::find($id);
            $check = true;
            if ($request->hasFile('image')) {
                $imagePath = storage_path('app/public/Admin/Slider/') . $slider->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $filename = $request->image;
                $name = time() . '.' . str_replace(' ', '', $filename->getClientOriginalName());
                $slider->update([
                    'image' => $name,
                ]);
                $filename->storeAs('public/Admin/Slider', $name);
                $SucssesMesssge['image'] = 'image updated successfully';
            }
            $keys = ['type', 'status'];
            for ($i = 0; $i < count($keys); $i++) {
                $property = $keys[$i];
                if ($request->$property != $slider->$property) {
                    $slider->$property = $request->$property;
                    $SucssesMesssge["$keys[$i]"] = $keys[$i] . " " . "updated successfully";
                    $check = false;
                }
            }
            if ($check) {
                $SucssesMesssge['info'] = 'No thing to update';
                return $this->successResponse($slider,   $SucssesMesssge);
            } else {
                $slider->save();
                return $this->successResponse($slider, $SucssesMesssge);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $slider = Slider::find($id);
            if (!$slider) {
                return $this->errorResponse('Slider not found', 404);
            }
            $imagePath = storage_path('app/public/Admin/Slider/') . $slider->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $slider->delete();
            return $this->successResponse(NULL, 'Sldier deleted Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Internal server error', 500);
        }
    }
}