<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpertTeam;
use Illuminate\Support\Facades\Validator;

class ExpertTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = ExpertTeam::orderBy('order_index', 'asc')->get();
        return view('expert-team.index', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'position_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'order_index' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $member = new ExpertTeam();
            $member->name_ar = $request->name_ar;
            $member->name_en = $request->name_en;
            $member->position_ar = $request->position_ar;
            $member->position_en = $request->position_en;
            $member->order_index = $request->order_index ?: 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_expert.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('expert-team');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $member->image = $imageName;
            }

            $member->save();

            return redirect()->route('expert-team.index')
                           ->with('success', trans('main_trans.expert_team_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the team member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = ExpertTeam::findOrFail($id);
        return view('expert-team.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'position_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'order_index' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $member = ExpertTeam::findOrFail($id);
            $member->name_ar = $request->name_ar;
            $member->name_en = $request->name_en;
            $member->position_ar = $request->position_ar;
            $member->position_en = $request->position_en;
            $member->order_index = $request->order_index ?: 0;

            // Handle image upload & replacement
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($member->image) {
                    $oldPath = public_path('expert-team/' . $member->image);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_expert.' . $image->getClientOriginalExtension();
                $uploadPath = public_path('expert-team');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $image->move($uploadPath, $imageName);
                $member->image = $imageName;
            }

            $member->save();

            return redirect()->route('expert-team.index')
                           ->with('success', trans('main_trans.expert_team_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the team member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $member = ExpertTeam::findOrFail($id);
            
            // Delete image
            if ($member->image) {
                $imagePath = public_path('expert-team/' . $member->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $member->delete();
            
            return redirect()->route('expert-team.index')
                           ->with('success', trans('main_trans.expert_team_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the team member: ' . $e->getMessage());
        }
    }
}
