<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\Transection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function __construct(
        private Supplier $supplier,
        private Transection $transection,
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
        $suppliers = $this->supplier->withCount('products')->latest()->paginate($limit, ['*'], 'page', $offset);
        $data =  [
            'total' => $suppliers->total(),
            'limit' => $limit,
            'offset' => $offset,
            'suppliers' => $suppliers->items()
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @return JsonResponse
     */
    public function postStore(Request $request, Supplier $supplier): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|unique:suppliers',
            'email' => 'required|email|unique:suppliers',
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        //Image Upload
        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('supplier/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        try {
            $supplier->name = $request->name;
            $supplier->mobile = $request->mobile;
            $supplier->email = $request->email;
            $supplier->image = $image_name;
            $supplier->state = $request->state;
            $supplier->city = $request->city;
            $supplier->zip_code = $request->zip_code;
            $supplier->address = $request->address;
            $supplier->due_amount = $request->due_amount;
            $supplier->save();
            return response()->json([
                'success' => true,
                'message' => 'Supplier saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not saved'
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetails(Request $request): JsonResponse
    {
        $supplier = $this->supplier->findOrFail($request->id);
        return response()->json([
            'success' => true,
            'message' => 'Supplier details',
            'supplier' => $supplier
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postUpdate(Request $request): JsonResponse
    {
        $supplier = $this->supplier->findOrFail($request->id);

        $validator = $request->validate([
            'name' => 'required',
            'mobile' => 'required|unique:suppliers,mobile,' . $supplier->id,
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        try {
            $supplier->name = $request->name;
            $supplier->mobile = $request->mobile;
            $supplier->email = $request->email;
            $supplier->image = $request->has('image') ? Helpers::update('supplier/', $supplier->image, 'png', $request->file('image')) : $supplier->image;
            $supplier->state = $request->state;
            $supplier->city = $request->city;
            $supplier->zip_code = $request->zip_code;
            $supplier->address = $request->address;
            $supplier->save();
            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier not updated',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $supplier = $this->supplier->findOrFail($request->id);
            Helpers::delete('supplier/' . $supplier['image']);
            $supplier->delete();
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not deleted'
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
        $result = $this->supplier
            ->withCount('products')
            ->where('name', 'like', '%' . $search . '%')->orWhere('mobile', 'like', '%' . $search . '%')
            ->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total' => $result->total(),
            'limit' => $limit,
            'offset' => $offset,
            'suppliers' => $result->items(),
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filterByCity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        if (!empty($request->city)) {
            $result = $this->supplier->where('city', $request->city)->latest()->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'supplier' => $result->items(),
            ];
        } else {
            $data = [
                'total' => 0,
                'limit' => $limit,
                'offset' => $offset,
                'supplier' => [],
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transactions(Request $request): JsonResponse
    {
        $transactions = $this->transection->with('account')->where('supplier_id', $request->supplier_id)->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $data = [
            'total' => $transactions->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'transfers' => $transactions->items()
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function transactionsDateFilter(Request $request)
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
            })->where('supplier_id', '=', $request->supplier_id)->latest()->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'transfers' => $result->items(),
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function payment(Request $request): JsonResponse
    { {
            $request->validate([
                'supplier_id' => 'required',
                'total_due_amount' => 'required',
                'pay_amount' => 'required',
                'remaining_due_amount' => 'required',
                'payment_account_id' => 'required',
            ]);

            $payment_account = $this->account->find($request->payment_account_id);
            if ($payment_account->balance < $request->pay_amount) {
                $data = [
                    'success' => true,
                    'message' => 'You do not have sufficent balance!'
                ];
                return response()->json($data);
            }

            if ($request->pay_amount > 0) {
                $payment_transaction = $this->transection;
                $payment_transaction->tran_type = 'Expense';
                $payment_transaction->account_id = $payment_account->id;
                $payment_transaction->amount = $request->pay_amount;
                $payment_transaction->description = 'Supplier due payment';
                $payment_transaction->debit = 1;
                $payment_transaction->credit = 0;
                $payment_transaction->balance = $payment_account->balance - $request->pay_amount;
                $payment_transaction->date = date("Y/m/d");
                $payment_transaction->supplier_id = $request->supplier_id;
                $payment_transaction->save();

                $payment_account->total_out = $payment_account->total_out + $request->pay_amount;
                $payment_account->balance = $payment_account->balance - $request->pay_amount;
                $payment_account->save();

                $payable_account = $this->account->find(2);
                $payable_transaction = $this->transection;
                $payable_transaction->tran_type = 'Payable';
                $payable_transaction->account_id = $payable_account->id;
                $payable_transaction->amount = $request->pay_amount;
                $payable_transaction->description = 'Supplier due payment';
                $payable_transaction->debit = 1;
                $payable_transaction->credit = 0;
                $payable_transaction->balance = $payable_account->balance - $request->pay_amount;
                $payable_transaction->date = date("Y/m/d");
                $payable_transaction->supplier_id = $request->supplier_id;
                $payable_transaction->save();

                $payable_account->total_out = $payable_account->total_out + $request->pay_amount;
                $payable_account->balance = $payable_account->balance - $request->pay_amount;
                $payable_account->save();
            }

            $supplier = $this->supplier->find($request->supplier_id);
            $supplier->due_amount = $supplier->due_amount - $request->pay_amount;
            $supplier->save();

            $data = [
                'success' => true,
                'message' => 'Supplier payment successfully'
            ];
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function newPurchase(Request $request): JsonResponse
    {

        $request->validate([
            'supplier_id' => 'required',
            'purchased_amount' => 'required',
            'paid_amount' => 'required',
            'due_amount' => 'required',
            'payment_account_id' => 'required',
        ]);

        $payment_account = $this->account->find($request->payment_account_id);

        if ($payment_account->balance < $request->paid_amount) {
            $data = [
                'success' => true,
                'message' => 'You do not have sufficent balance!'
            ];
            return response()->json($data);
        }
        if ($request->paid_amount > 0) {
            $payment_transaction = $this->transection;
            $payment_transaction->tran_type = 'Expense';
            $payment_transaction->account_id = $payment_account->id;
            $payment_transaction->amount = $request->paid_amount;
            $payment_transaction->description = 'Supplier payment';
            $payment_transaction->debit = 1;
            $payment_transaction->credit = 0;
            $payment_transaction->balance = $payment_account->balance - $request->paid_amount;
            $payment_transaction->date = date("Y/m/d");
            $payment_transaction->supplier_id = $request->supplier_id;
            $payment_transaction->save();

            $payment_account->total_out = $payment_account->total_out + $request->paid_amount;
            $payment_account->balance = $payment_account->balance - $request->paid_amount;
            $payment_account->save();
        }

        if ($request->due_amount > 0) {
            $payable_account = $this->account->find(2);
            $payable_transaction = $this->transection;
            $payable_transaction->tran_type = 'Payable';
            $payable_transaction->account_id = $payable_account->id;
            $payable_transaction->amount = $request->due_amount;
            $payable_transaction->description = 'Supplier payment';
            $payable_transaction->debit = 0;
            $payable_transaction->credit = 1;
            $payable_transaction->balance = $payable_account->balance + $request->due_amount;
            $payable_transaction->date = date("Y/m/d");
            $payable_transaction->supplier_id = $request->supplier_id;
            $payable_transaction->save();

            $payable_account->total_in = $payable_account->total_in + $request->due_amount;
            $payable_account->balance = $payable_account->balance + $request->due_amount;
            $payable_account->save();

            $supplier = $this->supplier->find($request->supplier_id);
            $supplier->due_amount = $supplier->due_amount + $request->due_amount;
            $supplier->save();
        }
        $data = [
            'success' => true,
            'message' => 'Supplier new purchase added successfully'
        ];
        return response()->json($data);
    }
}
