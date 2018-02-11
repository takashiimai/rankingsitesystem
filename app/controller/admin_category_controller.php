<?php

class admin_category_controller extends app_controller {
    /**
     * construct
     *
     * @param
     * @return
     */
    public function __construct() {
        parent::__construct();
    }

    // 一覧
    public function index() {
        try {
            $this->model("category_model");
            $views['lists'] = $this->category_model->get_list();

    		$this->view('admin_category_index', $views);
        } catch (Exception $e) {
        }
    }

    // 登録/編集
    public function edit($id = NULL) {
        try {
            $item = array();
            if ($id > 0) {
                // DBから読み出す
                $this->model("category_model");
                $post = $this->category_model->select_by_id('category', $id);
                if (isset($post[0]['id']) && $post[0]['id'] > 0) {
                    $views['post'] = $post[0];
                } else {
                    throw new Exception();
                }

                // サイトアイテムを取得
                $this->model("config_site_item_model");
                $item = $this->config_site_item_model->select_by_id('config_site_item', $post[0]['id'], 'category_id');
            } else {
                $post = array(
                    'id' => '',
                    'name' => '',
                    'slug' => '',
                    'templete' => '',
                );
                $views['post'] = $post;
            }
            $views['site_item_lists'] = $item;

            $this->view('admin_category_edit', $views);
        } catch (Exception $e) {
        }
    }



    // 登録/変更
    public function edit_post() {
        try {
            $post = $this->request->post();
            $error = array();
            if (!strlen($post['name'])) {
                $error[] = '※種別名を入力してください。';
            }
            if (!preg_match('/^[a-zA-Z0-9]{4,200}$/', $post['slug'])) {
                $error[] = '※スラッグを半角英数字 4文字以上で入力してください。';
            }

            if (count($error)) {
                $views['error'] = $error;
                $result['status'] = 'ERROR';
                $result['html'] = $this->view('parts_admin_edit_error', $views, TRUE);
            } else {
                $this->model("category_model");
                $params = array(
                    ':name' => $post['name'],
                    ':slug' => $post['slug'],
                    ':templete' => $post['templete'],
                );
                if ($post['id'] > 0) {
                    $this->category_model->update('category', array(':id' => $post['id']), $params);
                    $id = $post['id'];
                } else {
                    $this->category_model->insert('category', $params);
                    $id = $this->category_model->get_last_insert_id();
                }

                $result['status'] = 'SUCCESS';
                $result['id'] = $id;
                $result['html'] = '';
            }

            echo json_encode($result);

        } catch (Exception $e) {
            header('HTTP', true, 400);
        }
    }

    // サイトアイテム登録
    public function add_site_item() {
        try {
            $post = $this->request->post();
            $error = array();
            if (!strlen($post['name'])) {
                $error[] = '※種別名を入力してください。';
            }
            if (!preg_match('/^[a-zA-Z0-9_\-]{3,200}$/', $post['slug'])) {
                $error[] = '※スラッグを半角英数字 4文字以上で入力してください。';
            }

            if (count($error)) {
                $views['error'] = $error;
                $result['status'] = 'ERROR';
                $result['html'] = $this->view('parts_admin_edit_error', $views, TRUE);
            } else {
                $this->model("config_site_item_model");
                $params = array(
                    ':category_id' => $post['category_id'],
                    ':name' => $post['name'],
                    ':slug' => $post['slug'],
                );
                if ($post['id'] > 0) {
                    $this->config_site_item_model->update('config_site_item', array(':id' => $post['id']), $params);
                    $id = $post['id'];
                } else {
                    $max = $this->config_site_item_model->select('SELECT MAX(orderby) AS max FROM config_site_item WHERE category_id = :category_id', array(':category_id' => $post['category_id']));
                    $params[':orderby'] = $max[0]['max'] + 10;
                    $this->config_site_item_model->insert('config_site_item', $params);
                    $id = $this->config_site_item_model->get_last_insert_id();
                }

                $result['status'] = 'SUCCESS';
                $result['id'] = $id;
                $result['html'] = '';
            }

            echo json_encode($result);

        } catch (Exception $e) {
            header('HTTP', true, 400);
        }
    }


    // サイトアイテム削除
    public function delete_site_item() {
        try {
            $post = $this->request->post();
            $error = array();
            if (!strlen($post['config_site_item_id'])) {
                $error[] = '※IDを入力してください。';
            }

            if (count($error)) {
                $views['error'] = $error;
                $result['status'] = 'ERROR';
                $result['html'] = $this->view('parts_admin_edit_error', $views, TRUE);
            } else {
                $this->model("config_site_item_model");
                $this->model("site_item_model");
                $this->config_site_item_model->trans_start();

                $params = array(
                    ':id' => $post['config_site_item_id'],
                );
                $this->config_site_item_model->query('DELETE FROM config_site_item WHERE id = :id', $params);


                $params = array(
                    ':tag' => $post['slug'],
                    ':category_id' => $post['category_id'],
                );
                $query = 'DELETE FROM site_item WHERE site_id IN (SELECT id FROM site WHERE category_id = :category_id) AND tag = :tag ';
                $this->site_item_model->query($query, $params);

                $this->config_site_item_model->trans_commit();

                $result['status'] = 'SUCCESS';
                $result['html'] = '';
            }

            echo json_encode($result);

        } catch (Exception $e) {
            header('HTTP', true, 400);
        }
    }

    // 表示順変更
    public function change_orderby_config_site_item() {
        try {
            $post = $this->request->post();

            $this->model("config_site_item_model");
            $cnt = count($post['orderby']);
            for($i = 0; $i < $cnt; $i++) {
                $key = array(
                    ':id' => $post['id'][ $i ],
                );
                $params = array(
                    ':orderby' => $post['orderby'][ $i ],
                );
                $this->config_site_item_model->update('config_site_item', $key, $params);
            }

            $result['status'] = 'SUCCESS';
            echo json_encode($result);
        } catch (Exception $e) {
            header('HTTP', true, 400);
        }
    }

}

