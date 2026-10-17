<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class AuditLogController extends Controller
{
    private const PAGE_SIZE = 25;

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'action' => ['nullable', 'string', Rule::in(['viewed', 'created', 'updated', 'deleted', 'exported', 'login', 'logout', 'action'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $filters = [
            'search' => trim((string) ($validated['search'] ?? '')),
            'action' => (string) ($validated['action'] ?? ''),
            'date_from' => (string) ($validated['date_from'] ?? ''),
            'date_to' => (string) ($validated['date_to'] ?? ''),
            'user_id' => (string) ($validated['user_id'] ?? ''),
        ];

        $query = AuditLog::query()->with('user');

        if ($filters['search'] !== '') {
            $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filters['search']).'%';
            $query->where(function ($logs) use ($term) {
                $logs->whereRaw('user_name ILIKE ?', [$term])
                    ->orWhereRaw('user_email ILIKE ?', [$term])
                    ->orWhereRaw('route_name ILIKE ?', [$term])
                    ->orWhereRaw('path ILIKE ?', [$term])
                    ->orWhereRaw('ip_address ILIKE ?', [$term]);
            });
        }

        if ($filters['action'] !== '') {
            $query->where('action', $filters['action']);
        }
        if ($filters['date_from'] !== '') {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to'] !== '') {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if ($filters['user_id'] !== '') {
            $query->where('user_id', (int) $filters['user_id']);
        }

        $logs = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(self::PAGE_SIZE)
            ->withQueryString();

        $today = AuditLog::whereDate('created_at', today());
        $summary = [
            'today' => (clone $today)->count(),
            'changes' => (clone $today)->whereIn('action', ['created', 'updated', 'deleted'])->count(),
            'users' => (clone $today)->distinct('user_id')->count('user_id'),
        ];
        $selectedUser = null;
        if ($filters['user_id'] !== '') {
            $selectedUser = AuditLog::query()
                ->where('user_id', (int) $filters['user_id'])
                ->orderByDesc('created_at')
                ->first(['user_id', 'user_name', 'user_email']);
        }

        return view('admin.audit_logs.index', compact('logs', 'filters', 'summary', 'selectedUser'));
    }
}
