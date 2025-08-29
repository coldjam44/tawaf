<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::latest()->get();
        return view('achievements.index', compact('achievements'));
    }

    public function edit(string $id)
    {
        $achievement = Achievement::findOrFail($id);
        return view('achievements.edit', compact('achievement'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|file',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ];

        // Ensure image column exists (safe guard if CLI migrate not run)
        if (Schema::hasTable('achievements') && !Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->string('image')->nullable()->after('name_en');
            });
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'achievement_' . time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('achievements');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            // Now that we've ensured the column exists, set image name
            if (Schema::hasColumn('achievements', 'image')) {
                $data['image'] = $imageName;
            }
        }

        Achievement::create($data);

        return redirect()->route('achievements.index')->with('success', trans('main_trans.achievement_created_successfully'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $achievement = Achievement::findOrFail($id);
        $achievement->name_ar = $request->name_ar;
        $achievement->name_en = $request->name_en;

        // Ensure column exists (same safeguard)
        if (Schema::hasTable('achievements') && !Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->string('image')->nullable()->after('name_en');
            });
        }

        if ($request->hasFile('image')) {
            // delete old
            if ($achievement->image) {
                $old = public_path('achievements/' . $achievement->image);
                if (file_exists($old)) {
                    @unlink($old);
                }
            }
            $image = $request->file('image');
            $imageName = 'achievement_' . time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('achievements');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            if (Schema::hasColumn('achievements', 'image')) {
                $achievement->image = $imageName;
            }
        }

        $achievement->save();

        return redirect()->route('achievements.index')->with('success', trans('main_trans.project_updated_successfully'));
    }

    public function destroy(string $id)
    {
        $achievement = Achievement::findOrFail($id);
        if ($achievement->image) {
            $path = public_path('achievements/' . $achievement->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        $achievement->delete();
        return redirect()->route('achievements.index')->with('success', trans('main_trans.achievement_deleted_successfully'));
    }
}


