<?php

class index_controller extends app_controller {

    public function index() {
        $this->model('category_model');
        $result = $this->category_model->get_list();
        var_dump($result);
    }
}

