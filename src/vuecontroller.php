<?php
namespace Yudhees\LaravelVueController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
class vuecontroller {
    public function __invoke(Request $request){
        $request->validate([
            'controller'=>'required',
            'function'=>'required',
        ]);
        $function=$request->function;
        $controller =  App::make("App\Http\Controllers\\{$request->controller}");
        if (!method_exists($controller, $function)) {
            return Response::json(['error' => 'Function does not exist'], 404);
        }
        $params=$request->params;
         return call_user_func_array([$controller, $function], $params);
    }
}
