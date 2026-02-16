<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('actor')->latest('id');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('model')) {
            $query->where('auditable_type', 'like', '%' . $request->model);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59',
            ]);
        }

        $auditLogs = $query->limit(1000)->get();

        return view('pages.pengguna.audit-trail', compact('auditLogs'));
    }
}