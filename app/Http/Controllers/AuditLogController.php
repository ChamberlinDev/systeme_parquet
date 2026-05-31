<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $filtres = $request->only(['action', 'user_id', 'date_du', 'date_au', 'q']);

        $query = AuditLog::with('user')->latest('created_at');

        if (!empty($filtres['action'])) {
            $query->where('action', $filtres['action']);
        }

        if (!empty($filtres['user_id'])) {
            $query->where('user_id', $filtres['user_id']);
        }

        if (!empty($filtres['date_du'])) {
            $query->whereDate('created_at', '>=', $filtres['date_du']);
        }

        if (!empty($filtres['date_au'])) {
            $query->whereDate('created_at', '<=', $filtres['date_au']);
        }

        if (!empty($filtres['q'])) {
            $q = $filtres['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('detail', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%")
                    ->orWhere('objet_type', 'like', "%{$q}%");
            });
        }

        $logs      = $query->paginate(30)->withQueryString();
        $users     = User::orderBy('name')->get();
        $actions   = AuditLog::$labels;
        $couleurs  = AuditLog::$couleurs;

        return view('admin.audit.index', compact('logs', 'filtres', 'users', 'actions', 'couleurs'));
    }

    public function export(Request $request)
    {
        $filtres = $request->only(['action', 'user_id', 'date_du', 'date_au']);

        $query = AuditLog::with('user')->latest('created_at');

        if (!empty($filtres['action']))  $query->where('action', $filtres['action']);
        if (!empty($filtres['user_id'])) $query->where('user_id', $filtres['user_id']);
        if (!empty($filtres['date_du'])) $query->whereDate('created_at', '>=', $filtres['date_du']);
        if (!empty($filtres['date_au'])) $query->whereDate('created_at', '<=', $filtres['date_au']);

        $logs = $query->limit(5000)->get();

        $filename = 'audit_log_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Date/Heure', 'Utilisateur', 'Action',
                'Type objet', 'ID objet', 'Détail', 'Adresse IP', 'User Agent',
            ], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user->name ?? 'Système',
                    AuditLog::$labels[$log->action] ?? $log->action,
                    $log->objet_type ?? '',
                    $log->objet_id   ?? '',
                    $log->detail     ?? '',
                    $log->ip_address ?? '',
                    $log->user_agent ?? '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
