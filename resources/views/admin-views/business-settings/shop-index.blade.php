@extends('layouts.admin.app')
@section('title', \App\CPU\translate('shop_setup'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/custom.css" />
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="row align-items-center mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title d-flex align-items-center g-2px text-capitalize">{{ \App\CPU\translate('shop_setup') }}</h1>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.update-setup') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($shop_name = \App\Models\BusinessSetting::where('key', 'shop_name')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('shop_name') }} </label>
                                        <input type="text" name="shop_name" value="{{ $shop_name }}"
                                            class="form-control" placeholder="{{ \App\CPU\translate('shop_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($shop_email = \App\Models\BusinessSetting::where('key', 'shop_email')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('shop_email') }} </label>
                                        <input type="email" name="shop_email" value="{{ $shop_email }}"
                                            class="form-control" placeholder="{{ \App\CPU\translate('shop_email') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($shop_phone = \App\Models\BusinessSetting::where('key', 'shop_phone')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('shop_phone') }} </label>
                                        <input type="text" name="shop_phone" value="{{ $shop_phone }}"
                                               class="form-control" placeholder="{{ \App\CPU\translate('shop_phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($shop_address = \App\Models\BusinessSetting::where('key', 'shop_address')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('shop_address') }}</label>
                                        <input type="text" name="shop_address" value="{{ $shop_address }}"
                                               class="form-control" placeholder="{{ \App\CPU\translate('shop_address') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($pagination_limit = \App\Models\BusinessSetting::where('key', 'pagination_limit')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('pagination_limit') }}</label>
                                        <input min="1" type="number" name="pagination_limit" value="{{ $pagination_limit }}"
                                               class="form-control" placeholder="{{ \App\CPU\translate('pagination_limit') }}" id="pagination_limit" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('currency') }}</label>
                                        <select name="currency" class="form-control js-select2-custom">
                                            @foreach (\App\Models\Currency::orderBy('currency_code')->get() as $currency)
                                                <option value="{{ $currency['currency_code'] }}"
                                                    {{ $currency_code == $currency['currency_code'] ? 'selected' : '' }}>
                                                    {{ $currency['currency_code'] }} ({{ $currency['currency_symbol'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    <div class="form-group">
                                        <label class="input-label" for="country">{{ \App\CPU\translate('country') }}</label>
                                        <select id="country" name="country" class="form-control  js-select2-custom">
                                            <option value="AF">Afghanistan</option>
                                            <option value="AX">Åland Islands</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia, Plurinational State of</option>
                                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo, the Democratic Republic of the</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard Island and McDonald Islands</option>
                                            <option value="VA">Holy See (Vatican City State)</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran, Islamic Republic of</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JE">Jersey</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea, Democratic People's Republic of</option>
                                            <option value="KR">Korea, Republic of</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia, Federated States of</option>
                                            <option value="MD">Moldova, Republic of</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestinian Territory, Occupied</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Réunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="MF">Saint Martin (French part)</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SX">Sint Maarten (Dutch part)</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SS">South Sudan</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan, Province of China</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands, British</option>
                                            <option value="VI">Virgin Islands, U.S.</option>
                                            <option value="WF">Wallis and Futuna</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($footer_text = \App\Models\BusinessSetting::where('key', 'footer_text')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('footer_text') }}</label>
                                        <input type="text" name="footer_text" value="{{ $footer_text }}"
                                               class="form-control" placeholder="{{ \App\CPU\translate('footer_text') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($stock_limit = \App\Models\BusinessSetting::where('key', 'stock_limit')->first()->value)
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('minimum_stock_limit_for_warning') }}</label>
                                        <input type="number" name="stock_limit" value="{{ $stock_limit }}" min="1"
                                               class="form-control" placeholder="{{ \App\CPU\translate('stock_limit') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{ \App\CPU\translate('time_zone') }}</label>
                                        <select name="time_zone" id="time_zone" data-maximum-selection-length="3" class="form-control js-select2-custom">
                                            <option value='Pacific/Midway'>(UTC-11:00) Midway Island</option>
                                            <option value='Pacific/Samoa'>(UTC-11:00) Samoa</option>
                                            <option value='Pacific/Honolulu'>(UTC-10:00) Hawaii</option>
                                            <option value='US/Alaska'>(UTC-09:00) Alaska</option>
                                            <option value='America/Los_Angeles'>(UTC-08:00) Pacific Time (US &amp; Canada)</option>
                                            <option value='America/Tijuana'>(UTC-08:00) Tijuana</option>
                                            <option value='US/Arizona'>(UTC-07:00) Arizona</option>
                                            <option value='America/Chihuahua'>(UTC-07:00) Chihuahua</option>
                                            <option value='America/Chihuahua'>(UTC-07:00) La Paz</option>
                                            <option value='America/Mazatlan'>(UTC-07:00) Mazatlan</option>
                                            <option value='US/Mountain'>(UTC-07:00) Mountain Time (US &amp; Canada)</option>
                                            <option value='America/Managua'>(UTC-06:00) Central America</option>
                                            <option value='US/Central'>(UTC-06:00) Central Time (US &amp; Canada)</option>
                                            <option value='America/Mexico_City'>(UTC-06:00) Guadalajara</option>
                                            <option value='America/Mexico_City'>(UTC-06:00) Mexico City</option>
                                            <option value='America/Monterrey'>(UTC-06:00) Monterrey</option>
                                            <option value='Canada/Saskatchewan'>(UTC-06:00) Saskatchewan</option>
                                            <option value='America/Bogota'>(UTC-05:00) Bogota</option>
                                            <option value='US/Eastern'>(UTC-05:00) Eastern Time (US &amp; Canada)</option>
                                            <option value='US/East-Indiana'>(UTC-05:00) Indiana (East)</option>
                                            <option value='America/Lima'>(UTC-05:00) Lima</option>
                                            <option value='America/Bogota'>(UTC-05:00) Quito</option>
                                            <option value='Canada/Atlantic'>(UTC-04:00) Atlantic Time (Canada)</option>
                                            <option value='America/Caracas'>(UTC-04:30) Caracas</option>
                                            <option value='America/La_Paz'>(UTC-04:00) La Paz</option>
                                            <option value='America/Santiago'>(UTC-04:00) Santiago</option>
                                            <option value='Canada/Newfoundland'>(UTC-03:30) Newfoundland</option>
                                            <option value='America/Sao_Paulo'>(UTC-03:00) Brasilia</option>
                                            <option value='America/Argentina/Buenos_Aires'>(UTC-03:00) Buenos Aires</option>
                                            <option value='America/Argentina/Buenos_Aires'>(UTC-03:00) Georgetown</option>
                                            <option value='America/Godthab'>(UTC-03:00) Greenland</option>
                                            <option value='America/Noronha'>(UTC-02:00) Mid-Atlantic</option>
                                            <option value='Atlantic/Azores'>(UTC-01:00) Azores</option>
                                            <option value='Atlantic/Cape_Verde'>(UTC-01:00) Cape Verde Is.</option>
                                            <option value='Africa/Casablanca'>(UTC+00:00) Casablanca</option>
                                            <option value='Europe/London'>(UTC+00:00) Edinburgh</option>
                                            <option value='Etc/Greenwich'>(UTC+00:00) Greenwich Mean Time : Dublin</option>
                                            <option value='Europe/Lisbon'>(UTC+00:00) Lisbon</option>
                                            <option value='Europe/London'>(UTC+00:00) London</option>
                                            <option value='Africa/Monrovia'>(UTC+00:00) Monrovia</option>
                                            <option value='UTC'>(UTC+00:00) UTC</option>
                                            <option value='Europe/Amsterdam'>(UTC+01:00) Amsterdam</option>
                                            <option value='Europe/Belgrade'>(UTC+01:00) Belgrade</option>
                                            <option value='Europe/Berlin'>(UTC+01:00) Berlin</option>
                                            <option value='Europe/Berlin'>(UTC+01:00) Bern</option>
                                            <option value='Europe/Bratislava'>(UTC+01:00) Bratislava</option>
                                            <option value='Europe/Brussels'>(UTC+01:00) Brussels</option>
                                            <option value='Europe/Budapest'>(UTC+01:00) Budapest</option>
                                            <option value='Europe/Copenhagen'>(UTC+01:00) Copenhagen</option>
                                            <option value='Europe/Ljubljana'>(UTC+01:00) Ljubljana</option>
                                            <option value='Europe/Madrid'>(UTC+01:00) Madrid</option>
                                            <option value='Europe/Paris'>(UTC+01:00) Paris</option>
                                            <option value='Europe/Prague'>(UTC+01:00) Prague</option>
                                            <option value='Europe/Rome'>(UTC+01:00) Rome</option>
                                            <option value='Europe/Sarajevo'>(UTC+01:00) Sarajevo</option>
                                            <option value='Europe/Skopje'>(UTC+01:00) Skopje</option>
                                            <option value='Europe/Stockholm'>(UTC+01:00) Stockholm</option>
                                            <option value='Europe/Vienna'>(UTC+01:00) Vienna</option>
                                            <option value='Europe/Warsaw'>(UTC+01:00) Warsaw</option>
                                            <option value='Africa/Lagos'>(UTC+01:00) West Central Africa</option>
                                            <option value='Europe/Zagreb'>(UTC+01:00) Zagreb</option>
                                            <option value='Europe/Athens'>(UTC+02:00) Athens</option>
                                            <option value='Europe/Bucharest'>(UTC+02:00) Bucharest</option>
                                            <option value='Africa/Cairo'>(UTC+02:00) Cairo</option>
                                            <option value='Africa/Harare'>(UTC+02:00) Harare</option>
                                            <option value='Europe/Helsinki'>(UTC+02:00) Helsinki</option>
                                            <option value='Europe/Istanbul'>(UTC+02:00) Istanbul</option>
                                            <option value='Asia/Jerusalem'>(UTC+02:00) Jerusalem</option>
                                            <option value='Europe/Helsinki'>(UTC+02:00) Kyiv</option>
                                            <option value='Africa/Johannesburg'>(UTC+02:00) Pretoria</option>
                                            <option value='Europe/Riga'>(UTC+02:00) Riga</option>
                                            <option value='Europe/Sofia'>(UTC+02:00) Sofia</option>
                                            <option value='Europe/Tallinn'>(UTC+02:00) Tallinn</option>
                                            <option value='Europe/Vilnius'>(UTC+02:00) Vilnius</option>
                                            <option value='Asia/Baghdad'>(UTC+03:00) Baghdad</option>
                                            <option value='Asia/Kuwait'>(UTC+03:00) Kuwait</option>
                                            <option value='Europe/Minsk'>(UTC+03:00) Minsk</option>
                                            <option value='Africa/Nairobi'>(UTC+03:00) Nairobi</option>
                                            <option value='Asia/Riyadh'>(UTC+03:00) Riyadh</option>
                                            <option value='Europe/Volgograd'>(UTC+03:00) Volgograd</option>
                                            <option value='Asia/Tehran'>(UTC+03:30) Tehran</option>
                                            <option value='Asia/Muscat'>(UTC+04:00) Abu Dhabi</option>
                                            <option value='Asia/Baku'>(UTC+04:00) Baku</option>
                                            <option value='Europe/Moscow'>(UTC+04:00) Moscow</option>
                                            <option value='Asia/Muscat'>(UTC+04:00) Muscat</option>
                                            <option value='Europe/Moscow'>(UTC+04:00) St. Petersburg</option>
                                            <option value='Asia/Tbilisi'>(UTC+04:00) Tbilisi</option>
                                            <option value='Asia/Yerevan'>(UTC+04:00) Yerevan</option>
                                            <option value='Asia/Kabul'>(UTC+04:30) Kabul</option>
                                            <option value='Asia/Karachi'>(UTC+05:00) Islamabad</option>
                                            <option value='Asia/Karachi'>(UTC+05:00) Karachi</option>
                                            <option value='Asia/Tashkent'>(UTC+05:00) Tashkent</option>
                                            <option value='Asia/Calcutta'>(UTC+05:30) Chennai</option>
                                            <option value='Asia/Kolkata'>(UTC+05:30) Kolkata</option>
                                            <option value='Asia/Calcutta'>(UTC+05:30) Mumbai</option>
                                            <option value='Asia/Calcutta'>(UTC+05:30) New Delhi</option>
                                            <option value='Asia/Calcutta'>(UTC+05:30) Sri Jayawardenepura</option>
                                            <option value='Asia/Katmandu'>(UTC+05:45) Kathmandu</option>
                                            <option value='Asia/Almaty'>(UTC+06:00) Almaty</option>
                                            <option value='Asia/Dhaka'>(UTC+06:00) Dhaka</option>
                                            <option value='Asia/Yekaterinburg'>(UTC+06:00) Ekaterinburg</option>
                                            <option value='Asia/Rangoon'>(UTC+06:30) Rangoon</option>
                                            <option value='Asia/Bangkok'>(UTC+07:00) Bangkok</option>
                                            <option value='Asia/Bangkok'>(UTC+07:00) Hanoi</option>
                                            <option value='Asia/Jakarta'>(UTC+07:00) Jakarta</option>
                                            <option value='Asia/Novosibirsk'>(UTC+07:00) Novosibirsk</option>
                                            <option value='Asia/Hong_Kong'>(UTC+08:00) Beijing</option>
                                            <option value='Asia/Chongqing'>(UTC+08:00) Chongqing</option>
                                            <option value='Asia/Hong_Kong'>(UTC+08:00) Hong Kong</option>
                                            <option value='Asia/Krasnoyarsk'>(UTC+08:00) Krasnoyarsk</option>
                                            <option value='Asia/Kuala_Lumpur'>(UTC+08:00) Kuala Lumpur</option>
                                            <option value='Australia/Perth'>(UTC+08:00) Perth</option>
                                            <option value='Asia/Singapore'>(UTC+08:00) Singapore</option>
                                            <option value='Asia/Taipei'>(UTC+08:00) Taipei</option>
                                            <option value='Asia/Ulan_Bator'>(UTC+08:00) Ulaan Bataar</option>
                                            <option value='Asia/Urumqi'>(UTC+08:00) Urumqi</option>
                                            <option value='Asia/Irkutsk'>(UTC+09:00) Irkutsk</option>
                                            <option value='Asia/Tokyo'>(UTC+09:00) Osaka</option>
                                            <option value='Asia/Tokyo'>(UTC+09:00) Sapporo</option>
                                            <option value='Asia/Seoul'>(UTC+09:00) Seoul</option>
                                            <option value='Asia/Tokyo'>(UTC+09:00) Tokyo</option>
                                            <option value='Australia/Adelaide'>(UTC+09:30) Adelaide</option>
                                            <option value='Australia/Darwin'>(UTC+09:30) Darwin</option>
                                            <option value='Australia/Brisbane'>(UTC+10:00) Brisbane</option>
                                            <option value='Australia/Canberra'>(UTC+10:00) Canberra</option>
                                            <option value='Pacific/Guam'>(UTC+10:00) Guam</option>
                                            <option value='Australia/Hobart'>(UTC+10:00) Hobart</option>
                                            <option value='Australia/Melbourne'>(UTC+10:00) Melbourne</option>
                                            <option value='Pacific/Port_Moresby'>(UTC+10:00) Port Moresby</option>
                                            <option value='Australia/Sydney'>(UTC+10:00) Sydney</option>
                                            <option value='Asia/Yakutsk'>(UTC+10:00) Yakutsk</option>
                                            <option value='Asia/Vladivostok'>(UTC+11:00) Vladivostok</option>
                                            <option value='Pacific/Auckland'>(UTC+12:00) Auckland</option>
                                            <option value='Pacific/Fiji'>(UTC+12:00) Fiji</option>
                                            <option value='Pacific/Kwajalein'>(UTC+12:00) International Date Line West</option>
                                            <option value='Asia/Kamchatka'>(UTC+12:00) Kamchatka</option>
                                            <option value='Asia/Magadan'>(UTC+12:00) Magadan</option>
                                            <option value='Pacific/Fiji'>(UTC+12:00) Marshall Is.</option>
                                            <option value='Asia/Magadan'>(UTC+12:00) New Caledonia</option>
                                            <option value='Asia/Magadan'>(UTC+12:00) Solomon Is.</option>
                                            <option value='Pacific/Auckland'>(UTC+12:00) Wellington</option>
                                            <option value='Pacific/Tongatapu'>(UTC+13:00) Nuku'alofa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($vat_reg_no = \App\Models\BusinessSetting::where(['key' => 'vat_reg_no'])->first()->value)
                                    <div class="form-group ">
                                        <label>{{ \App\CPU\translate('vat_reg_no') }}</label>
                                        <input class="form-control" type="text" name="vat_reg_no"
                                               placeholder="{{ \App\CPU\translate('vat_reg_no') }}" value="{{ $vat_reg_no }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-2">
                                    @php($shop_logo = \App\Models\BusinessSetting::where('key', 'shop_logo')->first()->value)
                                    <div class="form-group ">
                                        <label>{{ \App\CPU\translate('logo') }}</label><small class="text-danger">* ( {{ \App\CPU\translate('ratio_1:1') }} )</small>
                                        <div class="custom-file">
                                            <input type="file" name="shop_logo" id="customFileEg1" class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{ \App\CPU\translate('choose') }}{{ \App\CPU\translate('file') }}</label>
                                        </div>
                                        <center>
                                            <img class="img-one-bs my-4" id="viewer"
                                                 onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                                 src="{{ asset('storage/app/public/shop/' . $shop_logo) }}" alt="" />
                                        </center>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="row">--}}
{{--                                @php($shop_logo = \App\Models\BusinessSetting::where('key', 'shop_logo')->first()->value)--}}
{{--                                <div class="col-sm-6 col-lg-6 mb-3 mb-lg-2">--}}
{{--                                    <div class="form-group ">--}}
{{--                                        <label>{{ \App\CPU\translate('logo') }}</label><small class="text-danger">*--}}
{{--                                            ( {{ \App\CPU\translate('ratio_1:1') }} )</small>--}}
{{--                                        <div class="custom-file">--}}
{{--                                            <input type="file" name="shop_logo" id="customFileEg1"--}}
{{--                                                class="custom-file-input"--}}
{{--                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">--}}
{{--                                            <label class="custom-file-label"--}}
{{--                                                for="customFileEg1">{{ \App\CPU\translate('choose') }}--}}
{{--                                                {{ \App\CPU\translate('file') }}</label>--}}
{{--                                        </div>--}}
{{--                                        <hr>--}}
{{--                                        <center>--}}
{{--                                            <img class="img-one-bs" id="viewer"--}}
{{--                                                onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"--}}
{{--                                                src="{{ asset('storage/app/public/shop/' . $shop_logo) }}"--}}
{{--                                                alt="" />--}}
{{--                                        </center>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <button type="submit"
                                class="btn btn-primary mb-2">{{ \App\CPU\translate('submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src={{ asset('public/assets/admin/js/global.js') }}></script>
    <script>
        "use strict";
        $(document).on('ready', function() {
            @php($country = \App\Models\BusinessSetting::where('key', 'country')->first()->value)
            $("#country option[value='{{ $country }}']").attr('selected', 'selected').change();
        });
        @php($time_zone = \App\Models\BusinessSetting::where('key', 'time_zone')->first())
        @php($time_zone = $time_zone->value ?? null)
        $('[name=time_zone]').val("{{ $time_zone }}");

        function enforceMinMax(el) {
            if (el.value != "") {
                if (parseInt(el.value) < parseInt(el.min)) {
                    el.value = el.min;
                }
                if (parseInt(el.value) > parseInt(el.max)) {
                    el.value = el.max;
                }
            }
        }
    </script>

    <script>
        $(function() {
            $("#pagination_limit").keydown(function() {
                // Save old value.
                if (!$(this).val() || parseInt($(this).val()) >= 0)
                    $(this).data("old", $(this).val());
            });
            $("#pagination_limit").keyup(function() {
                // Check correct, else revert back to old value.
                if (!$(this).val() || parseInt($(this).val()) >= 0)
                ;
                else
                    $(this).val($(this).data("old"));
            });
        });
    </script>
@endpush
