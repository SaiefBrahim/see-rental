<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Income;
use App\Models\Account;
use App\Models\Transection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\CPU\Helpers;

class IncomeController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $incomes = $this->transection->with('account')->where('tran_type', '=', 'Income')->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total' => $incomes->total(),
            'limit' => $limit,
            'offset' => $offset,
            'incomes' => $incomes->items(),
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getFilter(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        if (!empty($request->from && $request->to)) {
            $result = $this->transection->with('account')->when(($request->from && $request->to), function ($query) use ($request) {
                $query->whereBetween('date', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
            })->where('tran_type', '=', 'Income')->latest()->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'incomes' => $result->items(),
            ];
        } else {
            $data = [
                'total' => 0,
                'limit' => $limit,
                'offset' => $offset,
                'transfers' => [],
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function newIncome(Request $request): JsonResponse
    {
        $request->validate([
            'account_id' => 'required',
            'description' => 'required',
            'amount' => 'required|min:1',
            'date' => 'required',
        ]);

        $account = $this->account->find($request->account_id);
        $transection = $this->transection;
        $transection->tran_type = 'Income';
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
        return response()->json(
            ['success' => true, 'message' => 'New Income Added successfully'],
            200
        );
    }
}
