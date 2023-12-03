<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use Brian2694\Toastr\Facades\Toastr;
use App\CPU\Helpers;
use App\Models\Account;
use App\Models\Transection;
use function App\CPU\translate;

class CustomerController extends Controller
{
    public function __construct(
        private Customer $customer,
        private Order $order,
        private Account $account,
        private Transection $transection
    ){}

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('admin-views.customer.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'mobile'=> 'required|unique:customers',
        ]);

        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('customer/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $customer = $this->customer;
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->image = $image_name;
        $customer->state = $request->state;
        $customer->city = $request->city;
        $customer->zip_code = $request->zip_code;
        $customer->address = $request->address;
        $customer->balance = $request->balance;
        $customer->save();

        Toastr::success(translate('Customer Added successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): View|Factory|Application
    {
        $accounts = $this->account->orderBy('id')->get();
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $customers = $this->customer->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('mobile', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $customers = $this->customer;
        }
        $walk_customer = $this->customer->where('id',0)->first();
        $customers = $customers->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.list',compact('customers','accounts','search','walk_customer'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function view(Request $request, $id): View|Factory|RedirectResponse|Application
    {
        $customer = $this->customer->where('id',$id)->first();
        if(isset($customer))
        {
            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $this->order->where(['user_id' => $id])
                                    ->where(function ($q) use ($key) {
                                        foreach ($key as $value) {
                                            $q->where('id', 'like', "%{$value}%");
                                        }
                                    });
                $query_param = ['search' => $request['search']];
            } else {
                $orders = $this->order->where(['user_id' => $id]);
            }

            $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
            return view('admin-views.customer.view',compact('customer', 'orders','search'));
        }
        Toastr::error('Customer not found!');
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function transaction_list(Request $request, $id): View|Factory|RedirectResponse|Application
    {
        $accounts = $this->account->get();
        $customer = $this->customer->where('id',$id)->first();
        if(isset($customer))
        {
            $acc_id = $request['account_id'];
            $tran_type = $request['tran_type'];
            $orders = $this->order->where(['user_id' => $id])->get();
            $transactions = $this->transection->where(['customer_id' => $id])
                                ->when($acc_id!=null, function($q) use ($request){
                                    return $q->where('account_id',$request['account_id']);
                                })
                                ->when($tran_type!=null, function($q) use ($request){
                                    return $q->where('tran_type',$request['tran_type']);
                                })->latest()->paginate(Helpers::pagination_limit())
                                ->appends(['account_id' => $request['account_id'],'tran_type'=>$request['tran_type']]);
            return view('admin-views.customer.transaction-list',compact('customer', 'transactions','orders','tran_type','accounts','acc_id'));
        }
        Toastr::error(translate('Customer not found'));
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function edit(Request $request): Factory|View|Application
    {
        $customer = $this->customer->where('id',$request->id)->first();
        return view('admin-views.customer.edit',compact('customer'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $customer = $this->customer->where('id',$request->id)->first();
        $request->validate([
            'name' => 'required',
            'mobile'=> 'required|unique:customers,mobile,'.$customer->id,
        ]);

        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->image = $request->has('image') ? Helpers::update('customer/', $customer->image, 'png', $request->file('image')) : $customer->image;
        $customer->state = $request->state;
        $customer->city = $request->city;
        $customer->zip_code = $request->zip_code;
        $customer->address = $request->address;
        $customer->balance = $request->balance;
        $customer->save();

        Toastr::success(translate('Customer updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $customer = $this->customer->find($request->id);
        Helpers::delete('customer/' . $customer['image']);
        $customer->delete();

        Toastr::success(translate('Customer removed successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_balance(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id'=>'required',
            'amount' => 'required',
            'account_id'=> 'required',
            'date' => 'required',
        ]);
        $customer = $this->customer->find($request->customer_id);

        if($customer->balance >= 0)
        {
            $account = Account::find(2);
            $transection = new Transection;
            $transection->tran_type = 'Payable';
            $transection->account_id = $account->id;
            $transection->amount = $request->amount;
            $transection->description = $request->description;
            $transection->debit = 0;
            $transection->credit = 1;
            $transection->balance = $account->balance + $request->amount;
            $transection->date = $request->date;
            $transection->customer_id = $request->customer_id;
            $transection->save();

            $account->total_in = $account->total_in + $request->amount;
            $account->balance = $account->balance + $request->amount;
            $account->save();

            $receive_account = Account::find($request->account_id);
            $receive_transection = new Transection;
            $receive_transection->tran_type = 'Income';
            $receive_transection->account_id = $receive_account->id;
            $receive_transection->amount = $request->amount;
            $receive_transection->description = $request->description;
            $receive_transection->debit = 0;
            $receive_transection->credit = 1;
            $receive_transection->balance = $receive_account->balance + $request->amount;
            $receive_transection->date = $request->date;
            $receive_transection->customer_id = $request->customer_id;
            $receive_transection->save();

            $receive_account->total_in = $receive_account->total_in + $request->amount;
            $receive_account->balance = $receive_account->balance + $request->amount;
            $receive_account->save();
        }else{
            $remaining_balance = $customer->balance + $request->amount;

            if($remaining_balance >= 0)
            {
                if($remaining_balance!=0)
                {
                    $payable_account = Account::find(2);
                    $payable_transection = new Transection;
                    $payable_transection->tran_type = 'Payable';
                    $payable_transection->account_id = $payable_account->id;
                    $payable_transection->amount = $remaining_balance;
                    $payable_transection->description = $request->description;
                    $payable_transection->debit = 0;
                    $payable_transection->credit = 1;
                    $payable_transection->balance = $payable_account->balance + $remaining_balance;
                    $payable_transection->date = $request->date;
                    $payable_transection->customer_id = $request->customer_id;
                    $payable_transection->save();

                    $payable_account->total_in = $payable_account->total_in + $remaining_balance;
                    $payable_account->balance = $payable_account->balance + $remaining_balance;
                    $payable_account->save();
                }

                $receive_account = Account::find($request->account_id);
                $receive_transection = new Transection;
                $receive_transection->tran_type = 'Income';
                $receive_transection->account_id = $request->account_id;
                $receive_transection->amount = $request->amount;
                $receive_transection->description = $request->description;
                $receive_transection->debit = 0;
                $receive_transection->credit = 1;
                $receive_transection->balance = $receive_account->balance + $request->amount;
                $receive_transection->date = $request->date;
                $receive_transection->customer_id = $request->customer_id;
                $receive_transection->save();

                $receive_account->total_in = $receive_account->total_in + $request->amount;
                $receive_account->balance = $receive_account->balance + $request->amount;
                $receive_account->save();


                $receivable_account = Account::find(3);
                $receivable_transaction = new Transection;
                $receivable_transaction->tran_type = 'Receivable';
                $receivable_transaction->account_id = $receivable_account->id;
                $receivable_transaction->amount = -$customer->balance;
                $receivable_transaction->description = 'update customer balance';
                $receivable_transaction->debit = 1;
                $receivable_transaction->credit = 0;
                $receivable_transaction->balance = $receivable_account->balance + $customer->balance;
                $receivable_transaction->date = $request->date;
                $receivable_transaction->customer_id = $request->customer_id;
                $receivable_transaction->save();

                $receivable_account->total_out = $receivable_account->total_out - $customer->balance;
                $receivable_account->balance = $receivable_account->balance + $customer->balance;
                $receivable_account->save();

            }else{

                $receive_account = Account::find($request->account_id);
                $receive_transection = new Transection;
                $receive_transection->tran_type = 'Income';
                $receive_transection->account_id = $receive_account->id;
                $receive_transection->amount = $request->amount;
                $receive_transection->description = $request->description;
                $receive_transection->debit = 0;
                $receive_transection->credit = 1;
                $receive_transection->balance = $receive_account->balance + $request->amount;
                $receive_transection->date = $request->date;
                $receive_transection->customer_id = $request->customer_id;
                $receive_transection->save();

                $receive_account->total_in = $receive_account->total_in + $request->amount;
                $receive_account->balance = $receive_account->balance + $request->amount;
                $receive_account->save();

                $receivable_account = Account::find(3);
                $receivable_transaction = new Transection;
                $receivable_transaction->tran_type = 'Receivable';
                $receivable_transaction->account_id = $receivable_account->id;
                $receivable_transaction->amount = $request->amount;
                $receivable_transaction->description = 'update customer balance';
                $receivable_transaction->debit = 1;
                $receivable_transaction->credit =0;
                $receivable_transaction->balance = $receivable_account->balance - $request->amount;
                $receivable_transaction->date = $request->date;
                $receivable_transaction->customer_id = $request->customer_id;
                $receivable_transaction->save();

                $receivable_account->total_out = $receivable_account->total_out + $request->amount;
                $receivable_account->balance = $receivable_account->balance - $request->amount;
                $receivable_account->save();
            }

        }
        $customer->balance = $customer->balance + $request->amount;
        $customer->save();

        Toastr::success(translate('Customer balance updated successfully'));
        return back();
    }
}
