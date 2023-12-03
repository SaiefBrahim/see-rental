<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Transection;
use App\Models\Account;
use App\CPU\Helpers;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransectionController extends Controller
{
    public function __construct(
        private Transection $transection,
        private Account $account,
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): View|Factory|Application
    {
        $accounts = $this->account->orderBy('id','desc')->get();
        $acc_id = $request['account_id'];
        $tran_type = $request['tran_type'];
        $from = $request['from'];
        $to = $request['to'];

        $query = $this->transection->
            when($acc_id!=null, function($q) use ($request){
                return $q->where('account_id',$request['account_id']);
            })
            ->when($tran_type!=null, function($q) use ($request){
                return $q->where('tran_type',$request['tran_type']);
            })
            ->when($from!=null, function($q) use ($request){
                return $q->whereBetween('date', [$request['from'], $request['to']]);
            });

        $transections = $query->orderBy('id','desc')->paginate(Helpers::pagination_limit())->appends(['account_id' => $request['account_id'],'tran_type'=>$request['tran_type'],'from'=>$request['from'],'to'=>$request['to']]);
        return view('admin-views.transection.list',compact('accounts','transections','acc_id','tran_type','from','to'));
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export(Request $request): StreamedResponse|string
    {
        $acc_id = $request['account_id'];
        $tran_type = $request['tran_type'];
        $from = $request['from'];
        $to = $request['to'];
        if($acc_id==null && $tran_type==null && $to==null && $from !=null)
        {
            $transections = $this->transection->whereMonth('date',Carbon::now()->month)->get();

        }else{
            $transections = $this->transection->
                when($acc_id!=null, function($q) use ($request){
                    return $q->where('account_id',$request['account_id']);
                })
                ->when($tran_type!=null, function($q) use ($request){
                    return $q->where('tran_type',$request['tran_type']);
                })
                ->when($from!=null, function($q) use ($request){
                    return $q->whereBetween('date', [$request['from'], $request['to']]);
                })->get();
        }

        $storage = [];
        foreach($transections as $transection)
        {
            $storage[] = [
                'transection_type' => $transection->tran_type,
                'account' => $transection->account ?  $transection->account->account : '',
                'amount' => $transection->amount,
                'description' => $transection->description,
                'debit' => $transection->debit == 1 ? $transection->amount : 0,
                'credit' => $transection->credit == 1 ? $transection->amount : 0,
                'balance' => $transection->balance,
                'date' => $transection->date,
            ];
        }
        return (new FastExcel($storage))->download('transection_history.xlsx');
    }
}
