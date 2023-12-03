<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Account;
use function App\CPU\translate;

class AccountController extends Controller
{
    public function __construct(
        private Account $account
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->account->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('account', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->account;
        }

        $accounts = $query->orderBy('id','desc')->paginate(Helpers::pagination_limit());
        return view('admin-views.account.list', compact('accounts','search'));
    }

    /**
     * @return Application|Factory|View
     */
    public function add(): Factory|View|Application
    {
        return view('admin-views.account.add');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account' => 'required|unique:accounts,account',
            'balance'=> 'required',
            'account_number' => 'required|unique:accounts',
        ]);

        $account = $this->account;
        $account->account = $request->account;
        $account->description = $request->description;
        $account->balance = $request->balance;
        $account->account_number = $request->account_number;
        $account->save();

        Toastr::success(translate('New Account Added successfully'));
        return redirect()->route('admin.account.list');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $account = $this->account->find($id);
        return view('admin-views.account.edit', compact('account'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $account = $this->account->find($id);
        $request->validate([
            'account' => 'required|unique:accounts,account,'.$account->id,
            'account_number' => 'required|unique:accounts,account_number,'.$account->id,
        ]);

        $account->account = $request->account;
        $account->account_number = $request->account_number;
        $account->description = $request->description;
        $account->save();
        Toastr::success(translate('Account updated successfully'));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $account = $this->account->find($id);
        $account->delete();

        Toastr::success(translate('Account deleted successfully'));
        return back();
    }

}
