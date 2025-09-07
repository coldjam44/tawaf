<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewsletterExport;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(10);
        return view('newsletter.index', compact('newsletters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('newsletter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'nullable|string',
        ]);

        Newsletter::create($request->all());

        return redirect()->route('newsletter.index')
            ->with('success', trans('main_trans.newsletter_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        return view('newsletter.show', compact('newsletter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        return view('newsletter.edit', compact('newsletter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'nullable|string',
        ]);

        $newsletter->update($request->all());

        return redirect()->route('newsletter.index')
            ->with('success', trans('main_trans.newsletter_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return redirect()->route('newsletter.index')
            ->with('success', trans('main_trans.newsletter_deleted_successfully'));
    }

    /**
     * Export newsletter data to Excel
     */
    public function export()
    {
        try {
            $fileName = 'newsletter_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new NewsletterExport, $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while exporting data: ' . $e->getMessage());
        }
    }
}
