<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Entertainment\Models\EntertainmentView;
use Modules\Subscriptions\Models\SubscriptionTransactions;
use Modules\Entertainment\Models\Entertainment;
use Modules\Video\Models\Video;

use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\Subscription;
use Modules\Genres\Models\Genres;
use Modules\Entertainment\Models\Review;
use Illuminate\Http\Request;
use DB;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Fetch all regular users
        $allUsers = User::where('user_type', 'user')->get();

        // Total entertainment downloads
        $totalDownloads = EntertainmentDownload::count();

        // Total subscription transactions
        $totalTransactions = SubscriptionTransactions::count();




        // Count of new users  this month
        $startOfMonth = Carbon::now()->startOfMonth();
        $newUsersCount = User::where('user_type', 'user')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->count();
        $totalusers = $allUsers->count();
        $activeusers = $allUsers->where('status',1)->count();
        $totalSubscribers = $allUsers->where('is_subscribe',1)->count();
        $entertainments = Entertainment::where('status',1)->get();
        $totalmovies = $entertainments->where('type','movie')->count();
        $totaltvshow = $entertainments->where('type','tvshow')->count();
        $video = Video::where('status',1)->get();
        $totalvideo = $video->count();

        // Latest 4 subscription transactions
        $transactions = SubscriptionTransactions::orderBy('created_at', 'desc')
            ->take(4)
            ->get();


        //view
        $mostFrequentIds = EntertainmentView::select('entertainment_id')
            ->groupBy('entertainment_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(4)
            ->pluck('entertainment_id')
            ->toArray();

       
        $entertainments = Entertainment::whereIn('id', $mostFrequentIds)->get();

        $reviewData = Review::with('entertainment','user')->take(6)->get();

        $subscriptionData = Subscription::with('user','subscription_transaction','plan')->take(6)->get();

        return view('backend.dashboard.index', compact('allUsers', 'newUsersCount', 'totalDownloads', 'totalTransactions', 'transactions', 'entertainments','totalusers','activeusers','totalSubscribers','totalmovies','totaltvshow','totalvideo','reviewData','subscriptionData'));
    }


    public function getRevenuechartData(Request $request, $type)
    {
        $user = auth()->user();
        $userid = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($type == 'Year') {

            $monthlyTotals = SubscriptionTransactions::selectRaw('YEAR(updated_at) as year')
                ->selectRaw('MONTH(updated_at) as month')
                ->selectRaw('SUM(amount) as total_amount')
                ->where('payment_status', 'paid')
                ->groupByRaw('YEAR(updated_at), MONTH(updated_at)')
                ->orderByRaw('YEAR(updated_at), MONTH(updated_at)')
                ->get();

            $chartData = [];

            for ($month = 1; $month <= 12; $month++) {
                $found = false;
                foreach ($monthlyTotals as $total) {
                    if ((int)$total->month === $month) {
                        $chartData[] = (float)$total->total_amount;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $chartData[] = 0;
                }
            };

            $category = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                "Nov", "Dec"
            ];
        } else if ($type == 'Month') {

            $firstWeek = Carbon::now()->startOfMonth()->week;

            $monthlyWeekTotals = SubscriptionTransactions::selectRaw('YEAR(updated_at) as year, MONTH(updated_at) as month, WEEK(updated_at) as week, COALESCE(SUM(amount), 0) as total_amount')
                ->where('payment_status', 'paid')
                ->whereYear('updated_at', $currentYear)
                ->whereMonth('updated_at', $currentMonth)
                ->groupBy('year', 'month', 'week')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('week')
                ->get();

            $chartData = [];

            for ($i = $firstWeek; $i <= $firstWeek + 4; $i++) {
                $found = false;

                foreach ($monthlyWeekTotals as $total) {

                    if ((int)$total->month === $currentMonth && (int)$total->week === $i) {
                        $chartData[] = (float)$total->total_amount;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $chartData[] = 0;
                }
            }

            $category = ["Week 1", "Week 2", "Week 3", "Week 4", 'Week 5'];
        } else if ($type == 'Week') {

            $currentWeekStartDate = Carbon::now()->startOfWeek();
            $lastDayOfWeek = Carbon::now()->endOfWeek();

            $weeklyDayTotals = SubscriptionTransactions::selectRaw('DAY(updated_at) as day, COALESCE(SUM(amount), 0) as total_amount')
                ->where('payment_status', 'paid')
                ->whereYear('updated_at', $currentYear)
                ->whereMonth('updated_at', $currentMonth)
                ->whereBetween('updated_at', [$currentWeekStartDate, $currentWeekStartDate->copy()->addDays(6)])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $chartData = [];

            for ($day =  $currentWeekStartDate; $day <= $lastDayOfWeek; $day->addDay()) {
                $found = false;

                foreach ($weeklyDayTotals as $total) {
                    if ((int)$total->day === $day->day) {
                        $chartData[] = (float)$total->total_amount;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $chartData[] = 0;
                }
            };

            $category = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        }

        $data = [

            'chartData' => $chartData,
            'category' => $category

        ];

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function getSubscriberChartData(Request $request, $type)
    {
        $user = auth()->user();
        $userid = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $plans = Plan::all();

        $plans = Plan::all()->keyBy('id');

        if ($type == 'Year') {
            $monthlyTotals = Subscription::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, plan_id')
                ->selectRaw('COUNT(DISTINCT user_id) as total_subscribers')
                ->groupByRaw('YEAR(created_at), MONTH(created_at), plan_id')
                ->orderByRaw('YEAR(created_at), MONTH(created_at), plan_id')
                ->get()
                ->groupBy('plan_id');

            $chartData = [];

            foreach ($plans as $planId => $plan) {
                $planData = [];
                $planName = $plan->name;

                for ($month = 1; $month <= 12; $month++) {
                    $found = false;
                    if (isset($monthlyTotals[$planId])) {
                        foreach ($monthlyTotals[$planId] as $total) {
                            if ((int)$total->month === $month) {
                                $planData[] = $total->total_subscribers;
                                $found = true;
                                break;
                            }
                        }
                    }
                    if (!$found) {
                        $planData[] = 0;
                    }
                }

                $chartData[] = [
                    'name' => $planName,
                    'data' => $planData
                ];
            }
             
            $category = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
        } 
        else if ($type == 'Month') {

            $firstWeek = Carbon::now()->startOfMonth()->week;
        
            $monthlyWeekTotals = Subscription::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, WEEK(created_at) as week, plan_id')
                ->selectRaw('COUNT(DISTINCT user_id) as total_subscribers')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->groupBy('year', 'month', 'week', 'plan_id')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('week')
                ->get()
                ->groupBy('plan_id');
        
            $chartData = [];
        
            foreach ($plans as $planId => $plan) {
                $planData = [];
                $planName = $plan->name;
        
                for ($i = $firstWeek; $i <= $firstWeek + 4; $i++) {
                    $found = false;
                    
                    if (isset($monthlyWeekTotals[$planId])) {
                        foreach ($monthlyWeekTotals[$planId] as $total) {
                            if ((int)$total->week === $i) {
                                $planData[] = $total->total_subscribers;
                                $found = true;
                                break;
                            }
                        }
                    }
        
                    if (!$found) {
                        $planData[] = 0;
                    }
                }
        
                $chartData[] = [
                    'name' => $planName,
                    'data' => $planData
                ];
            }        

            $category = ["Week 1", "Week 2", "Week 3", "Week 4", 'Week 5'];
        } 
        else if ($type == 'Week') {

            $currentWeekStartDate = Carbon::now()->startOfWeek();
            $lastDayOfWeek = Carbon::now()->endOfWeek();
        
            $weeklyDayTotals = Subscription::selectRaw('DAY(created_at) as day, plan_id')
                ->selectRaw('COUNT(DISTINCT user_id) as total_subscribers')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->whereBetween('created_at', [$currentWeekStartDate, $lastDayOfWeek])
                ->groupBy('day', 'plan_id')
                ->orderBy('day')
                ->get();
        
            $chartData = [];
        
            foreach ($plans as $planId => $plan) {
                $planData = [];
                $planName = $plan->name;
        
                for ($day = clone $currentWeekStartDate; $day <= $lastDayOfWeek; $day->addDay()) {
                    $found = false;
        
                    foreach ($weeklyDayTotals as $total) {
                        if ((int)$total->day === $day->day && $total->plan_id == $planId) {
                            $planData[] = $total->total_subscribers;
                            $found = true;
                            break;
                        }
                    }
        
                    if (!$found) {
                        $planData[] = 0;
                    }
                }
    
                $chartData[] = [
                    'name' => $planName,
                    'data' => $planData
                ];
            }
            $category = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        }

        $data = [

            'chartData' => $chartData,
            'category' => $category

        ];

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function getGenreChartData(Request $request)
    {
        $user = auth()->user();
        $userid = $user->id;

        $genreData = Genres::withCount('entertainmentGenerMappings')->get();

        $genreNames = [];
        $entertainmentCounts = [];

        foreach ($genreData as $genre) {
            $genreNames[] = $genre->name;
            $entertainmentCounts[] = $genre->entertainment_gener_mappings_count;
        }

        $data = [

            'chartData' => $entertainmentCounts,
            'category' => $genreNames

        ];

        return response()->json(['data' => $data, 'status' => true]);
    }

    public function getMostwatchChartData(Request $request, $type)
    {
        $user = auth()->user();
        $userid = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($type == 'Year') {
            $monthlyTotals = EntertainmentView::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, entertainment_id')
                ->selectRaw('COUNT(*) as total_views')
                ->groupByRaw('YEAR(created_at), MONTH(created_at), entertainment_id')
                ->orderByRaw('YEAR(created_at), MONTH(created_at), entertainment_id')
                ->get()
                ->groupBy('entertainment_id');
        
            $chartData = [];

            $entertainmentTypes = [
                'movie' => 'Movie',
                'tvshow' => 'TV Show'
            ];
        
            foreach ($entertainmentTypes as $type => $typeName) {
                $typeData = [];
        
                for ($month = 1; $month <= 12; $month++) {
                    $totalViews = 0;
                    foreach ($monthlyTotals as $entertainmentId => $totals) {
                        $entertainment = Entertainment::find($entertainmentId);
                        if ($entertainment && $entertainment->type === $type) {
                            foreach ($totals as $total) {
                                if ((int)$total->month === $month) {
                                    $totalViews += $total->total_views;
                                }
                            }
                        }
                    }
                    $typeData[] = $totalViews;
                }
        
                $chartData[] = [
                    'name' => $typeName,
                    'data' => $typeData
                ];
            }
        
            $category = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
        }
        else if ($type == 'Month') {

            $firstWeek = Carbon::now()->startOfMonth()->week;
        
            $monthlyWeekTotals = EntertainmentView::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, WEEK(created_at) as week, entertainment_id')
                ->selectRaw('COUNT(*) as total_views')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->groupBy('year', 'month', 'week', 'entertainment_id')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('week')
                ->get()
                ->groupBy('entertainment_id');
        
            $chartData = [];
        
            $entertainmentTypes = [
                'movie' => 'Movie',
                'tvshow' => 'TV Show'
            ];
        
            foreach ($entertainmentTypes as $type => $typeName) {
                $typeData = [];
        
                for ($i = $firstWeek; $i <= $firstWeek + 4; $i++) {
                    $found = false;
                    
                    foreach ($monthlyWeekTotals as $entertainmentId => $totals) {
                        $entertainment = Entertainment::find($entertainmentId);
                        if ($entertainment && $entertainment->type === $type) {
                            foreach ($totals as $total) {
                                if ((int)$total->week === $i) {
                                    $typeData[] = $total->total_views;
                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
        
                    if (!$found) {
                        $typeData[] = 0;
                    }
                }
        
                $chartData[] = [
                    'name' => $typeName,
                    'data' => $typeData
                ];
            }        

            $category = ["Week 1", "Week 2", "Week 3", "Week 4", 'Week 5'];
        }

        $data = [

            'chartData' => $chartData,
            'category' => $category

        ];

        return response()->json(['data' => $data, 'status' => true]);
    }
}
