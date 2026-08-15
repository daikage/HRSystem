<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use App\Notifications\DocumentReviewedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class DocumentReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserDocument::with('user');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    public function approve(UserDocument $document): RedirectResponse
    {
        $document->update(['status' => 'approved']);

        Notification::send($document->user, new DocumentReviewedNotification($document->title, 'approved'));

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document approved and the employee has been notified.');
    }

    public function reject(Request $request, UserDocument $document): RedirectResponse
    {
        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $document->update([
            'status' => 'rejected',
            'admin_feedback' => $validated['feedback'],
        ]);

        Notification::send($document->user, new DocumentReviewedNotification($document->title, 'rejected', $validated['feedback']));

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document marked as rejected and the employee has been notified.');
    }
}