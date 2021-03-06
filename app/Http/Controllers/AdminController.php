<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Transaction;
use App\AdminNotifications;
use App\UserNotifications;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now = Carbon::now();
        $allTransactions = Transaction::where('status', 'success')->get();
        $allSales = Transaction::where('status', 'success')->count();;
        $monthlyTransactions = Transaction::where('status', 'success')->whereMonth('created_at', $now->month)->get();
        $annualTranscations = Transaction::where('status', 'success')->whereYear('created_at', $now->year)->get();
        $incomeTotal = 0;
        $incomeMonthly = 0;
        $incomeAnnual = 0;

        foreach ($allTransactions as $transaction) {
            $incomeTotal+=$transaction->total;
        }

        
        foreach ($monthlyTransactions as $monthly) {
            $incomeMonthly+=$monthly->total;
        }

        foreach ($annualTranscations as $annual) {
            $incomeAnnual+=$annual->total;
        }

        return view('admin.admindashboard', compact('now', 'allSales', 'incomeTotal', 'incomeMonthly', 'incomeAnnual'));
    }

    public function adminNotif($id) 
    {
        $notification = AdminNotifications::find($id);
        $notif = json_decode($notification->data);
        $date = Carbon::now('Asia/Makassar');
        AdminNotifications::where('id', $id)
                ->update([
                    'read_at' => $date
                ]);
        
        if ($notif->category == 'transaction') {
            return redirect()->route('transactions.detail', $notif->id);
        } elseif ($notif->category == 'review') {
            return redirect('/products');
        } 
    }
    public function laporan()
    {
        $now = Carbon::now();
        $allTransactions = Transaction::where('status', 'success')->get();
        $allSales = Transaction::where('status', 'success')->count();;
        $monthlyTransactions = Transaction::where('status', 'success')->whereMonth('created_at', $now->month)->get();
        $annualTranscations = Transaction::where('status', 'success')->whereYear('created_at', $now->year)->get();
        $report = Transaction_Detail::select(
            "products.product_name as produk", 
            "transactions.created_at as tanggal",
            "transactions.province as lokasi", 
            "transactions.sub_total as sub",
            "transactions.shipping_cost as ongkir",
            "transactions.total as total", 
            "transactions.status as status",   
        )
        ->join("products", "products.id", "=", "transaction_details.product_id")
        ->join("transactions", "transactions.id", "=", "transaction_details.transaction_id")
        ->get();
        // dd($report);
        $incomeTotal = 0;
        $incomeMonthly = 0;
        $incomeAnnual = 0;

        foreach ($allTransactions as $transaction) {
            $incomeTotal+=$transaction->total;
        }

        
        foreach ($monthlyTransactions as $monthly) {
            $incomeMonthly+=$monthly->total;
        }

        foreach ($annualTranscations as $annual) {
            $incomeAnnual+=$annual->total;
        }

        return view('admin.laporan', compact('now', 'allSales', 'incomeTotal', 'incomeMonthly', 'incomeAnnual','report'));
    }
}
