<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class BusinessSettingsController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting
    ){}

    /**
     * @return Application|Factory|View
     */
    public function shop_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.shop-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function shop_setup(Request $request): RedirectResponse
    {
        if ($request->pagination_limit == 0) {
            Toastr::warning(translate('pagination_limit_is_required'));
            return back();
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_name'], [
            'value' => $request['shop_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_email'], [
            'value' => $request['shop_email']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_phone'], [
            'value' => $request['shop_phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_address'], [
            'value' => $request['shop_address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'stock_limit'], [
            'value' => $request['stock_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        $curr_logo = $this->business_setting->where(['key' => 'shop_logo'])->first();
        DB::table('business_settings')->updateOrInsert(['key' => 'shop_logo'], [
            'value' => $request->has('shop_logo') ? Helpers::update('shop/', $curr_logo->value, 'png', $request->file('shop_logo')) : $curr_logo->value
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'time_zone'], [
            'value' => $request['time_zone'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'vat_reg_no'], [
            'value' => $request['vat_reg_no'],
        ]);
        Toastr::success(translate('Settings updated'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function shortcut_key(): View|Factory|Application
    {
        return view('admin-views.business-settings.shortcut-key-index');
    }
}
