<?php

namespace App\Http\Controllers\Apis;

use App\Models\amenity;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmenityController extends Controller
{
use GeneralTrait;
public function index()
{
    // جلب جميع التسهيلات
    $amenitys = amenity::all();

    // تحويل الصورة إلى رابط كامل
    $amenitys = $amenitys->map(function ($amenity) {
        // إذا كانت صورة موجودة، يتم تحويلها إلى رابط كامل
        if ($amenity->image) {
            $amenity->image = url('amenity/' . $amenity->image);
        }
        return $amenity;
    });

    // إرجاع البيانات مع الروابط
    return $this->returnData('amenitys', $amenitys);
}


    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'image.required' => 'Image is required',
        ]);
        try{
        $amenity=new amenity();
        $amenity->name_en=$request->name_en;
        $amenity->name_ar=$request->name_ar;
        $image = $request->image;
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $request->image->move(public_path('amenity'), $imagename);
        $amenity->image=$imagename;
        $amenity->save();
        return $this->returnData('amenity',$amenity);
        }catch(\Exception $e){
            return $this->returnError('E001','error');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'nullable|image', // الصورة اختيارية
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
        ]);

        try {
            $amenity = amenity::findOrFail($request->id);
            // تحديث الاسم باللغة الإنجليزية والعربية
            $amenity->name_en = $request->name_en;
            $amenity->name_ar = $request->name_ar;

            // إذا تم رفع صورة جديدة
            if ($request->hasFile('image')) {
                // إذا كانت هناك صورة جديدة، نحذف القديمة أولاً
                $oldImage = public_path('amenity/' . $amenity->image);
                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }

                // رفع الصورة الجديدة
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('amenity'), $imageName);

                // تحديث اسم الصورة في قاعدة البيانات
                $amenity->image = $imageName;
            }

            // حفظ التغييرات في قاعدة البيانات
            $amenity->save();

            // إعادة البيانات بعد التحديث
            return $this->returnData('amenity', $amenity);

        } catch (\Exception $e) {
            return $this->returnError('E001', 'Error: ' . $e->getMessage());
        }
    }





    public function destroy($id)
    {
        $amenity=amenity::find($id);
        $amenity->delete();
        if(!$amenity){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }    }
}
