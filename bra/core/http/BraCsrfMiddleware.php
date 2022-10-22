<?php

namespace Bra\core\http;

use Bra\core\middleware\Middleware;
use Bra\core\session\BraSession;
use Exception;
use Illuminate\Support\Str;

class BraCsrfMiddleware extends Middleware {

    /**
     * @throws Exception
     */
    public static function csrf_token_form ($name = "_token"): string {
        $roken = self::get_csrf_token($name);

        return <<<EVBUFFER_EOF
<input type='hidden' name='$name' value='$roken'/>
EVBUFFER_EOF;
    }

    /**
     * @throws Exception
     */
    public static function get_csrf_token ($token_name = "_token") {
        $token = BraSession::get($token_name);
        if (!$token) {
            $token = self::create_csrf_token($token_name);
        }

        return $token;
    }

    /**
     * @throws Exception
     */
    public static function create_csrf_token ($token_name = "_token"): string {
        $token = bin2hex(random_bytes(10));
        BraSession::set($token_name, $token);

        return $token;
    }

    function handle () {

        if( AUTH_GUARD === 'api'){
            return;
        }

        if (BraRequest::$holder->isMethod("post")) {
            if($this->is_skip_csrf()){
                return;
            }
            if (!self::is_csrf_valid(BraRequest::$holder->request->get("_token"))) {
                hour_log(" csrf valid failed");
                abort(bra_res(500, 'csrf valid failed'));
            }
        }
    }

    public function is_skip_csrf (): bool {
        $path = join('/' , [ROUTE_M , ROUTE_C , ROUTE_A]);
        foreach (config('cors.paths') as $pattern){
            if (Str::is($pattern, $path)) {
                return  true;
            }
        }
        return  false;
    }

    public static function is_csrf_valid ($token, $token_name = "_token"): bool {
        return $token && self::get_csrf_token($token_name) === $token;
    }
}
