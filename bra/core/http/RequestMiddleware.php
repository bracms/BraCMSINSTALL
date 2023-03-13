<?php

namespace Bra\core\http;

use Bra\core\middleware\Middleware;
use Illuminate\Support\Str;

class RequestMiddleware extends Middleware {
    function handle () {
        $request_path = ico('request')->getPathInfo();
        $params = $this->parse($request_path);
        $this->attach_params($params);
    }

    private function parse (string $result): array {
        $result = ltrim($result, "/");
        $api_routes = config('routes')['api'];
        if (isset($api_routes[$result])) {
            define("AUTH_GUARD", "api");

            return $this->parse_api($result);
        } else {
            $result = array_values(explode('/', $result));
            if (count($result) < 3) {
                $safe = config('bra_safe');
                switch (count($result)) {
                    case 0:
                        $result = [
                            $safe['default_app'],
                            $safe['default_ctrl'],
                            $safe['default_act']
                        ];
                        break;
                    case 1:
                        $result[1] = $safe['default_ctrl'];
                        $result[2] = $safe['default_act'];
                        break;
                    case 2:
                        $result[2] = $safe['default_act'];
                        break;
                }
            } else {
                if (count($result) % 2 != 1) {
                    abort(bra_res(403, 'Params parse failed!'));
                }
            }
            define("AUTH_GUARD", "web");

            return $this->parse_web($result);
        }
    }

    public function parse_api (string $result): array {
        if (count(explode("/", $result)) != 3) {
            abort(bra_res([500, 404], 'API Route Not Found ' . $result));
        }

        $datas = json_decode(BraRequest::$holder->getContent(), 1);
        if(empty($datas)){
            $datas = BraRequest::$holder->request->all();
            $datas['query'] = json_decode($datas['query'], 1);
        }
        $query = $datas['query'];
        unset($datas['query']);
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if (!preg_match($UUIDv4, $datas['bra_uuid'])) {
            if(BraRequest::$holder->getMethod()  === 'OPTIONS'){
                hour_log(BraRequest::$holder->getMethod() , chl: 'request');
                abort(bra_res([200, 200], '' ));
            }
            abort(bra_res([500, 404], 'UUID Not Found' ,data: [$datas ,BraRequest::$holder->request->all()]));
        }else{

            hour_log(BraRequest::$holder->getMethod() , chl: 'request');
        }
        $cas = explode("@", $query['page_name']);
        unset($query['page_name']);
        $controller = $cas[0];
        define("ROUTE_C", strip_tags($controller));
        $action = $cas[1];
        if (!$query['module']) {
            $ma = explode("_" . $controller . "_", $action, 2);
            define("ROUTE_M", strip_tags($ma[0]));
            define("ROUTE_A", strip_tags($ma[1]));
        } else {
            define("ROUTE_M", strip_tags($query['module']));
            define("ROUTE_A", str_replace(ROUTE_M . "_" . ROUTE_C . "_", '', $action));
        }
        unset($query['module']);
        BraRequest::$holder->request->add($datas);
        BraRequest::$holder->request->add($query);


        $query = BraRequest::$holder->request->all();
        if(empty($query)){
            abort(bra_res([200 , 200]));
        }
        return [];
    }

    public function parse_web (array $result): array {
        define("ROUTE_M", strip_tags($result[0] ?: 'index'));
        define("ROUTE_C", strip_tags($result[1] ?: 'index'));
        define("ROUTE_A", strip_tags($result[2] ?: 'index'));
        $controller = Str::studly(ROUTE_C);
        $patten = '/^[A-Za-z][A-Za-z0-9_]{1,31}$/';
        if (!preg_match($patten, $controller)) {
            abort(bra_res([403], 'Invalid Route Name!'));
        }
        if (str_contains(ROUTE_A, '__')) {
            abort(bra_res([403], 'Invalid Request, action with __ Route is Not Allowed!'));
        }
        if (!preg_match($patten, ROUTE_A)) {
            abort(bra_res(rand(520, 530), 'Invalid Request ACTION!' . ROUTE_A));
        }

        return $result;
    }

    private function attach_params ($result) {
        $params = [];
        //path params
        for ($i = 3; $i < count($result); $i++) {
            if ($i % 2 != 0) {
                $params[$result[$i]] = $result[$i + 1];
            }
        }
        BraRequest::$holder->request->add($params);
        BraRequest::$holder->request->add(BraRequest::$holder->query->all());
        BraRequest::$holder->request->add(BraRequest::$holder->files->all());
    }
}
