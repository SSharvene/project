<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Aduan;
use App\Models\Pinjaman;
use App\Models\StokRequest;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin dashboard
        if ($user->isAdminIct() || $user->isAdminHr()) {
            $totalAssets = Asset::count();
            $aduanNew = Aduan::where('status','baru')->count();
            $pinjamanPending = Pinjaman::where('status','pending')->count();
            $recentActivities = ActivityLog::latest()->limit(10)->get();

            // prepare chart for admin (example: pinjaman last 30 days)
            [$chartLabels, $chartData] = $this->getLast30DaysCounts(Pinjaman::class);

            return view('dashboard.admin', compact('totalAssets','aduanNew','pinjamanPending','recentActivities','chartLabels','chartData'));
        }

        // Staff dashboard
        $myPinjaman = Pinjaman::where('user_id', $user->id)->count();
        $myAduan   = Aduan::where('user_id', $user->id)->count();

        // FIX: stok_requests uses 'requester_id' not 'user_id'
        $myStok    = StokRequest::where('requester_id', $user->id)->count();

        $recentPinjaman = Pinjaman::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentAduan    = Aduan::where('user_id', $user->id)->latest()->limit(5)->get();
        $notifications   = []; // load real notifications if you have notifications table

        // chart for staff: their own stok_requests per day (last 30d)
        [$chartLabels, $chartData] = $this->getLast30DaysCounts(StokRequest::class, 'requester_id', $user->id);

        return view('dashboard.staff', compact(
            'myPinjaman','myAduan','myStok','recentPinjaman','recentAduan','notifications','chartLabels','chartData'
        ));
    }

    /**
     * Return [$labels, $data] for the last 30 days for a model.
     * $modelClass: Eloquent model class (string)
     * $filterField: optional field for filtering (e.g. 'requester_id' or 'user_id')
     * $filterValue: optional value for the above field
     */
    protected function getLast30DaysCounts(string $modelClass, string $filterField = null, $filterValue = null): array
    {
        $labels = [];
        $data = [];

        $today = Carbon::today();

        // build an array of dates (oldest -> newest)
        $dates = collect(range(0,29))->map(fn($i) => $today->copy()->subDays($i)->format('Y-m-d'))->reverse()->values();

        // query counts grouped by date
        $query = $modelClass::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereBetween('created_at', [$today->copy()->subDays(29)->startOfDay(), $today->endOfDay()]);

        if ($filterField && $filterValue !== null) {
            $query->where($filterField, $filterValue);
        }

        $rows = $query->groupBy('day')->orderBy('day')->get()->keyBy('day');

        foreach ($dates as $d) {
            $labels[] = Carbon::parse($d)->format('d M');
            $data[] = $rows->has($d) ? (int)$rows->get($d)->total : 0;
        }

        return [$labels, $data];
    }
}
