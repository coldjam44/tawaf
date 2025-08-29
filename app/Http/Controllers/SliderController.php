<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->paginate(10); // عدد العناصر لكل صفحة

        return view('admin.sliders.index', compact('sliders'));
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
 public function store(Request $request)
{
    // 1) Validation
    $validator = Validator::make($request->all(), [
        'title_en'        => 'nullable|string|max:255',
        'title_ar'        => 'nullable|string|max:255',
        'project_logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'button1_text_en' => 'nullable|string|max:255',
        'button1_text_ar' => 'nullable|string|max:255',
        'button1_link'    => 'nullable|string|max:255',
        'button2_text_en' => 'nullable|string|max:255',
        'button2_text_ar' => 'nullable|string|max:255',
        'button2_link'    => 'nullable|string|max:255',
        'features_en'     => 'nullable|string',
        'features_ar'     => 'nullable|string',
        'brochure_link'   => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
        'status'          => 'required|boolean',
        'order'           => 'required|integer',
        'background_image'=> 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // 2) Handle background image upload
    $imageName = time() . '_bg.' . $request->background_image->extension();
    $request->background_image->move(public_path('sliders'), $imageName);

    // 3) Handle project logo upload (optional)
    $logoFile = null;
    if ($request->hasFile('project_logo')) {
        $logoFile = time() . '_logo.' . $request->project_logo->extension();
        $request->project_logo->move(public_path('sliders'), $logoFile);
    }

    // 4) Handle brochure upload (optional)
    $brochureFile = null;
    if ($request->hasFile('brochure_link')) {
        $brochureFile = time() . '_brochure.' . $request->brochure_link->extension();
        $request->brochure_link->move(public_path('sliders'), $brochureFile);
    }

    // 5) Handle features (convert comma-separated to JSON)
    $features_en = $request->features_en
        ? json_encode(array_map('trim', explode(',', $request->features_en)))
        : null;

    $features_ar = $request->features_ar
        ? json_encode(array_map('trim', explode(',', $request->features_ar)))
        : null;

    // 6) Save to DB
    $slider = new Slider();
    $slider->background_image = $imageName;
    $slider->project_logo     = $logoFile; // save project logo if uploaded
    $slider->title_en         = $request->title_en;
    $slider->title_ar         = $request->title_ar;
    $slider->button1_text_en  = $request->button1_text_en;
    $slider->button1_text_ar  = $request->button1_text_ar;
    $slider->button1_link     = $request->button1_link;
    $slider->button2_text_en  = $request->button2_text_en;
    $slider->button2_text_ar  = $request->button2_text_ar;
    $slider->button2_link     = $request->button2_link;
    $slider->features_en      = $features_en;
    $slider->features_ar      = $features_ar;
    $slider->brochure_link    = $brochureFile;
    $slider->status           = $request->status;
    $slider->order            = $request->order;
    $slider->save();

    return redirect()->route('sliders.index')->with('success', 'Slider created successfully!');
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(string $id)
{
    $slider = Slider::findOrFail($id); // جلب السلايدر المطلوب
    return view('admin.sliders.edit', compact('slider'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $slider = Slider::findOrFail($id);

    // 1) Validation
    $validator = Validator::make($request->all(), [
        'title_en'        => 'nullable|string|max:255',
        'title_ar'        => 'nullable|string|max:255',
        'button1_text_en' => 'nullable|string|max:255',
        'button1_text_ar' => 'nullable|string|max:255',
        'button1_link'    => 'nullable|string|max:255',
        'button2_text_en' => 'nullable|string|max:255',
        'button2_text_ar' => 'nullable|string|max:255',
        'button2_link'    => 'nullable|string|max:255',
        'features_en'     => 'nullable|string',
        'features_ar'     => 'nullable|string',
        'brochure_link'   => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'status'          => 'required|boolean',
        'order'           => 'required|integer',
        'background_image'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'project_logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // 2) Handle background image upload (optional)
    if ($request->hasFile('background_image')) {
        $imageName = time() . '_bg.' . $request->background_image->extension();
        $request->background_image->move(public_path('sliders'), $imageName);
        $slider->background_image = $imageName;
    }

    // 3) Handle project logo upload (optional)
    if ($request->hasFile('project_logo')) {
        $logoName = time() . '_logo.' . $request->project_logo->extension();
        $request->project_logo->move(public_path('sliders'), $logoName);
        $slider->project_logo = $logoName;
    }

    // 4) Handle brochure upload (optional)
    if ($request->hasFile('brochure_link')) {
        $brochureFile = time() . '_brochure.' . $request->brochure_link->extension();
        $request->brochure_link->move(public_path('sliders'), $brochureFile);
        $slider->brochure_link = $brochureFile;
    }

    // 5) Handle features
    $slider->features_en = $request->features_en ? json_encode(array_map('trim', explode(',', $request->features_en))) : null;
    $slider->features_ar = $request->features_ar ? json_encode(array_map('trim', explode(',', $request->features_ar))) : null;

    // 6) Update other fields
    $slider->title_en        = $request->title_en;
    $slider->title_ar        = $request->title_ar;
    $slider->button1_text_en = $request->button1_text_en;
    $slider->button1_text_ar = $request->button1_text_ar;
    $slider->button1_link    = $request->button1_link;
    $slider->button2_text_en = $request->button2_text_en;
    $slider->button2_text_ar = $request->button2_text_ar;
    $slider->button2_link    = $request->button2_link;
    $slider->status          = $request->status;
    $slider->order           = $request->order;

    $slider->save();

    return redirect()->route('sliders.index')->with('success', 'Slider updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
  public function destroy(string $id)
{
    $slider = Slider::findOrFail($id);

    // حذف الخلفية إذا موجودة
    if ($slider->background_image && file_exists(public_path('sliders/' . $slider->background_image))) {
        unlink(public_path('sliders/' . $slider->background_image));
    }

    // حذف شعار المشروع إذا موجود
    if ($slider->project_logo && file_exists(public_path('sliders/' . $slider->project_logo))) {
        unlink(public_path('sliders/' . $slider->project_logo));
    }

    // حذف البروشور إذا موجود
    if ($slider->brochure_link && file_exists(public_path('sliders/' . $slider->brochure_link))) {
        unlink(public_path('sliders/' . $slider->brochure_link));
    }

    // حذف السلايدر من قاعدة البيانات
    $slider->delete();

    return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully!');
}

}
