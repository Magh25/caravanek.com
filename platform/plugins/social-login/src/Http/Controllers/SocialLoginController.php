<?php
namespace Botble\SocialLogin\Http\Controllers;
use  Firebase\JWT\JWT;
use  Firebase\JWT\JWK;  
use Assets;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Botble\SocialLogin\Http\Requests\SocialLoginRequest;
use Exception;
use Illuminate\Support\Str;
use RvMedia;
use Socialite;


class SocialLoginController extends BaseController
{

    /**
     * Redirect the user to the {provider} authentication page.
     *
     * @param string $provider
     * @return mixed
     */
    public function redirectToProvider($provider)
    {
        $this->setProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     */
    protected function setProvider(string $provider)
    {
        // $socialCallback = str_replace("http","http",route('auth.social.callback', $provider));
        $socialCallback = str_replace("http","http",route('auth.social.callback', $provider));
        // dd($socialCallback);
        config()->set([
            'services.' . $provider => [
                'client_id'     => setting('social_login_' . $provider . '_app_id'),
                'client_secret' => setting('social_login_' . $provider . '_app_secret'),
                'redirect'      => $socialCallback,
            ],
        ]);  
        return true;
    }


    /**
     * Obtain the user information from {provider}.
     * @param string $provider
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function handleProviderCallback($provider, BaseHttpResponse $response)
    {
        $this->setProvider($provider);
        try {
            /**
             * @var \Laravel\Socialite\AbstractUser $oAuth
             */
            $oAuth = Socialite::driver($provider)->user();
        } catch (Exception $ex) {
            return $response
                ->setError() 
                ->setNextUrl(route('public.account.login'))
                ->setMessage($ex->getMessage());
        }
        
        if (!$oAuth->getEmail()) {
            return $response
                ->setError()
                ->setNextUrl(route('public.account.login'))
                ->setMessage(__('Cannot login, no email provided!'));
        }

        $user = app(AccountInterface::class)->getFirstBy(['email' => $oAuth->getEmail()]);

        if (!$user) {
            $firstName = implode(' ', explode(' ', $oAuth->getName(), -1));

            $avatarId = null;
            try {
                $url = $oAuth->getAvatar();
                if ($url) {
                    $info = pathinfo($url);
                    $contents = file_get_contents($url);
                    $file = '/tmp/' . $info['basename'];
                    file_put_contents($file, $contents);
                    $fileUpload = new UploadedFile($file, Str::slug($oAuth->getName()) . '.png', 'image/png', null,
                        true);
                    $result = RvMedia::handleUpload($fileUpload, 0, 'accounts');
                    if (!$result['error']) {
                        $avatarId = $result['data']->id;
                    }
                }
            } catch (Exception $exception) {
                info($exception->getMessage());
            }

            $user = app(AccountInterface::class)->createOrUpdate([
                'first_name' => $firstName,
                'last_name'  => trim(str_replace($firstName, '', $oAuth->getName())),
                'email'      => $oAuth->getEmail(),
                'password'   => bcrypt(Str::random(36)),
                'avatar_id'  => $avatarId,
            ]);

            $user->confirmed_at = now();
            $user->save();
        }

        Auth::guard('account')->login($user, true);

        return $response
            ->setNextUrl(route('public.account.dashboard'))
            ->setMessage(trans('core/acl::auth.login.success'));
    }




    /**
     * Obtain the user information from {provider}.
     * @param string $provider
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function handleAppleProviderCallback(SocialLoginRequest $request , BaseHttpResponse $response)
    {  
        $client_authorization_code =  $request->except('authCode');
        $id_token = $request->except('idToken'); 
        $teamId = "338VQF75YD" ;  
        $clientId = "com.caravanek.webapp";
        $privKey = file_get_contents("/var/www/html/dev-caravan/AuthKey_RKD44DMTFR.p8"); 
        $keyID = "RKD44DMTFR" ;  


        $apple_jwk_keys = json_decode(
            file_get_contents(
                "https://appleid.apple.com/auth/keys"
            ), null, 512, JSON_OBJECT_AS_ARRAY
        ); 

        $keys = array();
        foreach($apple_jwk_keys['keys'] as $key)
            $keys[] = (array)$key;
        $jwks = ['keys' => $keys];

        
        $header_base_64 = explode('.', $client_authorization_code['idToken'])[0];
      
        $kid = JWT::jsonDecode(JWT::urlsafeB64Decode($header_base_64));
         
        $kid = $kid->kid;

        $public_key = JWK::parseKeySet($jwks);
        $public_key = $public_key[$kid]; 
        
        $payload = array(
         "iss" => $teamId,
         'aud' => 'https://appleid.apple.com',
         'iat' => time(),
         'exp' => time() + 3600,
         'sub' => $clientId
        );
        
        $client_secret = JWT::encode($payload, $privKey, 'ES256', $keyID);
        
        $post_data = [
          'client_id' => $clientId,
          'grant_type' => 'authorization_code',
          'code' => $id_token['authCode'],
          'client_secret' => $client_secret
        ];
     
        $ch = curl_init("https://appleid.apple.com/auth/token");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
           'Accept: application/x-www-form-urlencoded',
           'User-Agent: curl',  //Apple requires a user agent header at the token endpoint
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $curl_response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($curl_response, true);
        $refresh_token = $data['refresh_token'];
        
        $claims = explode('.', $data['id_token'])[1];
        $claims = json_decode(base64_decode($claims));
        
        if(!empty($claims)){
            $email = $claims->email;
            $firstName = $claims->email;
            $user = app(AccountInterface::class)->getFirstBy(['email' => $email ]);

            if (!$user) {
                $avatarId = null;  
                $user = app(AccountInterface::class)->createOrUpdate([
                    'first_name' => $firstName,
                    'last_name'  => '',
                    'email'      => $email,
                    'password'   => bcrypt(Str::random(36)),
                    'avatar_id'  => $avatarId,
                ]);
                $user->confirmed_at = now();
                $user->save();
            }
            Auth::guard('account')->login($user, true);
            return $response
                ->setNextUrl(route('public.account.dashboard'))
                ->setMessage(trans('core/acl::auth.login.success'));

        }
        // echo json_encode($claims);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/social-login::social-login.settings.title'));

        Assets::addScriptsDirectly('vendor/core/plugins/social-login/js/social-login.js');

        return view('plugins/social-login::settings');
    }

    /**
     * @param SocialLoginRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     */
    public function postSettings(SocialLoginRequest $request, BaseHttpResponse $response, SettingStore $setting)
    {
        foreach ($request->except(['_token']) as $settingKey => $settingValue) {
            $setting->set($settingKey, $settingValue);
        }

        $setting->save();

        return $response
            ->setPreviousUrl(route('social-login.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
