# Facade pattern

Facade pattern-i klasning obyektini olishda uning murakkabligini yashiradi.

### Masala

Faraz qilaylik, bizda maqola chop etadigan CMS-imiz bor bo'lsin. Har bir maqolani chop etgan paytda bu maqolani twitter-da ham chop etishimiz kerak.

Avvalo, maqolani twitter-da chop etadigan [dg/twitter-php](https://github.com/dg/twitter-php) kutubxonasini CMS-ga qo'shib olamiz. Bu kutubxona quyidagi ko'rinishda ishlar ekan:

```bash
use DG\Twitter\Twitter;

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$twitter->send('Hello, friend!');
```

E'tibor bergan bo'lsangiz, har safar maqolani twit qilish paytida, twitter obyektini olib, unga secret token-larni berib yuboryapmiz. Bundan tashqari, bizga Factory pattern-i ham kerak emas. Chunki, bu yerda biz faqat twit yuboryapmiz. Agar factory ishlatadigan bo'lsak, bu pattern qo'shimcha murakkablikni keltirib chiqaradi.

Bu paytda, obyekt yaratishning qiyinchiligini Facade pattern-i kamaytirib beradi:

`app\Facades\TwitterFacade.php`:

```bash
class TwitterFacade
{
    public static function get()
    {
        $config = config('services.twitter');

        return new Twitter($config['api_key'], $config['api_secret'], $config['api_token'], $config['api_token_secret']);
    }
}
```

Sozlamalarni `config/services.php` fayliga joylashtiramiz:

```bash
return [
    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'api_secret' => env('TWITTER_API_SECRET'),
        'api_token' => env('TWITTER_Access_TOKEN'),
        'api_token_secret' => env('TWITTER_Access_TOKEN_SECRET'),
    ],
];
```

Va nihoyat, fasadimiz quyidagicha ishlatiladi:

```bash
TwitterFacade::get()->send('Hello, friend!');
```

### Facade-ni Laravel yordamida ishlatish

Laravel Facade pattern-idan core qismida ham foydalangan. Agar, `config/app.php` faylini ochib, aliases qismini ko'rsangiz ko'plab facade-lar ro'yxatini ko'rishingiz mumkin:

```bash
'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

    ],
```

Yuqorida yaratgan `app\Facades\TwitterFacade.php` faylini quyidagicha o'zgartiramiz:

```bash
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'twitter-poster';
    }
}
```

Bu yerda yaratgan facade-imiz `Illuminate\Support\Facades\Facade` dan meros oladi. Twitter obyektiga murojaat qilish nomi sifatida `twitter-poster`-ni tanladik.

Endi, `AppServiceProvider`-ga o'tib (agar facade-lar ko'p bo'lsa, yangi `FacadeServiceProvider` nomli provider ham ochish mumkin), `twitter-poster`-ni `Twitter`-ga bog'laymiz:

```bash
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('twitter-poster', function () {
            $config = config('services.twitter');
            return new Twitter($config['api_key'], $config['api_secret'], $config['api_token'], $config['api_token_secret']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

```

Endi, facade-ni ishlatib ko'ramiz:

```bash
Route::get('/tweet', function () {
    \App\Facades\TwitterFacade::send('Hello, friend!');
});
```
