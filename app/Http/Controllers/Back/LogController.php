<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function activityLog()
    {
        $data = [
            'title' => 'Activity Log',
            'breadcrumbs' => [
                ['name' => 'Logs', 'link' => '#'],
                ['name' => 'Activity Log', 'link' => route('back.logs.activity')],
            ],
            'log_names' => Activity::select('log_name')->distinct()->orderBy('log_name')->pluck('log_name'),
            'events' => Activity::select('event')->whereNotNull('event')->distinct()->orderBy('event')->pluck('event'),
            'subject_types' => Activity::select('subject_type')->whereNotNull('subject_type')->distinct()->orderBy('subject_type')->get()->map(function ($item) {
                return [
                    'value' => $item->subject_type,
                    'label' => class_basename($item->subject_type),
                ];
            }),
        ];

        return view('back.pages.logs.activity', $data);
    }

    public function activityLogData(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Filter: log_name
        if ($request->filled('log_name') && $request->log_name !== 'all') {
            $query->where('log_name', $request->log_name);
        }

        // Filter: event
        if ($request->filled('event') && $request->event !== 'all') {
            $query->where('event', $request->event);
        }

        // Filter: subject_type
        if ($request->filled('subject_type') && $request->subject_type !== 'all') {
            $query->where('subject_type', $request->subject_type);
        }

        // Filter: causer (search by name)
        if ($request->filled('causer')) {
            $causerSearch = $request->causer;
            $query->whereHasMorph('causer', ['App\Models\User'], function ($q) use ($causerSearch) {
                $q->where('name', 'like', "%{$causerSearch}%");
            });
        }

        // Filter: date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Global search
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();
        $totalRecords = Activity::count();

        // Ordering
        $columns = ['id', 'log_name', 'description', 'subject_type', 'event', 'causer_id', 'created_at'];
        $orderColumn = $columns[$request->input('order.0.column', 0)] ?? 'id';
        $orderDir = $request->input('order.0.dir', 'desc');
        $query->orderBy($orderColumn, $orderDir);

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $activities = $query->offset($start)->limit($length)->get();

        $data = $activities->map(function ($activity) {
            $properties = $activity->properties->toArray();
            $propertiesHtml = '';

            if (!empty($properties)) {
                $stringify = function ($val) {
                    if (is_array($val) || is_object($val)) {
                        return json_encode($val, JSON_UNESCAPED_UNICODE);
                    }
                    return $val === null ? '-' : (string) $val;
                };

                if (isset($properties['old']) && isset($properties['attributes'])) {
                    $changes = [];
                    foreach ($properties['attributes'] as $key => $newValue) {
                        $oldValue = $properties['old'][$key] ?? '-';
                        if ($oldValue != $newValue) {
                            $changes[] = "<span class='text-muted fs-8'>" . e($key) . ":</span> <del class='text-danger fs-8'>" . e($stringify($oldValue)) . "</del> → <span class='text-success fs-8'>" . e($stringify($newValue)) . "</span>";
                        }
                    }
                    $propertiesHtml = implode('<br>', array_slice($changes, 0, 5));
                    if (count($changes) > 5) {
                        $propertiesHtml .= '<br><span class="text-muted fs-8">+' . (count($changes) - 5) . ' lainnya</span>';
                    }
                } elseif (isset($properties['attributes'])) {
                    $attrs = [];
                    foreach (array_slice($properties['attributes'], 0, 5) as $key => $val) {
                        $attrs[] = "<span class='text-muted fs-8'>" . e($key) . ":</span> <span class='fs-8'>" . e($stringify($val)) . "</span>";
                    }
                    $propertiesHtml = implode('<br>', $attrs);
                } else {
                    $json = json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    $propertiesHtml = '<code class="fs-8" style="max-width:300px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . e(substr($json, 0, 200)) . '</code>';
                }
            }

            $eventColors = [
                'created' => 'success',
                'updated' => 'warning',
                'deleted' => 'danger',
                'restored' => 'info',
            ];
            $eventColor = $eventColors[$activity->event] ?? 'secondary';

            return [
                'id' => $activity->id,
                'log_name' => '<span class="badge badge-light-primary fs-8">' . e($activity->log_name) . '</span>',
                'description' => e($activity->description),
                'subject' => $activity->subject_type
                    ? '<span class="text-gray-800 fs-7">' . class_basename($activity->subject_type) . '</span><br><span class="text-muted fs-8">#' . ($activity->subject_id ?? '-') . '</span>'
                    : '-',
                'event' => $activity->event
                    ? '<span class="badge badge-light-' . $eventColor . ' fs-8">' . e($activity->event) . '</span>'
                    : '-',
                'causer' => $activity->causer
                    ? '<div class="d-flex align-items-center"><div class="symbol symbol-25px me-2"><span class="symbol-label bg-light-primary fw-bold text-primary">' . strtoupper(substr($activity->causer->name, 0, 1)) . '</span></div><span class="fs-7">' . e($activity->causer->name) . '</span></div>'
                    : '<span class="text-muted fs-8">System</span>',
                'properties' => $propertiesHtml ?: '<span class="text-muted fs-8">-</span>',
                'created_at' => $activity->created_at->format('d M Y H:i:s'),
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data->values()->toArray(),
        ]);
    }
}
