<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsComment;
use App\Models\NewsViewer;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Finance;



class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
            ],


        ];
        return view('back.pages.dashboard.index', $data);
    }

    public function visistorStat()
    {


        $data = cache()->remember('visitor_stats', 60, function () {
            return [
                'visitor_monthly' => Visitor::select(DB::raw('Date(created_at) as date'), DB::raw('count(*) as total'))
                    ->orderBy('date', 'desc')
                    ->limit(30)
                    ->groupBy('date')
                    ->get(),
                'visitor_platfrom' => Visitor::select('platform', DB::raw('count(*) as total'))
                    ->groupBy('platform')
                    ->get(),
                'visitor_browser' => Visitor::select('browser', DB::raw('count(*) as total'))
                    ->groupBy('browser')
                    ->get(),
                'visitor_country' => Visitor::select('country', DB::raw('count(*) as total'))
                    ->whereNotNull('country')
                    ->groupBy('country')
                    ->orderBy('total', 'desc')
                    ->get()
                    ->map(function ($item) {
                        $countryName = $item->country;

                        $hash = substr(md5($countryName), 0, 6);
                        $item->color = "#{$hash}";
                        return $item;
                    }),
            ];
        });
        return response()->json($data);
    }

    public function news()
    {
        $data = [
            'title' => 'Dashboard Berita',
            'menu' => 'dashboard',
            'sub_menu' => '',
            'total_news' => News::count(),
            'total_views' => NewsViewer::count(),
            'total_comments' => NewsComment::count(),
            'total_published' => News::where('status', 'published')->count(),
            'total_draft' => News::where('status', 'draft')->count(),
            'news_popular' => News::with('comments')->withCount('viewers')->orderBy('viewers_count', 'desc')->limit(5)->get(),
            'news_new' => News::with(['comments', 'viewers'])->latest()->limit(5)->get(),
            'news_writer' => News::select(
                DB::raw('count(*) as total'),
                'news.user_id',
            )
                ->join('users', 'users.id', '=', 'news.user_id')
                ->addSelect('users.name', 'users.email')
                ->groupBy('news.user_id', 'users.name', 'users.email')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
            'news_by_category' => News::select(
                DB::raw('count(*) as total'),
                'news_categories.name as category_name',
            )
                ->join('news_categories', 'news_categories.id', '=', 'news.news_category_id')
                ->groupBy('news.news_category_id', 'news_categories.name')
                ->orderBy('total', 'desc')
                ->limit(8)
                ->get(),
        ];
        return view('back.pages.dashboard.news', $data);
    }

    public function stat()
    {


        $data = [
            'news_viewer_monthly' => NewsViewer::select(DB::raw('Date(created_at) as date'), DB::raw('count(*) as total'))
                ->limit(30)
                ->groupBy('date')
                ->get(),
            'news_viewer_platfrom' => NewsViewer::select('platform', DB::raw('count(*) as total'))
                ->groupBy('platform')
                ->get(),
            'news_viewer_browser' => NewsViewer::select('browser', DB::raw('count(*) as total'))
                ->groupBy('browser')
                ->get(),

        ];
        return response()->json($data);
    }

    public function cashFlow()
    {
        $data = [
            'title' => 'Dashboard Cashflow',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Cashflow',
                    'link' => route('back.dashboard.cashflow')
                ]
            ]
        ];
        return view('back.pages.dashboard.cashflow', $data);
    }

    public function cashflowStat()
    {
        try {
            $data = cache()->remember('cashflow_stats', 60, function () {
                // Use current year as date range
                $startDate = now()->startOfYear()->toDateString();
                $endDate = now()->addDay()->toDateString();

                // Monthly cashflow data
                $monthlyData = Finance::select(
                    DB::raw('DATE(date) as date'),
                    DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                    DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
                )
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->groupBy(DB::raw('DATE(date)'))
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();

                // Merge and process monthly data
                $mergedMonthly = $monthlyData->map(function ($item) {
                    $totalIncome = (int)$item->income;
                    $expense = (int)$item->expense;

                    return [
                        'date' => $item->date,
                        'income' => $totalIncome,
                        'expense' => $expense,
                        'balance' => $totalIncome - $expense
                    ];
                });

                // Transaction type distribution
                $transactionTypes = Finance::select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                    ->where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->groupBy('type')
                    ->get();

                // Recent transactions
                $recentTransactions = Finance::where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->orderBy('date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                // Summary totals
                $totalIncome = Finance::where('type', 'income')
                    ->where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->sum('amount');

                $totalPaymentIncome = 0;

                $totalExpense = Finance::where('type', 'expense')
                    ->where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->sum('amount');

                // Transaction counts
                $totalTransactionCount = Finance::where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->count();

                $monthlyTransactionCount = Finance::where('date', '>=', now()->startOfMonth())
                    ->where('date', '<=', now()->endOfMonth())
                    ->count();

                // Top expense categories (by name keywords)
                $topExpenses = Finance::where('type', 'expense')
                    ->where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->select('name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                    ->groupBy('name')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get()
                    ->toArray();

                // Payment method distribution
                $paymentMethods = Finance::where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate)
                    ->whereNotNull('payment_method')
                    ->where('payment_method', '!=', '')
                    ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                    ->groupBy('payment_method')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray();

                // Monthly aggregated data (for bar chart)
                $monthlyAggregated = Finance::select(
                        DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                        DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                        DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
                    )
                    ->where('date', '>=', now()->subMonths(12)->startOfMonth())
                    ->where('date', '<=', $endDate)
                    ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
                    ->orderBy('month', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'month' => $item->month,
                            'income' => (int)$item->income,
                            'expense' => (int)$item->expense,
                            'balance' => (int)($item->income - $item->expense),
                        ];
                    })
                    ->toArray();

                // Daily average (current month)
                $daysInMonth = now()->day;
                $currentMonthIncome = Finance::where('type', 'income')
                    ->where('date', '>=', now()->startOfMonth())
                    ->where('date', '<=', now())
                    ->sum('amount');
                $currentMonthExpense = Finance::where('type', 'expense')
                    ->where('date', '>=', now()->startOfMonth())
                    ->where('date', '<=', now())
                    ->sum('amount');

                // Previous month totals (for MoM comparison)
                $prevMonthIncome = Finance::where('type', 'income')
                    ->where('date', '>=', now()->subMonth()->startOfMonth())
                    ->where('date', '<=', now()->subMonth()->endOfMonth())
                    ->sum('amount');
                $prevMonthExpense = Finance::where('type', 'expense')
                    ->where('date', '>=', now()->subMonth()->startOfMonth())
                    ->where('date', '<=', now()->subMonth()->endOfMonth())
                    ->sum('amount');

                // Pending invoices count
                try {
                    $pendingInvoiceCount = \App\Models\PaymentInvoice::where('payment_status', 'pending')->count();
                } catch (\Exception $e) {
                    $pendingInvoiceCount = 0;
                }

                return [
                    'monthly_cashflow' => $mergedMonthly->values()->toArray(),
                    'monthly_aggregated' => $monthlyAggregated,
                    'transaction_types' => $transactionTypes->toArray(),

                    'recent_transactions' => $recentTransactions->toArray(),
                    'top_expenses' => $topExpenses,
                    'payment_methods' => $paymentMethods,
                    'summary' => [
                        'total_income' => (int)($totalIncome + $totalPaymentIncome),
                        'total_expense' => (int)$totalExpense,
                        'total_balance' => (int)(($totalIncome + $totalPaymentIncome) - $totalExpense),
                        'finance_income' => (int)$totalIncome,
                        'payment_income' => (int)$totalPaymentIncome,
                        'transaction_count' => $totalTransactionCount,
                        'monthly_transactions' => $monthlyTransactionCount,
                        'daily_avg_income' => $daysInMonth > 0 ? (int)($currentMonthIncome / $daysInMonth) : 0,
                        'daily_avg_expense' => $daysInMonth > 0 ? (int)($currentMonthExpense / $daysInMonth) : 0,
                        'prev_month_income' => (int)$prevMonthIncome,
                        'prev_month_expense' => (int)$prevMonthExpense,
                        'current_month_income' => (int)$currentMonthIncome,
                        'current_month_expense' => (int)$currentMonthExpense,
                        'pending_invoices' => $pendingInvoiceCount,
                    ]
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load cashflow data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
