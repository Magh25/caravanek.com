@if (setting('social_login_facebook_enable', false) || setting('social_login_google_enable', false) || setting('social_login_github_enable', false) || setting('social_login_linkedin_enable', false))
    <div class="modal-divider"><span>{{ __('Or login via') }}</span></div>    
    <div class="login-options social-login">
        <ul> 
            @if (setting('social_login_twitter_enable', false))
                <!-- <li>
                    <a href="{{ route('auth.social', 'twitter') }}" class="btn connect-twitter">
                        <i class="ti-twitter"></i>{{ __('Twitter') }}
                    </a>
                </li> -->

            @endif
            <li> 
                <div id="appleid-signin" style="height:40px;cursor: pointer;margin-left: 3%;" data-color="black" data-border="true" data-type="sign in"></div>
                <!-- <div id="appleid-signin" class="signin-button" data-color="white" data-border="false" data-type="sign in"></div> -->
            </li>  
            <!--   -->
            
            @if (setting('social_login_google_enable', false))
                <!-- <li>
                    <a href="{{ route('auth.social', 'google') }}" class="btn connect-google"><i class="ti-google"></i>{{ __('Google') }}</a>
                </li> -->
            <div class="row mt-3"  style="margin-top: 10px;">
                <div class="col-md-3" >
                    <a class="btn btn-outline-dark" href="{{ route('auth.social', 'google') }}" role="button" style="text-transform:none;">
                        <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                        Login with Google
                    </a>
                </div>
            </div>
            @endif
             
            @if (setting('social_login_github_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'github') }}" class="btn btn-dark github connect-github"><i class="ti-github"></i>{{ __('Github') }}</a>
                </li>
            @endif
            @if (setting('social_login_linkedin_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'linkedin') }}" class="btn linkedin connect-linkedin"><i class="ti-linkedin"></i>{{ __('Linkedin') }}</a>
                </li>
            @endif
        </ul>
    </div>
@endif

<script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
