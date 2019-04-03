<?php
use Application\Web\ { Request, Response, Received };

namespace Application\Web\Rest;

class Server {
    protected $api;
    public function __construct(ApiInterface $api) {
        $this->api = $api;
    }

    public function listen() {
        $request = new Request();
        $response = new Response($request);
        $getPost = $_REQUEST ?? [];
        $jsonData = json_decode(file_get_contents('php://input'), true);
        $jsonData = $jsonData ?? [];
        $request->setData(array_merge($getPost, $jsonData));

        if (!$this->api->authenticate($request)) {
            $response->setStatus(Request::STATUS_401);
            echo $this->api::ERROR;
            exit;
        }

        // $id = $request->getData()[$this->api::ID_FIELD] ?? NULL;
        switch (strtoupper($request->getMethod())) {
            case Request::METHOD_POST:
                $this->api->post($request, $response);
                break;
            case Request::METHOD_PUT:
                $this->api->put($request, $response);
                break;
            case Request::METHOD_DELETE:
                $this->api->delete($request, $response);
                break;
            case Request::METHOD_GET:
            default:
                $this->api->get($request, $response);
        }

        $this->processResponse($response);
        echo json_encode($response->getData());
    }

    protected function processResponse(Response $response) {
        if ($response->getHeaders()) {
            foreach ($$response->getHeaders() as $key => $value) {
                header($key . ': ' , $value, TRUE, $response->getStatus());
            }
        }
        header(Request::HEADER_CONTENT_TYPE. ': ' . Request::CONTENT_TYPE_JSON, TRUE);
        if ($response->getCookies()) foreach($response->getCookies() as $key => $value) {
            setcookie($key, $value);
        }
    }
}