<?php

namespace admin\log;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Model;
    }

    public function show()
    {
        if ($this->model->connect === false) {
            echo 'Error connect to DB log';
            return;
        }

        $view = new View;
        $view->out($this->model->getData());
    }
}
