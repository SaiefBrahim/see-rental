<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transection;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class PayableController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function add(Request $request): Factory|View|Application
    {
        $accounts = $this->account->orderBy('id')->get();
        $search = $request['search'];
        $from = $request->from;
        $to = $request->to;
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->transection->where('tran_type','Payable')->
                    where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('description', 'like', "%{$value}%");
                        }
                });
            $query_param = ['search' => $request['search']];
        }else
         {
            $query = $this->transection->where('tran_type','Payable')
                                ->when($from!=null, function($q) use ($request){
                                     return $q->whereBetween('date', [$request['from'], $request['to']]);
            });

         }
        $payables = $query->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.account-payable.add',compact('accounts','payables','search','from','to'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_id' => 'required',
            'description'=> 'required',
            'amount' => 'required',
        ]);

        $account = $this->account->find($request->account_id);
        $transection = $this->transection;
        $transection->tran_type = 'Payable';
        $transection->account_id = $request->account_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 1;
        $transection->credit = 0;
        $transection->balance =  $account->balance + $request->amount;
        $transection->date = $request->date;
        $transection->save();

        $account->total_in = $account->total_in + $request->amount;
        $account->balance = $account->balance + $request->amount;
        $account->save();

        Toastr::success(translate('Payable Balance Added successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function transfer(Request $request): RedirectResponse
    {
        $payment_account = $this->account->find($request->payment_account_id);
        $remain_balance = $payment_account->balance - $request->amount;
        if($remain_balance < 0)
        {
            Toastr::warning(translate('Your payment account has not sufficent balance for this transaction'));
            return back();
        }
        $payable_account = $this->account->find($request->account_id);
        $payable_transection = $this->transection->find($request->transection_id);
        $balance = $payable_transection->amount - $request->amount;
        if($balance < 0){
            Toastr::warning(translate('You have not sufficient balance for this transaction'));
            return back();
        }
        $payable_transection->amount = $balance;
        $payable_transection->balance = $payable_transection->balance - $request->amount;
        $payable_transection->save();

        $payable_account->total_out = $payable_account->total_out + $request->amount;
        $payable_account->balance = $payable_account->balance - $request->amount;
        $payable_account->save();

        $transection = $this->transection;
        $transection->tran_type = 'Expense';
        $transection->account_id = $request->payment_account_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 1;
        $transection->credit = 0;
        $transection->balance =  $payment_account->balance - $request->amount;
        $transection->date = $request->date;
        $transection->save();

        $payment_account->total_out = $payment_account->total_out + $request->amount;
        $payment_account->balance = $payment_account->balance - $request->amount;
        $payment_account->save();

        Toastr::success(translate('Payable Balance pay successfully'));
        return back();
    }
}
