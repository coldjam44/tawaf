<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactMessagesExport;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10);
        return view('contact-messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact-messages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            return redirect()->back()->with('success', trans('main_trans.message_sent_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while sending the message: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        return view('contact-messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('contact-messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $message->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            return redirect()->route('contact-messages.index')->with('success', trans('main_trans.message_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the message: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();
            
            return redirect()->route('contact-messages.index')->with('success', trans('main_trans.message_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the message: ' . $e->getMessage());
        }
    }

    /**
     * Toggle read status
     */
    public function toggleRead(string $id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->is_read = !$message->is_read;
            $message->save();

            $message_text = $message->is_read ? 
                trans('main_trans.message_marked_as_read') : 
                trans('main_trans.message_marked_as_unread');

            return redirect()->back()->with('success', $message_text);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the message status: ' . $e->getMessage());
        }
    }

    /**
     * Export contact messages to Excel
     */
    public function export()
    {
        try {
            $fileName = 'contact_messages_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new ContactMessagesExport, $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while exporting data: ' . $e->getMessage());
        }
    }
}
