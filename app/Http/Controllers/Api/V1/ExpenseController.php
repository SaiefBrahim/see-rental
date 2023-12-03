<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getExpense(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $expenses = $this->transection->with('account')->where('tran_type', '=', 'Expense')->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total' => $expenses->total(),
            'limit' => $limit,
            'offset' => $offset,
            'expenses' => $expenses->items(),
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @param Transection $expense
     * @return JsonResponse
     */
    public function storeExpenses(Request $request, Transection $expense): JsonResponse
    {
        $request->validate([
            'account_id' => 'required',
            'description' => 'required',
            'amount' => 'required|min:1',
        ]);
        try {
            $account = $this->account->find($request->account_id);
            if ($account->balance < $request->amount) {
                return response()->json(['success' => false, 'message' => 'You do not have sufficent balance'], 400);
            }
            $expense->tran_type = "Expense";
            $expense->account_id = $request->account_id;
            $expense->amount = $request->amount;
            $expense->description = $request->description;
            $expense->debit = 1;
            $expense->credit = 0;
            $expense->date = $request->date;
            $expense->save();
            //Now reduce amount form account after saving expense
            $account->total_out = $account->total_out + $request->amount;
            $account->balance = $account->balance - $request->amount;
            $account->save();
            return response()->json([
                'success' => true,
                'message' => 'Expenses saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Expenses not saved',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTransfer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required',
            'description' => 'required',
            'amount' => 'required|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $accTransfaree = $this->account->findOrFail($request->account_id);
        if ($accTransfaree->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance',
            ], 400);
        } elseif ($request->amount == 0) {
            return response()->json([
                'success' => false,
                'message' => 'You are not able to transfer this amount',
            ], 400);
        }
        try {
            $transfer = $this->transection;
            $transfer->tran_type = 'Transfer';
            $transfer->account_id = $request->account_id;
            $transfer->amount = $request->amount;
            $transfer->description = $request->description;
            $transfer->debit = 1;
            $transfer->credit = 0;
            $transfer->balance = $accTransfaree->balance - $request->amount;
            $transfer->date = $request->date;
            $transfer->save();

            $accTransfaree->total_out = $accTransfaree->total_out + $request->amount;
            $accTransfaree->balance = $accTransfaree->balance - $request->amount;
            $accTransfaree->save();
            return response()->json([
                'success' => false,
                'message' => 'Transfer successfull',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer unsuccessfull',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSearch(Request $request): JsonResponse
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
            $result = $this->transection->when(($request->from && $request->to), function ($query) use ($request) {
                $query->whereBetween('date', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
            })->where('tran_type', '=', 'Expense')->latest()->paginate($limit, ['*'], 'page', $offset);

            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'expenses' => $result->items(),
            ];
        } else {
            $data = [
                'total' => 0,
                'limit' => $limit,
                'offset' => $offset,
                'expenses' => [],
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transferList(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $transfers = $this->transection->with('account')->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total' => $transfers->total(),
            'limit' => $limit,
            'offset' => $offset,
            'transfers' => $transfers->items(),
        ];
        return response()->json($data, 200);
    }
}
