<?php

namespace App\Http\Controllers;

use App\Models\UserDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserDocumentController extends Controller
{
    /**
     * Categories accepted for employee documents.
     */
    public const CATEGORIES = ['ID', 'Passport', 'Degree / Certificate', 'Employment Contract', 'Bank Details', 'Other'];

    public function index(): View
    {
        $documents = UserDocument::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('documents.index', [
            'documents' => $documents,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:'.implode(',', self::CATEGORIES),
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('document');

        // Store privately so only the owner (and admins reviewing) can access it.
        $path = $file->store('employee-documents', 'local');

        UserDocument::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded for verification. An admin will review it shortly.');
    }

    /**
     * Download a document. Owners may download their own; admins may download any.
     */
    public function download(UserDocument $document): StreamedResponse
    {
        $user = Auth::user();

        if (! $user->hasRole('admin') && $document->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }
}