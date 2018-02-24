<?php

class top_controller extends app_controller {

    public function index($slug = "") {
        try {
            $this->model("category_model");
            $category = $this->category_model->get_list($slug);
            $views['category'] = $category[0];
        
            $this->model("site_model");
            $views['sites'] = $this->site_model->get_list_with_items($slug);
            $this->view('top_index', $views);
        } catch  (Exception $e) {
            echo "ERR";
            exit;
        }
    }
}

