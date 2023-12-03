<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Transection;
use App\CPU\Helpers;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Pagination\Paginator;

class DashboardController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
        private Product $product
    ){}

    /**
     * @return Application|Factory|View
     */
    public function dashboard(): Factory|View|Application
    {
        $total_payable_debit = $this->transection->where('tran_type','Payable')->where('debit',1)->sum('amount');
        $total_payable_credit = $this->transection->where('tran_type','Payable')->where('credit',1)->sum('amount');
        $total_payable = $total_payable_credit - $total_payable_debit;

        $total_receivable_debit = $this->transection->where('tran_type','Receivable')->where('debit',1)->sum('amount');
        $total_receivable_credit = $this->transection->where('tran_type','Receivable')->where('credit',1)->sum('amount');
        $total_receivable = $total_receivable_credit - $total_receivable_debit;

        $account = [
            'total_income' => $this->transection->where('tran_type','Income')->sum('amount'),
            'total_expense' => $this->transection->where('tran_type','Expense')->sum('amount'),
            'total_payable' => $total_payable,
            'total_receivable' => $total_receivable,
        ];
        $monthly_income=[];
        for ($i=1;$i<=12;$i++){
            $from = date('Y-'.$i.'-01');
            $to = date('Y-'.$i.'-30');
            $monthly_income[$i] = $this->transection->where(['tran_type'=>'Income'])->whereBetween('date', [$from, $to])->sum('amount');
        }
        $monthly_expense=[];
        for ($i=1;$i<=12;$i++){
            $from = date('Y-'.$i.'-01');
            $to = date('Y-'.$i.'-30');
            $monthly_expense[$i] = $this->transection->where(['tran_type'=>'Expense'])->whereBetween('date', [$from, $to])->sum('amount');
        }
        $month = date('t');
        $first_day = strtotime(date('Y-m-1'));
        $curr_day = strtotime(date('Y-m-d'));
        //$total_day= ($curr_day - $first_day) / 86400 + 1;

        $total_day = Carbon::now()->daysInMonth;

        $last_month_income=[];
        for($i=1;$i<=$total_day;$i++){
            $day = date('Y-m-'.$i);
            $last_month_income[$i] = $this->transection->where(['tran_type'=>'Income'])->where('date', $day)->sum('amount');
        }

        $last_month_expense=[];
        for($i=1;$i<=$total_day;$i++){
            $day = date('Y-m-'.$i);
            $last_month_expense[$i] = $this->transection->where(['tran_type'=>'Expense'])->where('date', $day)->sum('amount');
        }
        //dd($last_month_income);
        $stock_limit = Helpers::get_business_settings('stock_limit');

        //$products = $this->product->where('quantity','<',$stock_limit)->orderBy('quantity')->paginate(Helpers::pagination_limit(), ['*'], 'product');
        $products = $this->product->where('quantity','<',$stock_limit)->orderBy('quantity')->take(5)->get();
        //$accounts = $this->account->latest()->paginate(Helpers::pagination_limit(), ['*'], 'account');
        $accounts = $this->account->take(5)->get();


        return view('admin-views.dashboard',compact('account','monthly_income','monthly_expense','accounts','products','last_month_income','last_month_expense','month','total_day'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function account_stats(Request $request): JsonResponse
    {
        if($request->statistics_type=='overall')
        {
            $total_payable_debit = $this->transection->where('tran_type','Payable')->where('debit',1)->sum('amount');
            $total_payable_credit = $this->transection->where('tran_type','Payable')->where('credit',1)->sum('amount');
            $total_payable = $total_payable_credit - $total_payable_debit;

            $total_receivable_debit = $this->transection->where('tran_type','Receivable')->where('debit',1)->sum('amount');
            $total_receivable_credit = $this->transection->where('tran_type','Receivable')->where('credit',1)->sum('amount');
            $total_receivable = $total_receivable_credit - $total_receivable_debit;

            $account = [
                'total_income' => $this->transection->where('tran_type','Income')->sum('amount'),
                'total_expense' => $this->transection->where('tran_type','Expense')->sum('amount'),
                'total_payable' => $total_payable,
                'total_receivable' => $total_receivable,
            ];
        }elseif ($request->statistics_type=='today') {

            $total_payable_debit = $this->transection->where('tran_type','Payable')->whereDay('date', '=', Carbon::today())->where('debit',1)->sum('amount');
            $total_payable_credit = $this->transection->where('tran_type','Payable')->whereDay('date', '=', Carbon::today())->where('credit',1)->sum('amount');
            $total_payable = $total_payable_credit - $total_payable_debit;

            $total_receivable_debit = $this->transection->where('tran_type','Receivable')->whereDay('date', '=', Carbon::today())->where('debit',1)->sum('amount');
            $total_receivable_credit = $this->transection->where('tran_type','Receivable')->whereDay('date', '=', Carbon::today())->where('credit',1)->sum('amount');
            $total_receivable = $total_receivable_credit - $total_receivable_debit;

            $account = [
                'total_income' => $this->transection->where('tran_type','Income')->whereDay('date', '=', Carbon::today())->sum('amount'),
                'total_expense' => $this->transection->where('tran_type','Expense')->whereDay('date', '=', Carbon::today())->sum('amount'),
                'total_payable' => $total_payable,
                'total_receivable' => $total_receivable,
            ];
        }elseif ($request->statistics_type=='month') {

            $total_payable_debit = $this->transection->where('tran_type','Payable')->whereMonth('date', '=', Carbon::today())->where('debit',1)->sum('amount');
            $total_payable_credit = $this->transection->where('tran_type','Payable')->whereMonth('date', '=', Carbon::today())->where('credit',1)->sum('amount');
            $total_payable = $total_payable_credit - $total_payable_debit;

            $total_receivable_debit = $this->transection->where('tran_type','Receivable')->whereMonth('date', '=', Carbon::today())->where('debit',1)->sum('amount');
            $total_receivable_credit = $this->transection->where('tran_type','Receivable')->whereMonth('date', '=', Carbon::today())->where('credit',1)->sum('amount');
            $total_receivable = $total_receivable_credit - $total_receivable_debit;

            $account = [
                'total_income' => $this->transection->where('tran_type','Income')->whereMonth('date', '=', Carbon::today())->sum('amount'),
                'total_expense' => $this->transection->where('tran_type','Expense')->whereMonth('date', '=', Carbon::today())->sum('amount'),
                'total_payable' => $total_payable,
                'total_receivable' => $total_receivable,
            ];
        }
        return response()->json([
            'view'=> view('admin-views.partials._dashboard-balance-stats',compact('account'))->render()
        ],200);
    }

}
