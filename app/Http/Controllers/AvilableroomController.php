<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\avilableroom;
use Illuminate\Http\Request;

class AvilableroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avilablerooms = avilableroom::paginate(5);
        $hotels = hotel::all();
        return view('pages.avilablerooms.avilablerooms', compact('avilablerooms','hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //     'availableroom_type_en'=>'required',
    //     'availableroom_type_ar'=>'required',
    //     'availableroom_price'=>'required',
    //     'hotel_id'=>'required',
    //     'availableroom_image' => ['required', 'array', ],
    //     ],[
    //         'availableroom_type_en.required'=>'availableroom_type_en is required',
    //         'availableroom_type_ar.required'=>'availableroom_type_ar is required',
    //         'availableroom_price.required'=>'availableroom_price is required',
    //         'hotel_id.required'=>'hotel_id is required',
    //         'availableroom_image.required'=>'availableroom_image is required',

    //     ]);
    //     try {
    //         $avilableroom = new avilableroom();
    //         $avilableroom->availableroom_type_en = $request->availableroom_type_en;
    //         $avilableroom->availableroom_type_ar = $request->availableroom_type_ar;
    //         $avilableroom->availableroom_price = $request->availableroom_price;
    //         $avilableroom->hotel_id = $request->hotel_id;

    //         if ($request->hasFile('availableroom_image')) {
    //             $imageNames = [];
    //             foreach ($request->file('availableroom_image') as $file) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('availableroom_image'), $imageName);
    //                 $imageNames[] = $imageName;
    //             }
    //             $avilableroom->image = json_encode($imageNames);
    //         }
    //         $avilableroom->save();
    //         return redirect()->back()->with('success', 'avilableroom created successfully');
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', 'Something went wrong');
    //     }
    // }

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
        return redirect()->back()->with('success', 'Available room created successfully');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $th->getMessage());
    }
}


    /**
     * Display the specified resource.
     */
    public function show(avilableroom $avilableroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(avilableroom $avilableroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    
           
             public function update(Request $request, $id)
{
    // التحقق من القيم المدخلة
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

        // إدارة الصور إذا تم تحميل صور جديدة
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

        // حفظ التحديثات
        $avilableroom->save();

        return redirect()->back()->with('success', 'Available room updated successfully');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $th->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $avilableroom = avilableroom::findOrFail($id);
        $avilableroom->delete();
        return redirect()->back()->with('success', 'avilableroom deleted successfully');
    }
}
