<?php

namespace App\Http\Controllers\Apis;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

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

    /**
     * API: Get all messages
     */
    public function apiIndex(): JsonResponse
    {
        try {
            $messages = Message::orderBy('created_at', 'desc')->get();
            
            // Transform image to full URL
            $messages->each(function ($message) {
                if ($message->image_path) {
                    $message->image_url = asset('messagesfiles/' . $message->image_path);
                } else {
                    $message->image_url = null;
                }
                // Remove image_path from response
                unset($message->image_path);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Messages retrieved successfully',
                'data' => $messages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get list of all messages with ID and name only
     */
    public function apiList(): JsonResponse
    {
        try {
            $messages = Message::select('id', 'name_ar', 'name_en')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Messages list retrieved successfully',
                'data' => $messages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve messages list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get message by ID
     */
    public function apiShow($id): JsonResponse
    {
        try {
            // Get specific message by ID
            $message = Message::findOrFail($id);
            
            // Transform image to full URL
            if ($message->image_path) {
                $message->image_url = asset('messagesfiles/' . $message->image_path);
            } else {
                $message->image_url = null;
            }
            // Remove image_path from response
            unset($message->image_path);
            
            return response()->json([
                'success' => true,
                'message' => 'Message retrieved successfully',
                'data' => $message
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve message',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
