<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct(
        private Account $account,
    ){}


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getIndex(Request $request): JsonResponse
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $accounts = $this->account->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $data = [
            'total' => $accounts->total(),
            'limit' => $limit,
            'offset' => $offset,
            'accounts' => $accounts->items(),
        ];
        return response()->json($data, 200);
    }


    /**
     * @param Request $request
     * @param Account $account
     * @return JsonResponse
     */
    public function accountStore(Request $request, Account $account): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|unique:accounts,account',
            'balance' => 'required',
            'account_number' => 'required|unique:accounts',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
            $account->account = $request->account;
            $account->description = $request->description;
            $account->balance = $request->balance;
            $account->account_number = $request->account_number;
            $account->save();
            return response()->json([
                'success' => true,
                'message' => 'Account saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Account not saved',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function accountUpdate(Request $request): JsonResponse
    {

        $acc = $this->account->findOrFail($request->id);
        $request->validate([
            'account' => 'required|unique:accounts,account,' . $acc->id,
            'account_number' => 'required|unique:accounts,account_number,' . $acc->id,
        ]);
        $acc->account = $request->account;
        $acc->account_number = $request->account_number;
        $acc->description = $request->description;
        $acc->update();
        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully'
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $account = $this->account->where('account', '!=', 'Receivable')->where('account', '!=', 'Cash')->where('account', '!=', 'Payable')->findOrFail($request->id);
            $account->delete();
            return response()->json(
                ['success' => true, 'message' => 'Account deleted successfully'],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Account not deleted',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSearch(Request $request): JsonResponse
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $search = $request->name;
        if (!empty($search)) {
            $result = $this->account->where('account', 'like', '%' . $search . '%')->orWhere('account_number', 'like', '%' . $search . '%')->latest()->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'accounts' => $result->items(),
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'total' => 0,
                'limit' => $limit,
                'offset' => $offset,
                'accounts' => [],
            ];
            return response()->json($data, 200);
        }
    }
}
