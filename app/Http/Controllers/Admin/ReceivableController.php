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

class ReceivableController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function add(Request $request): View|Factory|Application
    {
        $accounts = $this->account->orderBy('id')->get();
        $search = $request['search'];
        $from = $request->from;
        $to = $request->to;
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->transection->where('tran_type','Receivable')->
                    where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('description', 'like', "%{$value}%");
                        }
                });
            $query_param = ['search' => $request['search']];
        }else
         {
            $query = $this->transection->where('tran_type','Receivable')
                                ->when($from!=null, function($q) use ($request){
                                     return $q->whereBetween('date', [$request['from'], $request['to']]);
            });

         }
        $receivables = $query->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.account-receivable.add',compact('accounts','receivables','search','from','to'));
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
            'date' =>'required',
        ]);

        $account = $this->account->find($request->account_id);
        $transection = $this->transection;
        $transection->tran_type = 'Receivable';
        $transection->account_id = $request->account_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 0;
        $transection->credit = 1;
        $transection->balance =  $account->balance + $request->amount;
        $transection->date = $request->date;
        $transection->save();

        $account->total_in = $account->total_in + $request->amount;
        $account->balance = $account->balance + $request->amount;
        $account->save();

        Toastr::success(translate('Receivable Balance Added successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function transfer(Request $request): RedirectResponse
    {
        $receivable_account = $this->account->find($request->account_id);
        $receivable_transection = $this->transection->find($request->transection_id);
        $balance = $receivable_transection->amount - $request->amount;
        if($balance < 0){
            Toastr::warning(translate('You have not sufficient balance for this transaction'));
            return back();
        }
        $receivable_transection->amount = $balance;
        $receivable_transection->balance = $receivable_transection->balance - $request->amount;
        $receivable_transection->save();

        $receivable_account->total_out = $receivable_account->total_out + $request->amount;
        $receivable_account->balance = $receivable_account->balance - $request->amount;
        $receivable_account->save();

        $receive_account = $this->account->find($request->receive_account_id);
        $transection = $this->transection;
        $transection->tran_type = 'Income';
        $transection->account_id = $request->receive_account_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 0;
        $transection->credit = 1;
        $transection->balance =  $receive_account->balance + $request->amount;
        $transection->date = $request->date;
        $transection->save();

        $receive_account->total_in = $receive_account->total_in + $request->amount;
        $receive_account->balance = $receive_account->balance + $request->amount;
        $receive_account->save();

        Toastr::success(translate('Payable Balance pay successfully'));
        return back();

    }
}
