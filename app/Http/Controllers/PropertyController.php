<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Project;
use App\Models\PaymentPlan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with(['project', 'paymentPlan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::with('area')->orderBy('id', 'desc')->get();
        $paymentPlans = PaymentPlan::active()->orderBy('name_ar')->get();
        
        // Get selected project location if project_id is provided
        $selectedProjectLocation = '';
        if (request('project_id')) {
            $selectedProject = Project::with('area')->find(request('project_id'));
            if ($selectedProject && $selectedProject->area) {
                $currentLocale = app()->getLocale();
                $selectedProjectLocation = $currentLocale === 'ar' ? $selectedProject->area->name_ar : $selectedProject->area->name_en;
            }
        }
        
        return view('properties.create', compact('projects', 'paymentPlans', 'selectedProjectLocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'propertyproject' => 'required|exists:projects,id',
            'propertypurpose' => 'required|in:sale,rental,both,investment,vacation',
            'propertyprice' => 'required|numeric|min:0',
            'propertyrooms' => 'required|integer|min:0',
            'propertybathrooms' => 'required|integer|min:0',
            'propertyarea' => 'required|numeric|min:0',
            'propertyquantity' => 'required|integer|min:1',
            'propertyloaction' => 'required|string|max:255',
            'propertypaymentplan' => 'required|exists:payment_plans,id',
            'propertyhandover' => 'nullable|date',
            'propertyfeatures' => 'nullable|array',
            'propertyfulldetils' => 'nullable|string',
            'propertyinformation' => 'nullable|string',
            'propertyimages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image uploads
        if ($request->hasFile('propertyimages')) {
            $images = [];
            foreach ($request->file('propertyimages') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
            $data['propertyimages'] = $images;
        }

        Property::create($data);

        return redirect()->route('properties.index')
            ->with('success', trans('main_trans.property_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load(['project', 'paymentPlan']);
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $projects = Project::orderBy('id', 'desc')->get();
        $paymentPlans = PaymentPlan::active()->orderBy('name_ar')->get();
        
        return view('properties.edit', compact('property', 'projects', 'paymentPlans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $request->validate([
            'propertyproject' => 'required|exists:projects,id',
            'propertypurpose' => 'required|in:sale,rental,both,investment,vacation',
            'propertyprice' => 'required|numeric|min:0',
            'propertyrooms' => 'required|integer|min:0',
            'propertybathrooms' => 'required|integer|min:0',
            'propertyarea' => 'required|numeric|min:0',
            'propertyquantity' => 'required|integer|min:1',
            'propertyloaction' => 'required|string|max:255',
            'propertypaymentplan' => 'required|exists:payment_plans,id',
            'propertyhandover' => 'nullable|date',
            'propertyfeatures' => 'nullable|array',
            'propertyfulldetils' => 'nullable|string',
            'propertyinformation' => 'nullable|string',
            'propertyimages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image uploads
        if ($request->hasFile('propertyimages')) {
            $images = [];
            foreach ($request->file('propertyimages') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
            $data['propertyimages'] = $images;
        }

        $property->update($data);

        return redirect()->route('properties.index')
            ->with('success', trans('main_trans.property_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Delete associated images
        if ($property->propertyimages) {
            foreach ($property->propertyimages as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', trans('main_trans.property_deleted_successfully'));
    }



    /**
     * Handle project selection and redirect to create form with location
     */
    public function selectProject(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id'
        ]);

        $project = Project::with('area')->find($request->project_id);
        if (!$project || !$project->area) {
            return redirect()->route('properties.create')
                ->with('error', trans('main_trans.no_location_found'));
        }

        $currentLocale = app()->getLocale();
        $location = $currentLocale === 'ar' ? $project->area->name_ar : $project->area->name_en;

        return redirect()->route('properties.create', ['project_id' => $request->project_id])
            ->with('success', trans('main_trans.location_filled_successfully', ['location' => $location]));
    }
}
