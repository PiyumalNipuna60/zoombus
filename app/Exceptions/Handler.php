<?php

namespace App\Exceptions;



use App\Admins;
use App\Country;
use App\Currency;
use App\Http\Controllers\Controller;
use Http\Client\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $currencies = Currency::all()->toArray();
        $ip = $_SERVER['REMOTE_ADDR'];
        $locale = Country::where('code', strtoupper(geoip($ip)->iso_code))->first(['locale']);
        if($locale) {
            $locale_by_ip = $locale->locale;
        }

        $request->attributes->add(['currencies' => $currencies]);

        //setting locale
        if (isset($locale_by_ip) && array_key_exists($locale_by_ip, config('laravellocalization.supportedLocales')) && empty(session('locale'))) {
            session()->put('locale', $locale_by_ip);
        }


        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                if($request->hasCookie('locale')) {
                    // Get cookie
                    $cookie = $request->cookie('locale');
                    // Check if cookie is already decrypted if not decrypt
                    $cookie = strlen($cookie) > 2 ? decrypt($cookie) : $cookie;
                    // Set locale
                    app()->setLocale($cookie);
                }
                $data = Controller::essentialVars();
                return response()->view('errors.' . '404', $data, 404);
            }
        }

        return parent::render($request, $exception);
    }
}
