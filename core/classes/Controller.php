<?php
/**
 * Created by PhpStorm.
 * User: HP ENVY
 * Date: 10/28/2018
 * Time: 5:10 AM
 */

namespace Path;

use Path\Http\Request;
use Path\Http\Response;
use Path\RouterException;

abstract class Controller{

    protected  $request, $response;
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|null
     * @throws RouterException
     */
    final public function response(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
        $controller_name = get_class($this);
        $_method_name = strtolower($request->METHOD);
        $_method_name[0] = strtoupper($_method_name[0]);

        $_call = ('on'.$_method_name);

        if (!method_exists($this, $_call))
            throw new RouterException("Request Method does not exist");

        $_response = $this->$_call($request, $response);

        if($_response === false)
            throw new RouterException("Override \"$_call(){}\" method in  {$controller_name} to handle {$request->METHOD} Request");

        return $_response;

    }

    public function onDelete(Request $request, Response $response){
        return false;
    }

    public function onPost(Request $request, Response $response){
        return false;
    }

    public function onGet(Request $request, Response $response){
        return false;
    }

    public function onPatch(Request $request, Response $response){
        return false;
    }

    public function onRequest(Request $request, Response $response){
        return false;
    }

    public function onPut(Request $request, Response $response){
        return false;
    }

    public function onOptions(Request $request, Response $response){
        return false;
    }

}
