<?php
namespace Application\Acl;

use PDO;
use Application\Database\Connection;
use Psr\Http\Message\ { RequestInterface, ResponseInterface };
use Application\MiddleWare\ { Response, TextStream };

class DbTabl implements AuthenticateInterface {
    const ERROR_AUTH = 'ERROR: authentication error';
    protected $conn;
    protected $table;

    public function __construct(Connection $conn, $tableName) {
        $this->conn = $conn;
        $this->table = $tableName;
    }

    public function login(RequestInterface $requset): ResponseInterface {
        $code = 401;
        $info = false;
        $body = new TextStream(self::ERROR_AUTH);
        $params = json_decode($requset->getBody()->getContents());
        $response = new Response();
        $username = $params->username ?? false;

        if ($username) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
            $stmt = $this->conn->pdo->prepare($sql);
            $stmt->execute([$username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if (password_verify($params->password, $row['password'])) {
                    unset($row['password']);
                    $body = new TextStream(json_encode($row));
                    $response->withBody($body);
                    $code = 202;
                    $info = $row;
                }
            }
        }
        return $response->withBody($body)->withStatus($code);
    }
}
