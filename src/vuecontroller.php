<?php
namespace Yudhees\LaravelVueController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
class vuecontroller {
    public function __invoke(Request $request,$controller,$method){
        $controller =  App::make("App\Http\Controllers\\{$controller}");
        if (!method_exists($controller, $method)) {
            return Response::json(['error' => 'Function does not exist'], 404);
        }
        $params=$request->all();
         return call_user_func_array([$controller, $method], $params);
    }
}
