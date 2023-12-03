<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Account;
use App\Models\Transection;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function __construct(
        private Account $account,
        private Transection $transection,
    ){}

    public function getIndex(Request $request)
    {
        $transactions = $this->transection->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $data = [
            'total' => $transactions->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'transactions' => $transactions
        ];
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Transection $transaction
     * @return JsonResponse
     */
    public function storeExpenses(Request $request, Transection $transaction): JsonResponse
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
            $transaction->tran_type = "Expense";
            $transaction->account_id = $request->account_id;
            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->debit = 0;
            $transaction->credit = 0;
            $transaction->date = $request->date;
            $transaction->save();

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
                'message' => 'Expenses not saved'
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fundTransfer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account_from_id' => 'required',
            'account_to_id' => 'required',
            'description' => 'required',
            'amount' => 'required|min:1',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $acc_from = Account::find($request->account_from_id);
        if ($acc_from->balance < $request->amount) {
            return response()->json([
                'message' => 'You have not sufficient balance',
            ], 203);
        }
        $transection = new Transection();
        $transection->tran_type = 'Transfer';
        $transection->account_id = $request->account_from_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 1;
        $transection->credit = 0;
        $transection->balance = $acc_from->balance - $request->amount;
        $transection->date = $request->date;
        $transection->save();


        $acc_from->total_out = $acc_from->total_out + $request->amount;
        $acc_from->balance = $acc_from->balance - $request->amount;
        $acc_from->save();

        $acc_to = Account::find($request->account_to_id);
        $transection = new Transection();
        $transection->tran_type = 'Transfer';
        $transection->account_id = $request->account_to_id;
        $transection->amount = $request->amount;
        $transection->description = $request->description;
        $transection->debit = 0;
        $transection->credit = 1;
        $transection->balance = $acc_to->balance + $request->amount;
        $transection->date = $request->date;
        $transection->save();

        $acc_to->total_in = $acc_to->total_in + $request->amount;
        $acc_to->balance = $acc_to->balance + $request->amount;
        $acc_to->save();

        return response()->json([
            'message' => 'New Deposit Added successfully',
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transactionFilter(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $transactions = $this->transection->when($request->has('account_id'), function ($query) use ($request) {
            $query->where('account_id', $request->account_id);
        })->when($request->has('tran_type'), function ($query) use ($request) {
            $query->where('tran_type', $request->tran_type);
        })->when($request->has('from') && $request->has('to'), function ($query) use ($request) {
            $query->whereBetween('date', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
        })->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $data = [
            'total' => $transactions->total(),
            'limit' => $limit,
            'offset' => $offset,
            'transfers' => $transactions->items()
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transferAccounts(Request $request): JsonResponse
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        if (isset($request->customer_balance)) {
            $accounts = $this->account->orderBy('id')->where('id', '!=', 2)->where('id', '!=', 3)->paginate($request['limit'], ['*'], 'page', $request['offset']);
            $data = [
                'limit' => $limit,
                'offset' => $offset,
                'accounts' => $accounts->items(),
                'customer_balance' => [
                    'id' => 0,
                    'account' => 'Customer Balance'
                ]
            ];
        } else {
            $accounts = $this->account->orderBy('id')->where('id', '!=', 2)->where('id', '!=', 3)->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
            $data = [
                'total' => $accounts->total(),
                'limit' => $limit,
                'offset' => $offset,
                'accounts' => $accounts->items(),
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function transferListExport(Request $request): StreamedResponse|string
    {
        if ($request->account_id) {
            $transactions = $this->transection->where('account_id', $request->account_id)->latest()->get();
        } elseif ($request->transaction_type) {
            $transactions = $this->transection->where('tran_type', $request->transaction_type)->latest()->get();
        } elseif ($request->from && $request->to) {
            $transactions = $this->transection->whereBetween('date', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])->latest()->get();
        } else {
            $transactions = $this->transection->where('tran_type', 'Transfer')->get();
        }
        return (new FastExcel($transactions))->download('transactions_list.xlsx');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transactionTypes(Request $request): JsonResponse
    {
        $types = $this->transection->select('id', 'tran_type')->groupBy('tran_type')->get();
        $data = [
            'types' => $types
        ];
        return response()->json($data, 200);
    }
}
