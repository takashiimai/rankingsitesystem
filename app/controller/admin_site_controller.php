<?php

class admin_site_controller extends app_controller {
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
    		$this->view('admin_site_index', $views);
        } catch (Exception $e) {
        }
    }

    // 登録/編集
    public function edit($id = NULL) {
        try {
            // 種別一覧取得
            $this->model("category_model");
            $views['categorys'] = $this->category_model->get_list();

            if ($id > 0) {
                // DBから読み出す
                $this->model("site_model");
                $post = $this->site_model->select_by_id('site', $id);
                if (isset($post[0]['id']) && $post[0]['id'] > 0) {
                    $views['post'] = $post[0];
                } else {
                    throw new Exception();
                }
            } else {
                $post = array(
                    'id' => '',
                    'category_id' => '', 
                    'name' => '',
                    'url' => '',
                    'orderby' => '10000',
                );
                $views['post'] = $post;
            }
            $this->view('admin_site_edit', $views);

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
            if (!strlen($post['url'])) {
                $error[] = '※URLを入力してください。';
            }

            if (count($error)) {
                $views['error'] = $error;
                $result['status'] = 'ERROR';
                $result['html'] = $this->view('parts_admin_edit_error', $views, TRUE);
            } else {
                $this->model("site_model");
                $this->site_model->trans_start();

                $params = array(
                    ':name' => $post['name'],
                    ':category_id' => $post['category_id'],
                    ':url' => $post['url'],
                    ':orderby' => $post['orderby'],
                );
                if ($post['id'] > 0) {
                    $this->site_model->update('site', array(':id' => $post['id']), $params);
                    $id = $post['id'];
                } else {
                    $this->site_model->insert('site', $params);
                    $id = $this->site_model->get_last_insert_id();
                }

                // サイトアイテム処理
                $this->model("site_item_model");
                $this->site_item_model->delete($id);
                if (isset($post['site_item'])) {
                    foreach ($post['site_item'] as $key => $value) {
                        $params = array(
                            ':site_id'   => $id,
                            ':tag'       => $key,
                            ':meta'      => $value,
                        );
                        $this->site_item_model->insert('site_item', $params);
                    }
                }


                $this->site_model->trans_commit();

                $result['status'] = 'SUCCESS';
                $result['id'] = $id;
                $result['html'] = '';
            }

            echo json_encode($result);

        } catch (Exception $e) {
            header('HTTP', true, 400);
        }
    }

    // 登録/編集
    public function get_config_site_item() {
        try {
            $post = $this->request->post();

            // サイト設定タグ情報覧取得
            $this->model("config_site_item_model");
            $views['config_site_items'] = $this->config_site_item_model->select_by_id('config_site_item', $post['category_id'], 'category_id');

            // サイト設定情報取得
            if ($post['id'] > 0) {
                $this->model("site_item_model");
                $site_items = $this->site_item_model->select_by_id('site_item', $post['id'], 'site_id');
                foreach ($site_items as $row) {
                    $views['site_items'][ $row['tag'] ] = $row;
                }
            } else {
                $views['site_items'] = array();
            }
            echo $this->view('parts_site_item_form', $views, TRUE);

        } catch (Exception $e) {
        }
    }


}
