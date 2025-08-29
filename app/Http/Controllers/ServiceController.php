<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ServiceController extends Controller
{
    public function index()
    {
        // Ensure table exists to avoid errors if migrations haven't been run yet
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en');
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->string('contact_phone')->nullable();
                $table->boolean('request_service')->default(false);
                $table->timestamps();
            });
        }
        $services = Service::latest()->get();
        return view('services.index', compact('services'));
    }

    public function edit(string $id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function store(Request $request)
    {
        // Ensure table exists before validation/save (safety)
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en');
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->string('contact_phone')->nullable();
                $table->boolean('request_service')->default(false);
                $table->timestamps();
            });
        }

        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|file',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'contact_phone' => $request->contact_phone,
        ];

        // Ensure image column exists (safety)
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'image')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('image')->nullable()->after('description_en');
            });
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'service_' . time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('services');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            if (Schema::hasColumn('services', 'image')) {
                $data['image'] = $imageName;
            }
        }

        Service::create($data);

        return redirect()->route('services.index')->with('success', trans('main_trans.project_created_successfully'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|file',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service = Service::findOrFail($id);
        $service->title_ar = $request->title_ar;
        $service->title_en = $request->title_en;
        $service->description_ar = $request->description_ar;
        $service->description_en = $request->description_en;
        $service->contact_phone = $request->contact_phone;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                $old = public_path('services/' . $service->image);
                if (file_exists($old)) {
                    @unlink($old);
                }
            }
            $image = $request->file('image');
            $imageName = 'service_' . time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('services');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            $service->image = $imageName;
        }

        $service->save();

        return redirect()->route('services.index')->with('success', trans('main_trans.project_updated_successfully'));
    }

    public function destroy(string $id)
    {
        if (!Schema::hasTable('services')) {
            return redirect()->route('services.index');
        }
        $service = Service::findOrFail($id);
        if ($service->image) {
            $path = public_path('services/' . $service->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        $service->delete();
        return redirect()->route('services.index')->with('success', trans('main_trans.project_deleted_successfully'));
    }
}


