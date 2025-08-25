<?php

namespace App\Http\Controllers\Apis;

use App\Models\hotel;
use App\Models\avilableroom;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class AvilableroomController extends Controller
{
use GeneralTrait;
    public function index()
    {
        //$hotels = hotel::all();
        $avilablerooms = avilableroom::paginate(5);

        $images_data = $avilablerooms->getCollection()->map(function ($avilableroom) {
            $imagesArray = json_decode($avilableroom->availableroom_image, true); // فك تشفير حقل الصورة
            return[
            'id' => $avilableroom->id,
            'availableroom_type_en' => $avilableroom->availableroom_type_en,
            'availableroom_type_ar' => $avilableroom->availableroom_type_ar,
            'availableroom_price' => $avilableroom->availableroom_price,
            'hotel_id' => $avilableroom->hotel_id,
            'availableroom_image' => !empty($imagesArray)
                    ? collect($imagesArray)->map(function ($imageName) {
                        return url('availableroom_image/' . $imageName); // إرجاع الرابط الكامل للصورة
                    })->toArray()
                    : [],
            ];
        });

        return $this->returnData('avilablerooms',$images_data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'availableroom_type_en' => 'required',
            'availableroom_type_ar' => 'required',
            'availableroom_price' => 'required',
            'hotel_id' => 'required|exists:hotels,id',
            'availableroom_image' => ['required', 'array'],
            'availableroom_image.*' => ['file', 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ], [
            'availableroom_type_en.required' => 'availableroom_type_en is required',
            'availableroom_type_ar.required' => 'availableroom_type_ar is required',
            'availableroom_price.required' => 'availableroom_price is required',
            'hotel_id.required' => 'hotel_id is required',
            'hotel_id.exists' => 'The selected hotel does not exist.',
            'availableroom_image.required' => 'availableroom_image is required',
            'availableroom_image.*.mimes' => 'Each file must be an image (jpeg, png, jpg, gif, svg, or webp).',
        ]);

        try {
            $avilableroom = new avilableroom();
            $avilableroom->availableroom_type_en = $request->availableroom_type_en;
            $avilableroom->availableroom_type_ar = $request->availableroom_type_ar;
            $avilableroom->availableroom_price = $request->availableroom_price;
            $avilableroom->hotel_id = $request->hotel_id;

            if ($request->hasFile('availableroom_image')) {
                $imageNames = [];
                foreach ($request->file('availableroom_image') as $file) {
                    $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('availableroom_image'), $imageName);
                    $imageNames[] = $imageName;
                }
                $avilableroom->availableroom_image = json_encode($imageNames);
            }

            $avilableroom->save();
            return $this->returnData('avilableroom',$avilableroom);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'availableroom_type_en' => 'required',
        'availableroom_type_ar' => 'required',
        'availableroom_price' => 'required',
        'hotel_id' => 'required|exists:hotels,id',
        //'availableroom_image' => ['nullable', 'array'],
        //'availableroom_image.*' => ['file', 'mimes:jpeg,png,jpg,gif,svg,webp'],
    ], [
        'availableroom_type_en.required' => 'availableroom_type_en is required',
        'availableroom_type_ar.required' => 'availableroom_type_ar is required',
        'availableroom_price.required' => 'availableroom_price is required',
        'hotel_id.required' => 'hotel_id is required',
        //'hotel_id.exists' => 'The selected hotel does not exist.',
        //'availableroom_image.*.mimes' => 'Each file must be an image (jpeg, png, jpg, gif, svg, or webp).',
    ]);

         try {
        // جلب الغرفة المتاحة بواسطة المعرف
        $avilableroom = avilableroom::findOrFail($id);

        // تحديث البيانات الأساسية
        $avilableroom->availableroom_type_en = $request->availableroom_type_en;
        $avilableroom->availableroom_type_ar = $request->availableroom_type_ar;
        $avilableroom->availableroom_price = $request->availableroom_price;
        $avilableroom->hotel_id = $request->hotel_id;

            // Handle image upload if new images are provided
            if ($request->hasFile('availableroom_image')) {
            // حذف الصور القديمة
            $oldImages = json_decode($avilableroom->availableroom_image, true);
            if (!empty($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    $imagePath = public_path('availableroom_image/' . $oldImage);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // رفع الصور الجديدة
            $imageNames = [];
            foreach ($request->file('availableroom_image') as $file) {
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('availableroom_image'), $imageName);
                $imageNames[] = $imageName;
            }
            $avilableroom->availableroom_image = json_encode($imageNames);
        }

            // Save the updated room data
            $avilableroom->save();

            return $this->returnData('avilableroom',$avilableroom);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function destroy($id)
    {
        $avilableroom = avilableroom::find($id);

        if (!$avilableroom) {
            return redirect()->back()->with('error', 'Model image not found');
        }

        // Decode the JSON-encoded image array
        $images = json_decode($avilableroom->availableroom_image);

        if (is_array($images)) {
            foreach ($images as $image) {
                $imagePath = public_path('availableroom_image/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
            }
        }

        // Delete the database record
        $avilableroom->delete();
        if(!$avilableroom){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }

    }
}
