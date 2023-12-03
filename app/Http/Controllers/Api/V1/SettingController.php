<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct(
        private Currency $currency,
        private BusinessSetting $business_setting
    ){}

    public function updateShop(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'shop_name'], [
            'value' => $request['shop_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_email'], [
            'value' => $request['shop_email']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_phone'], [
            'value' => $request['shop_phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'stock_limit'], [
            'value' => $request['stock_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'shop_address'], [
            'value' => $request['shop_address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol'], [
            'value' => $request['currency_symbol']
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
        return response()->json([
            'success' => true,
            'message' => 'Shop updated succefully',
        ], 200);
    }
    public function configuration()
    {
        $time_zones = [
            "Pacific/Midway",
            "Pacific/Samoa",
            "Pacific/Honolulu",
            "US/Alaska",
            "America/Los_Angeles",
            "America/Tijuana",
            "US/Arizona",
            "America/Chihuahua",
            "America/Chihuahua",
            "America/Mazatlan",
            "US/Mountain",
            "America/Managua",
            "US/Central",
            "America/Mexico_City",
            "America/Mexico_City",
            "America/Monterrey",
            "Canada/Saskatchewan",
            "America/Bogota",
            "US/Eastern",
            "US/East-Indiana",
            "America/Lima",
            "America/Bogota",
            "Canada/Atlantic",
            "America/Caracas",
            "America/La_Paz",
            "America/Santiago",
            "Canada/Newfoundland",
            "America/Sao_Paulo",
            "America/Argentina/Buenos_Aires",
            "America/Argentina/Buenos_Aires",
            "America/Godthab",
            "America/Noronha",
            "Atlantic/Azores",
            "Atlantic/Cape_Verde",
            "Africa/Casablanca",
            "Europe/London",
            "Etc/Greenwich",
            "Europe/Lisbon",
            "Europe/London",
            "Africa/Monrovia",
            "Europe/Amsterdam",
            "Europe/Belgrade",
            "Europe/Berlin",
            "Europe/Berlin",
            "Europe/Bratislava",
            "Europe/Brussels",
            "Europe/Budapest",
            "Europe/Copenhagen",
            "Europe/Ljubljana",
            "Europe/Madrid",
            "Europe/Paris",
            "Europe/Prague",
            "Europe/Rome",
            "Europe/Sarajevo",
            "Europe/Skopje",
            "Europe/Stockholm",
            "Europe/Vienna",
            "Europe/Warsaw",
            "Africa/Lagos",
            "Europe/Zagreb",
            "Europe/Athens",
            "Europe/Bucharest",
            "Africa/Cairo",
            "Africa/Harare",
            "Europe/Helsinki",
            "Europe/Istanbul",
            "Asia/Jerusalem",
            "Europe/Helsinki",
            "Africa/Johannesburg",
            "Europe/Riga",
            "Europe/Sofia",
            "Europe/Tallinn",
            "Europe/Vilnius",
            "Asia/Baghdad",
            "Asia/Kuwait",
            "Europe/Minsk",
            "Africa/Nairobi",
            "Asia/Riyadh",
            "Europe/Volgograd",
            "Asia/Tehran",
            "Asia/Muscat",
            "Asia/Baku",
            "Europe/Moscow",
            "Asia/Muscat",
            "Europe/Moscow",
            "Asia/Tbilisi",
            "Asia/Yerevan",
            "Asia/Kabul",
            "Asia/Karachi",
            "Asia/Karachi",
            "Asia/Tashkent",
            "Asia/Calcutta",
            "Asia/Kolkata",
            "Asia/Calcutta",
            "Asia/Calcutta",
            "Asia/Calcutta",
            "Asia/Katmandu",
            "Asia/Almaty",
            "Asia/Dhaka",
            "Asia/Yekaterinburg",
            "Asia/Rangoon",
            "Asia/Bangkok",
            "Asia/Bangkok",
            "Asia/Jakarta",
            "Asia/Novosibirsk",
            "Asia/Hong_Kong",
            "Asia/Chongqing",
            "Asia/Hong_Kong",
            "Asia/Krasnoyarsk",
            "Asia/Kuala_Lumpur",
            "Australia/Perth",
            "Asia/Singapore",
            "Asia/Taipei",
            "Asia/Ulan_Bator",
            "Asia/Urumqi",
            "Asia/Irkutsk",
            "Asia/Tokyo",
            "Asia/Tokyo",
            "Asia/Seoul",
            "Asia/Tokyo",
            "Australia/Adelaide",
            "Australia/Darwin",
            "Australia/Brisbane",
            "Australia/Canberra",
            "Pacific/Guam",
            "Australia/Hobart",
            "Australia/Melbourne",
            "Pacific/Port_Moresby",
            "Australia/Sydney",
            "Asia/Yakutsk",
            "Asia/Vladivostok",
            "Pacific/Auckland",
            "Pacific/Fiji",
            "Pacific/Kwajalein",
            "Asia/Kamchatka",
            "Asia/Magadan",
            "Pacific/Fiji",
            "Asia/Magadan",
            "Asia/Magadan",
            "Pacific/Auckland",
            "Pacific/Tongatapu",
        ];
        $key = [
            'shop_logo',
            'pagination_limit',
            'currency',
            'shop_name',
            'shop_address',
            'shop_phone',
            'shop_email',
            'footer_text',
            'app_minimum_version_ios',
            'country',
            'stock_limit',
            'time_zone',
            'vat_reg_no'
        ];
        $config_key_value_array =  array_column($this->business_setting->whereIn('key', $key)->get()->toArray(), 'value', 'key');
        return response()->json([
            'business_info' => $config_key_value_array,
            'currency_symbol' => $this->currency->where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol,
            'base_urls' => [
                'category_image_url' => asset('storage/app/public/category'),
                'brand_image_url' => asset('storage/app/public/brand'),
                'product_image_url' => asset('storage/app/public/product'),
                'supplier_image_url' => asset('storage/app/public/supplier'),
                'shop_image_url' => asset('storage/app/public/shop'),
                'admin_image_url' => asset('storage/app/public/admin'),
                'customer_image_url' => asset('storage/app/public/customer'),
            ],
            'time_zone' => $time_zones,
        ], 200);
    }
}
