<?php
namespace Applcation\Web\Rest;

use Applcation\Web\ { Request, Response };

interface ApiInterface {
    public function get(Request $request, Response $response);
    public function put(Request $request, Response $response);
    public function post(Request $request, Response $response);
    public function delete(Request $request, Response $response);
    public function authenticate(Request $request);
}
