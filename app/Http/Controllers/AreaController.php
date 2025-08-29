<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::paginate(10);
        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:areas,slug',
            'about_community_overview_ar' => 'nullable|string',
            'about_community_overview_en' => 'nullable|string',
            'rental_sales_trends_ar' => 'nullable|string',
            'rental_sales_trends_en' => 'nullable|string',
            'roi_ar' => 'nullable|string',
            'roi_en' => 'nullable|string',
            'things_to_do_perks_ar' => 'nullable|string',
            'things_to_do_perks_en' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Area::create([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'slug' => $request->slug,
                'about_community_overview_ar' => $request->about_community_overview_ar,
                'about_community_overview_en' => $request->about_community_overview_en,
                'rental_sales_trends_ar' => $request->rental_sales_trends_ar,
                'rental_sales_trends_en' => $request->rental_sales_trends_en,
                'roi_ar' => $request->roi_ar,
                'roi_en' => $request->roi_en,
                'things_to_do_perks_ar' => $request->things_to_do_perks_ar,
                'things_to_do_perks_en' => $request->things_to_do_perks_en
            ]);

            return redirect()->route('areas.index')->with('success', trans('main_trans.area_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the area: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $area = Area::with('projects')->findOrFail($id);
        return view('areas.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $area = Area::findOrFail($id);
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $area = Area::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:areas,slug,' . $id,
            'about_community_overview_ar' => 'nullable|string',
            'about_community_overview_en' => 'nullable|string',
            'rental_sales_trends_ar' => 'nullable|string',
            'rental_sales_trends_en' => 'nullable|string',
            'roi_ar' => 'nullable|string',
            'roi_en' => 'nullable|string',
            'things_to_do_perks_ar' => 'nullable|string',
            'things_to_do_perks_en' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $area->update([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'slug' => $request->slug,
                'about_community_overview_ar' => $request->about_community_overview_ar,
                'about_community_overview_en' => $request->about_community_overview_en,
                'rental_sales_trends_ar' => $request->rental_sales_trends_ar,
                'rental_sales_trends_en' => $request->rental_sales_trends_en,
                'roi_ar' => $request->roi_ar,
                'roi_en' => $request->roi_en,
                'things_to_do_perks_ar' => $request->things_to_do_perks_ar,
                'things_to_do_perks_en' => $request->things_to_do_perks_en
            ]);

            return redirect()->route('areas.index')->with('success', trans('main_trans.area_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the area: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $area = Area::findOrFail($id);
            
            // Check if area has projects
            if ($area->projects()->count() > 0) {
                return redirect()->back()->with('error', trans('main_trans.cannot_delete_area_with_projects'));
            }
            
            $area->delete();
            
            return redirect()->route('areas.index')->with('success', trans('main_trans.area_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the area: ' . $e->getMessage());
        }
    }
}
