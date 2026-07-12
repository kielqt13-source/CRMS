<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecognitionRequest;
use App\Jobs\ProcessRecognition;
use App\Models\ActivityLog;
use App\Models\Recognition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HandwrittenRecognitionController extends Controller
{
    /**
     * List the authenticated user's recognitions.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->recognitions()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $recognitions = $query->paginate(15)->withQueryString();

        $statusCounts = Auth::user()->recognitions()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('user.recognitions.index', compact('recognitions', 'statusCounts'));
    }

    /**
     * Show the upload form.
     */
    public function create()
    {
        return view('user.recognitions.create');
    }

    /**
     * Store a single uploaded file.
     */
    public function store(StoreRecognitionRequest $request)
    {
        $file     = $request->file('file');
        $filePath = $file->store('recognitions', 'public');
        $origName = $file->getClientOriginalName();
        $fileType = $this->fileType($origName);
        $docType  = $request->input('document_type');

        $recognition = DB::transaction(function () use ($filePath, $origName, $fileType, $docType) {
            $rec = Recognition::create([
                'user_id'           => Auth::id(),
                'file_path'         => $filePath,
                'original_filename' => $origName,
                'file_type'         => $fileType,
                'status'            => 'pending',
                'document_type'     => $docType,
            ]);

            ProcessRecognition::dispatch($rec);

            ActivityLog::log('upload', "Uploaded document: {$origName}", $rec);

            return $rec;
        });

        return redirect()->route('recognitions.show', $recognition)
            ->with('status', 'File uploaded successfully. Recognition is in progress.');
    }

    /**
     * Store multiple uploaded files (batch).
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'files'         => 'required|array|max:20',
            'files.*'       => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:20480',
            'document_type' => 'required|in:Birth Certificate,Marriage Certificate,Death Certificate',
        ]);

        $docType = $request->input('document_type');
        $batchId = Str::uuid()->toString();
        $count   = 0;

        DB::transaction(function () use ($request, $docType, $batchId, &$count) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('recognitions', 'public');
                $origName = $file->getClientOriginalName();
                $fileType = $this->fileType($origName);

                $rec = Recognition::create([
                    'user_id'           => Auth::id(),
                    'file_path'         => $filePath,
                    'original_filename' => $origName,
                    'file_type'         => $fileType,
                    'status'            => 'pending',
                    'document_type'     => $docType,
                    'batch_id'          => $batchId,
                ]);

                ProcessRecognition::dispatch($rec);
                $count++;
            }

            ActivityLog::log('batch_upload', "Batch uploaded {$count} {$docType} document(s).");
        });

        return redirect()->route('recognitions.index')
            ->with('batch_status', "Successfully uploaded {$count} file(s). Recognitions are now in progress.");
    }

    /**
     * Display a single recognition result.
     */
    public function show(Recognition $recognition)
    {
        $this->authorize('view', $recognition);
        $fields = Recognition::getDocumentFields($recognition->document_type ?? '');

        return view('user.recognitions.show', compact('recognition', 'fields'));
    }

    /**
     * Show the verification / data correction form.
     */
    public function verify(Recognition $recognition)
    {
        $this->authorize('view', $recognition);

        $fields     = Recognition::getDocumentFields($recognition->document_type ?? '');
        $prefilled  = $recognition->corrected_fields ?? $recognition->extracted_fields ?? [];

        return view('user.recognitions.verify', compact('recognition', 'fields', 'prefilled'));
    }

    /**
     * Save verified (corrected) data and mark as verified.
     */
    public function saveVerification(Request $request, Recognition $recognition)
    {
        $this->authorize('view', $recognition);

        $action = $request->input('action', 'verify');

        if ($action === 'reject') {
            $request->validate(['rejection_reason' => 'required|string|max:1000']);
            $recognition->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->input('rejection_reason'),
                'verified_by'      => Auth::id(),
                'verified_at'      => now(),
            ]);

            ActivityLog::log('reject', "Rejected recognition #{$recognition->id}", $recognition);

            return redirect()->route('recognitions.show', $recognition)
                ->with('status', 'Document has been rejected.');
        }

        // Approve — save corrected fields
        $corrected = $request->except(['_token', '_method', 'action', 'rejection_reason']);
        $recognition->update([
            'status'           => 'verified',
            'corrected_fields' => $corrected,
            'verified_by'      => Auth::id(),
            'verified_at'      => now(),
        ]);

        ActivityLog::log('verify', "Verified recognition #{$recognition->id}", $recognition);

        return redirect()->route('recognitions.show', $recognition)
            ->with('status', 'Document verified and saved successfully.');
    }

    protected function fileType(string $filename): string
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) return 'image';
        if ($ext === 'pdf') return 'pdf';
        if (in_array($ext, ['doc', 'docx'])) return 'word';
        return 'image';
    }
}
