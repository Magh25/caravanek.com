@php
    $countryCodes =  storage_path().'/json/CountryCodes.json'; 
    $countries = (array)@json_decode(@file_get_contents($countryCodes),'true'); 
    if( empty($countries) )
        $countries = [['dial_code'=>'+966','code'=>'SA','name'=>'Saudi Arabia']]; 
@endphp
<style type="text/css">
    select#countries {
        padding: .3125rem 3rem .3125rem 1rem;
        /* background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); */
        background-repeat: no-repeat, repeat;
        background-position: right 1rem center;
        border-radius: .25rem;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        border: 1px solid #d8e2ef;
        color: #344040;
        background-size: 10px 12px;
        background-color: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.075);
        outline: none;
    }
</style>
<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="modal-content" id="sign-up">
                    <div class="modal-body">
                        <h2 class="text-center">{{ trans('plugins/real-estate::dashboard.register-title') }}</h2>
                        <br>
                        @include(Theme::getThemeNamespace() . '::views.real-estate.account.auth.includes.messages')
                        <form method="POST" class="simple-form" action="{{ route('public.account.register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="first_name" type="text"
                                                   class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                                   name="first_name" value="{{ old('first_name') }}" required autofocus
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.first_name') }}">
                                            <i class="ti-user"></i>
                                        </div>
                                        @if ($errors->has('first_name'))
                                            <span class="d-block invalid-feedback">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="last_name" type="text"
                                                   class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                                   name="last_name" value="{{ old('last_name') }}" required
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.last_name') }}">
                                            <i class="ti-user"></i>
                                        </div>
                                        @if ($errors->has('last_name'))
                                            <span class="d-block invalid-feedback">
                                                 <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="email" type="email"
                                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                   name="email" value="{{ old('email') }}" required
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.email') }}">
                                            <i class="ti-email"></i>
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="d-block invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="username" type="text"
                                                   class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"  
                                                   name="username" value="{{ old('username') }}" required
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.username') }}">
                                            <i class="ti-user"></i>
                                        </div>
                                        @if ($errors->has('username'))
                                            <span class="d-block invalid-feedback">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> 
                             <!--     <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" id="countries" name="countries">
                                            @foreach ($countries as $key => $value) 
                                                <option value="{{ $value['dial_code'] }}" data-code="{{ $value['code'] }}">{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --> 
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" id="role" name="role">
                                            <option value="c">{{ __('User') }}</option>
                                            <option value="v">{{ __('Vendor') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                   <div class="form-group">
                                        <input autocomplete="off" type="text" style="width: 59% !important;float: right;" placeholder="555-5555-555" class="form-control" name="phone-number" id="phone-number">
                                         <select  style="width: 40% !important;" class="form-control" id="countries" name="countries">
                                            @foreach ($countries as $key => $value) 
                                                <option value="{{ $value['dial_code'] }}" data-code="{{ $value['code'] }}">{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>&nbsp;
                                        <input type="hidden" style="width: 19% !important;" placeholder="+1" class="form-control" name="phone-code"id="phone-code">
                                        <input type="hidden" class="form-control" name="phone" id="phone" required>
                                    </div> 
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="password" type="password"
                                                   class="form-control{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                   name="password" required
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.password') }}">
                                            <i class="ti-unlock"></i>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="d-block invalid-feedback">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <input autocomplete="off" id="password-confirm" type="password" class="form-control"
                                                   name="password_confirmation" required
                                                   placeholder="{{ trans('plugins/real-estate::dashboard.password-confirmation') }}">
                                            <i class="ti-unlock"></i>
                                        </div>
                                    </div>
                                </div>  
                            </div> 
                            @if (setting('enable_captcha') && is_plugin_active('captcha'))
                                <div class="form-group">
                                    {!! Captcha::display() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="d-block invalid-feedback">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            @endif
                            <div class="form-group">
                                <button type="submit" class="btn btn-md full-width btn-theme-light-2 rounded">
                                    {{ trans('plugins/real-estate::dashboard.register-cta') }}
                                </button>
                            </div>

                            <div class="form-group text-center">
                                <p>{{ __('Have an account already?') }}
                                    <a href="{{ route('public.account.login') }}"
                                       class="link d-block d-sm-inline-block text-sm-left text-center">{{ __('Login') }}</a>
                                </p>
                            </div>

                            <div class="text-center">
                                {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\RealEstate\Models\Account::class) !!}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    jQuery(document).ready(function(){
        var phoneCode = jQuery('#countries').val();
        jQuery('#phone-code').val(phoneCode);

        jQuery('#countries').on('change',function(){
            var country = jQuery(this).val(); 
            jQuery('#phone-code').val(country);  
            /* concate start here */
            var phonenumber  = jQuery('#phone-number').val();
            var phonecode = jQuery('#phone-code').val();
            var finalPhoneNo = phonecode+'-'+phonenumber;
            jQuery('#phone').val(finalPhoneNo); 
        })
        jQuery('#phone-number').on('change',function(){
            var phonecode = jQuery('#phone-code').val();
            var phonenumber = jQuery(this).val();
            var finalPhoneNo = phonecode+'-'+phonenumber;
            jQuery('#phone').val(finalPhoneNo);
        })
    })  

    // const input = document.querySelector('input[name="username"]');
    // console.log(input);
    // input.addEventListener('invalid', function (event) {
    //     console.log(event.target.validity.patternMismatch)
    //     if (event.target.validity.patternMismatch) {
    //         event.target.setCustomValidity('Please tell us how we should address you.');
    //     }
    // })
    // input.addEventListener('change', function (event) {
    //   event.target.setCustomValidity('');
    // })

</script>
