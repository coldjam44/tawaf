<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')
            ->get();

        return view('messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'position_en' => 'required|string|max:255',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'image' => 'nullable|file'
        ]);

        $data = $request->all();

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_main_' . $request->name_en . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('messagesfiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
        }
        
        $data['image_path'] = $imageName;

        Message::create($data);

        return redirect()->route('messages.index')
            ->with('success', trans('main_trans.message_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return view('messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        return view('messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'position_en' => 'required|string|max:255',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'image' => 'nullable|file'
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($message->image_path && file_exists(public_path('messagesfiles/' . $message->image_path))) {
                unlink(public_path('messagesfiles/' . $message->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_main_' . $request->name_en . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('messagesfiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $data['image_path'] = $imageName;
        }

        $message->update($data);

        return redirect()->route('messages.index')
            ->with('success', trans('main_trans.message_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        // Delete image file
        if ($message->image_path && file_exists(public_path('messagesfiles/' . $message->image_path))) {
            unlink(public_path('messagesfiles/' . $message->image_path));
        }

        $message->delete();

        return redirect()->route('messages.index')
            ->with('success', trans('main_trans.message_deleted_successfully'));
    }
}
