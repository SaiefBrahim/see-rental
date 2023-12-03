@extends('layouts.blank')

@section('content')
    @php $phone_codes = [
    ["name" => 'UK (+44)', "code" => '44'],
    ["name" => 'USA (+1)', "code" => '1'],
    ["name" => 'Algeria (+213)', "code" => '213'],
    ["name" => 'Andorra (+376)', "code" => '376'],
    ["name" => 'Angola (+244)', "code" => '244'],
    ["name" => 'Anguilla (+1264)', "code" => '1264'],
    ["name" => 'Antigua & Barbuda (+1268)', "code" => '1268'],
    ["name" => 'Argentina (+54)', "code" => '54'],
    ["name" => 'Armenia (+374)', "code" => '374'],
    ["name" => 'Aruba (+297)', "code" => '297'],
    ["name" => 'Australia (+61)', "code" => '61'],
    ["name" => 'Austria (+43)', "code" => '43'],
    ["name" => 'Azerbaijan (+994)', "code" => '994'],
    ["name" => 'Bahamas (+1242)', "code" => '1242'],
    ["name" => 'Bahrain (+973)', "code" => '973'],
    ["name" => 'Bangladesh (+880)', "code" => '880'],
    ["name" => 'Barbados (+1246)', "code" => '1246'],
    ["name" => 'Belarus (+375)', "code" => '375'],
    ["name" => 'Belgium (+32)', "code" => '32'],
    ["name" => 'Belize (+501)', "code" => '501'],
    ["name" => 'Benin (+229)', "code" => '229'],
    ["name" => 'Bermuda (+1441)', "code" => '1441'],
    ["name" => 'Bhutan (+975)', "code" => '975'],
    ["name" => 'Bolivia (+591)', "code" => '591'],
    ["name" => 'Bosnia Herzegovina (+387)', "code" => '387'],
    ["name" => 'Botswana (+267)', "code" => '267'],
    ["name" => 'Brazil (+55)', "code" => '55'],
    ["name" => 'Brunei (+673)', "code" => '673'],
    ["name" => 'Bulgaria (+359)', "code" => '359'],
    ["name" => 'Burkina Faso (+226)', "code" => '226'],
    ["name" => 'Burundi (+257)', "code" => '257'],
    ["name" => 'Cambodia (+855)', "code" => '855'],
    ["name" => 'Cameroon (+237)', "code" => '237'],
    ["name" => 'Canada (+1)', "code" => '1'],
    ["name" => 'Cape Verde Islands (+238)', "code" => '238'],
    ["name" => 'Cayman Islands (+1345)', "code" => '1345'],
    ["name" => 'Central African Republic (+236)', "code" => '236'],
    ["name" => 'Chile (+56)', "code" => '56'],
    ["name" => 'China (+86)', "code" => '86'],
    ["name" => 'Colombia (+57)', "code" => '57'],
    ["name" => 'Comoros (+269)', "code" => '269'],
    ["name" => 'Congo (+242)', "code" => '242'],
    ["name" => 'Cook Islands (+682)', "code" => '682'],
    ["name" => 'Costa Rica (+506)', "code" => '506'],
    ["name" => 'Croatia (+385)', "code" => '385'],
    ["name" => 'Cuba (+53)', "code" => '53'],
    ["name" => 'Cyprus North (+90392)', "code" => '90392'],
    ["name" => 'Cyprus South (+357)', "code" => '357'],
    ["name" => 'Czech Republic (+42)', "code" => '42'],
    ["name" => 'Denmark (+45)', "code" => '45'],
    ["name" => 'Djibouti (+253)', "code" => '253'],
    ["name" => 'Dominica (+1767)', "code" => '1767'],
    ["name" => 'Dominican Republic (+1809)', "code" => '1809'],
    ["name" => 'Ecuador (+593)', "code" => '593'],
    ["name" => 'Egypt (+20)', "code" => '20'],
    ["name" => 'El Salvador (+503)', "code" => '503'],
    ["name" => 'Equatorial Guinea (+240)', "code" => '240'],
    ["name" => 'Eritrea (+291)', "code" => '291'],
    ["name" => 'Estonia (+372)', "code" => '372'],
    ["name" => 'Ethiopia (+251)', "code" => '251'],
    ["name" => 'Falkland Islands (+500)', "code" => '500'],
    ["name" => 'Faroe Islands (+298)', "code" => '298'],
    ["name" => 'Fiji (+679)', "code" => '679'],
    ["name" => 'Finland (+358)', "code" => '358'],
    ["name" => 'France (+33)', "code" => '33'],
    ["name" => 'French Guiana (+594)', "code" => '594'],
    ["name" => 'French Polynesia (+689)', "code" => '689'],
    ["name" => 'Gabon (+241)', "code" => '241'],
    ["name" => 'Gambia (+220)', "code" => '220'],
    ["name" => 'Georgia (+7880)', "code" => '7880'],
    ["name" => 'Germany (+49)', "code" => '49'],
    ["name" => 'Ghana (+233)', "code" => '233'],
    ["name" => 'Gibraltar (+350)', "code" => '350'],
    ["name" => 'Greece (+30)', "code" => '30'],
    ["name" => 'Greenland (+299)', "code" => '299'],
    ["name" => 'Grenada (+1473)', "code" => '1473'],
    ["name" => 'Guadeloupe (+590)', "code" => '590'],
    ["name" => 'Guam (+671)', "code" => '671'],
    ["name" => 'Guatemala (+502)', "code" => '502'],
    ["name" => 'Guinea (+224)', "code" => '224'],
    ["name" => 'Guinea - Bissau (+245)', "code" => '245'],
    ["name" => 'Guyana (+592)', "code" => '592'],
    ["name" => 'Haiti (+509)', "code" => '509'],
    ["name" => 'Honduras (+504)', "code" => '504'],
    ["name" => 'Hong Kong (+852)', "code" => '852'],
    ["name" => 'Hungary (+36)', "code" => '36'],
    ["name" => 'Iceland (+354)', "code" => '354'],
    ["name" => 'India (+91)', "code" => '91'],
    ["name" => 'Indonesia (+62)', "code" => '62'],
    ["name" => 'Iran (+98)', "code" => '98'],
    ["name" => 'Iraq (+964)', "code" => '964'],
    ["name" => 'Ireland (+353)', "code" => '353'],
    ["name" => 'Israel (+972)', "code" => '972'],
    ["name" => 'Italy (+39)', "code" => '39'],
    ["name" => 'Jamaica (+1876)', "code" => '1876'],
    ["name" => 'Japan (+81)', "code" => '81'],
    ["name" => 'Jordan (+962)', "code" => '962'],
    ["name" => 'Kazakhstan (+7)', "code" => '7'],
    ["name" => 'Kenya (+254)', "code" => '254'],
    ["name" => 'Kiribati (+686)', "code" => '686'],
    ["name" => 'Korea North (+850)', "code" => '850'],
    ["name" => 'Korea South (+82)', "code" => '82'],
    ["name" => 'Kuwait (+965)', "code" => '965'],
    ["name" => 'Kyrgyzstan (+996)', "code" => '996'],
    ["name" => 'Laos (+856)', "code" => '856'],
    ["name" => 'Latvia (+371)', "code" => '371'],
    ["name" => 'Lebanon (+961)', "code" => '961'],
    ["name" => 'Lesotho (+266)', "code" => '266'],
    ["name" => 'Liberia (+231)', "code" => '231'],
    ["name" => 'Libya (+218)', "code" => '218'],
    ["name" => 'Liechtenstein (+417)', "code" => '417'],
    ["name" => 'Lithuania (+370)', "code" => '370'],
    ["name" => 'Luxembourg (+352)', "code" => '352'],
    ["name" => 'Macao (+853)', "code" => '853'],
    ["name" => 'Macedonia (+389)', "code" => '389'],
    ["name" => 'Madagascar (+261)', "code" => '261'],
    ["name" => 'Malawi (+265)', "code" => '265'],
    ["name" => 'Malaysia (+60)', "code" => '60'],
    ["name" => 'Maldives (+960)', "code" => '960'],
    ["name" => 'Mali (+223)', "code" => '223'],
    ["name" => 'Malta (+356)', "code" => '356'],
    ["name" => 'Marshall Islands (+692)', "code" => '692'],
    ["name" => 'Martinique (+596)', "code" => '596'],
    ["name" => 'Mauritania (+222)', "code" => '222'],
    ["name" => 'Mayotte (+269)', "code" => '269'],
    ["name" => 'Mexico (+52)', "code" => '52'],
    ["name" => 'Micronesia (+691)', "code" => '691'],
    ["name" => 'Moldova (+373)', "code" => '373'],
    ["name" => 'Monaco (+377)', "code" => '377'],
    ["name" => 'Montserrat (+1664)', "code" => '1664'],
    ["name" => 'Morocco (+212)', "code" => '212'],
    ["name" => 'Mozambique (+258)', "code" => '258'],
    ["name" => 'Myanmar (+95)', "code" => '95'],
    ["name" => 'Namibia (+264)', "code" => '264'],
    ["name" => 'Nauru (+674)', "code" => '674'],
    ["name" => 'Nepal (+977)', "code" => '977'],
    ["name" => 'Netherlands (+31)', "code" => '31'],
    ["name" => 'New Caledonia (+687)', "code" => '687'],
    ["name" => 'New Zealand (+64)', "code" => '64'],
    ["name" => 'Nicaragua (+505)', "code" => '505'],
    ["name" => 'Niger (+227)', "code" => '227'],
    ["name" => 'Nigeria (+234)', "code" => '234'],
    ["name" => 'Niue (+683)', "code" => '683'],
    ["name" => 'Norfolk Islands (+672)', "code" => '672'],
    ["name" => 'Northern Marianas (+670)', "code" => '670'],
    ["name" => 'Norway (+47)', "code" => '47'],
    ["name" => 'Oman (+968)', "code" => '968'],
    ["name" => 'Palau (+680)', "code" => '680'],
    ["name" => 'Panama (+507)', "code" => '507'],
    ["name" => 'Papua New Guinea (+675)', "code" => '675'],
    ["name" => 'Paraguay (+595)', "code" => '595'],
    ["name" => 'Peru (+51)', "code" => '51'],
    ["name" => 'Philippines (+63)', "code" => '63'],
    ["name" => 'Poland (+48)', "code" => '48'],
    ["name" => 'Portugal (+351)', "code" => '351'],
    ["name" => 'Qatar (+974)', "code" => '974'],
    ["name" => 'Reunion (+262)', "code" => '262'],
    ["name" => 'Romania (+40)', "code" => '40'],
    ["name" => 'Russia (+7)', "code" => '7'],
    ["name" => 'Rwanda (+250)', "code" => '250'],
    ["name" => 'San Marino (+378)', "code" => '378'],
    ["name" => 'Sao Tome & Principe (+239)', "code" => '239'],
    ["name" => 'Saudi Arabia (+966)', "code" => '966'],
    ["name" => 'Senegal (+221)', "code" => '221'],
    ["name" => 'Serbia (+381)', "code" => '381'],
    ["name" => 'Seychelles (+248)', "code" => '248'],
    ["name" => 'Sierra Leone (+232)', "code" => '232'],
    ["name" => 'Singapore (+65)', "code" => '65'],
    ["name" => 'Slovak Republic (+421)', "code" => '421'],
    ["name" => 'Slovenia (+386)', "code" => '386'],
    ["name" => 'Solomon Islands (+677)', "code" => '677'],
    ["name" => 'Somalia (+252)', "code" => '252'],
    ["name" => 'South Africa (+27)', "code" => '27'],
    ["name" => 'Spain (+34)', "code" => '34'],
    ["name" => 'Sri Lanka (+94)', "code" => '94'],
    ["name" => 'St. Helena (+290)', "code" => '290'],
    ["name" => 'St. Kitts (+1869)', "code" => '1869'],
    ["name" => 'St. Lucia (+1758)', "code" => '1758'],
    ["name" => 'Sudan (+249)', "code" => '249'],
    ["name" => 'Suriname (+597)', "code" => '597'],
    ["name" => 'Swaziland (+268)', "code" => '268'],
    ["name" => 'Sweden (+46)', "code" => '46'],
    ["name" => 'Switzerland (+41)', "code" => '41'],
    ["name" => 'Syria (+963)', "code" => '963'],
    ["name" => 'Taiwan (+886)', "code" => '886'],
    ["name" => 'Tajikstan (+7)', "code" => '7'],
    ["name" => 'Thailand (+66)', "code" => '66'],
    ["name" => 'Togo (+228)', "code" => '228'],
    ["name" => 'Tonga (+676)', "code" => '676'],
    ["name" => 'Trinidad & Tobago (+1868)', "code" => '1868'],
    ["name" => 'Tunisia (+216)', "code" => '216'],
    ["name" => 'Turkey (+90)', "code" => '90'],
    ["name" => 'Turkmenistan (+7)', "code" => '7'],
    ["name" => 'Turkmenistan (+993)', "code" => '993'],
    ["name" => 'Turks & Caicos Islands (+1649)', "code" => '1649'],
    ["name" => 'Tuvalu (+688)', "code" => '688'],
    ["name" => 'Uganda (+256)', "code" => '256'],
    ["name" => 'Ukraine (+380)', "code" => '380'],
    ["name" => 'United Arab Emirates (+971)', "code" => '971'],
    ["name" => 'Uruguay (+598)', "code" => '598'],
    ["name" => 'Uzbekistan (+7)', "code" => '7'],
    ["name" => 'Vanuatu (+678)', "code" => '678'],
    ["name" => 'Vatican City (+379)', "code" => '379'],
    ["name" => 'Venezuela (+58)', "code" => '58'],
    ["name" => 'Vietnam (+84)', "code" => '84'],
    ["name" => 'Virgin Islands - British (+1284)', "code" => '1284'],
    ["name" => 'Virgin Islands - US (+1340)', "code" => '1340'],
    ["name" => 'Wallis & Futuna (+681)', "code" => '681'],
    ["name" => 'Yemen (North)(+969)', "code" => '969'],
    ["name" => 'Yemen (South)(+967)', "code" => '967'],
    ["name" => 'Zambia (+260)', "code" => '260'],
    ["name" => 'Zimbabwe (+263)', "code" => '263'],
]; @endphp

        <!-- Title -->
    <div class="text-center text-white mb-4">
        <h2>6POS Software Installation</h2>
        <h6 class="fw-normal">Please proceed step by step with proper data according to instructions</h6>
    </div>

    <!-- Progress -->
    <div class="pb-2">
        <div class="progress cursor-pointer" role="progressbar" aria-label="Grofresh Software Installation"
             aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip"
             data-bs-placement="top" data-bs-custom-class="custom-progress-tooltip" data-bs-title="Final Step!"
             data-bs-delay='{"hide":1000}'>
            <div class="progress-bar" style="width: 90%"></div>
        </div>
    </div>

    <!-- Card -->
    <div class="card mt-4 position-relative">
        <div class="d-flex justify-content-end mb-2 position-absolute top-end">
            <a href="#" class="d-flex align-items-center gap-1">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                              data-bs-title="Admin setup">

                            <img src="{{asset('public/assets/installation')}}/assets/img/svg-icons/info.svg" alt=""
                                 class="svg">
                        </span>
            </a>
        </div>
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="d-flex align-items-center column-gap-3 flex-wrap">
                <h5 class="fw-bold fs text-uppercase">Step 5. </h5>
                <h5 class="fw-normal">Admin Account Settings</h5>
            </div>
            <p class="mb-4">These information will be used to create <strong>admin credential</strong>
                for your admin panel.
            </p>

            <form method="POST" action="{{ route('system_settings',['token'=>bcrypt('step_6')]) }}">
                @csrf
                <div class="bg-light p-4 rounded mb-4">
                    <div class="px-xl-2 pb-sm-3">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <div class="from-group">
                                    <label for="first-name" class="d-flex align-items-center gap-2 mb-2">Business Name</label>
                                    <input type="text" id="first-name" class="form-control" name="business_name"
                                           required placeholder="Ex: 6POS">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="first-name" class="d-flex align-items-center gap-2 mb-2">
                                        First Name</label>
                                    <input type="text" id="first-name" class="form-control" name="admin_f_name"
                                           required placeholder="Ex: John">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="last-name" class="d-flex align-items-center gap-2 mb-2">
                                        Last Name</label>
                                    <input type="text" id="last-name" class="form-control" name="admin_l_name"
                                           required placeholder="Ex: Doe">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="phone" class="d-flex align-items-center gap-2 mb-2">
                                        <span class="fw-medium">Phone</span>
                                        <span class="cursor-pointer" data-bs-toggle="tooltip"
                                              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                              data-bs-html="true"
                                              data-bs-title="Provide an valid number. This number will be use to send verification code and other attachments in future">
                                                    <img
                                                        src="{{asset('public/assets/installation')}}/assets/img/svg-icons/info2.svg"
                                                        class="svg" alt="">
                                                </span>
                                    </label>

                                    <div class="number-input-wrap">
                                        <select name="phone_code" id="phone-number" class="form-select">
                                            @foreach ($phone_codes as $item)
                                                <option value="{{$item['code']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" id="phone" class="form-control" name="admin_phone" required
                                               placeholder="Ex: 9837530836">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="email" class="d-flex align-items-center gap-2 mb-2">
                                        <span class="fw-medium">Email</span>
                                        <span class="cursor-pointer" data-bs-toggle="tooltip"
                                              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                              data-bs-html="true"
                                              data-bs-title="Provide an valid email. This email will be use to send verification code and other attachments in future">
                                                    <img
                                                        src="{{asset('public/assets/installation')}}/assets/img/svg-icons/info2.svg"
                                                        class="svg" alt="">
                                                </span>
                                    </label>

                                    <input type="email" id="email" class="form-control" name="admin_email" required
                                           placeholder="Ex: jhone@doe.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="password"
                                           class="d-flex align-items-center gap-2 mb-2">Password</label>
                                    <div class="input-inner-end-ele position-relative">
                                        <input type="password" autocomplete="new-password" id="password"
                                               name="password" required class="form-control"
                                               placeholder="Ex: 8+ character" minlength="8">
                                        <div class="togglePassword">
                                            <img
                                                src="{{asset('public/assets/installation')}}/assets/img/svg-icons/eye.svg"
                                                alt="" class="svg eye">
                                            <img
                                                src="{{asset('public/assets/installation')}}/assets/img/svg-icons/eye-off.svg"
                                                alt="" class="svg eye-off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="confirm-password" class="d-flex align-items-center gap-2 mb-2">Confirm Password</label>
                                    <div class="input-inner-end-ele position-relative">
                                        <input type="password" autocomplete="new-password" id="confirm_password"
                                              name="confirm_password" class="form-control" placeholder="Ex: 8+ character" required>
                                        <div class="togglePassword">
                                            <img
                                                src="{{asset('public/assets/installation')}}/assets/img/svg-icons/eye.svg"
                                                alt="" class="svg eye">
                                            <img
                                                src="{{asset('public/assets/installation')}}/assets/img/svg-icons/eye-off.svg"
                                                alt="" class="svg eye-off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5">Complete Installation</button>
                </div>
            </form>
        </div>
    </div>
@endsection
