<?php

namespace admin\modules\blocks\mvc;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Model;

        if ($this->model->connect === false) {
            echo '<div class="pad10 bg-red700 t90 t-white t-center"><i class="im-exclamation-triangle"></i>Error connect to DB options</div>';
        }
    }

    public function show()
    {
        if ($this->model->connect === false) return false;
        $view = new View;
        $view->out($this->model->getData());
    }

    public function showOne()
    {
        if ($this->model->connect === false) return false;
        $view = new View;
        $view->outOne($this->model->getDataOne());
    }
    
    public function addNew()
    {
        if ($this->model->connect === false) return false;
        return $this->model->addNew();
    }

    public function update($id, $data)
    {
        if ($this->model->connect === false) return false;
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        if ($this->model->connect === false) return false;
        return $this->model->delete($id);
    }
}
