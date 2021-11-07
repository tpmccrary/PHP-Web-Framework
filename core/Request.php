<?php

namespace app\core;

class Request
{
    public function getPath()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethodType()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet()
    {
        return $this->getMethodType() === 'GET';
    }

    public function isPost()
    {
        return $this->getMethodType() === 'POST';
    }

    public function getParams()
    {
        $params = [];
        $query = $_SERVER['QUERY_STRING'];
        parse_str($query, $params);
        return $params;
    }

    public function getBody()
    {
        $body = [];

        if ($this->getMethodType() == 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } elseif ($this->getMethodType() == 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}