<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * 献立コントローラ
 */
class MenusController extends AppController
{

    /**
     * メニュー種別
     */
    public $menuTypeSelect = [
        '1' => 'サラダ',
        '2' => 'スープ',
        '3' => 'おかず',
    ];

    /**
     * メニュー量
     */
    public $menuQuantitySelect = [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
    ];

    /**
     * 材料種別
     */
    public $materialTypeSelect = [
        '1' => '野菜',
        '2' => '魚',
        '3' => '肉',
        '4' => '調味料',
        '5' => 'その他',
    ];

    /**
     * 初期処理
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Registry');
    }

    /**
     * 一覧画面
     */
    public function index()
    {

        $menus = $this->paginate($this->Menus, [
            'maxLimit' => 8,
            'order' => ['hiragana' => 'asc'],
        ]);
        $title = 'レシピ一覧';

        $this->set(compact('menus', 'title'));
    }

    /**
     * イメージ表示
     * @param type $id メニューID
     */
    public function contents($id)
    {

        $data = $this->Menus->get($id);
        $this->autoRender = false;
        $this->response->getType('image/jpeg');
        $this->response->body(stream_get_contents($data->image));
    }

    /**
     * 新規作成
     */
    public function create()
    {

        $menuTypeSelect = $this->menuTypeSelect;
        $menuQuantitySelect = $this->menuQuantitySelect;
        $materialTypeSelect = $this->materialTypeSelect;
        $entities = null;
        $title = 'レシピ新規作成';

        if ($this->request->is(['patch', 'post', 'put'])) {
            // 1回もsaveが成功してないときは（または、初回）、リクエストは【post】
            // Menusのsaveが成功して、Materialsがバリデーションエラーになり再度修正して、
            // 【登録】ボタンを押下したときリクエストが【put】になる。
            list($isSuccess, $entities) = $this->Registry->save($this->Auth->user('id'), $this->request->data());

            // エラー判定
            if ($isSuccess === true) {
                $this->Flash->success('登録しました。');
                return $this->redirect(['action' => 'index']);
            } elseif ($isSuccess == false) {
                $this->Flash->error('登録に失敗しました。');
            }
        }

        $this->set(compact('menuTypeSelect', 'menuQuantitySelect', 'materialTypeSelect', 'entities', 'title'));
    }

    /**
     * 編集
     */
    public function edit()
    {
        $menuTypeSelect = $this->menuTypeSelect;
        $menuQuantitySelect = $this->menuQuantitySelect;
        $materialTypeSelect = $this->materialTypeSelect;
        $title = 'レシピ編集';
        $entities = null;
        $datas = $this->request->getData('id');

        if ($this->request->is('post')) {

            // 献立取得
            $menu = $this->Menus
                ->find()
                ->where(['id' => $datas['id']])
                ->select(['id', 'name', 'type', 'quantity', 'modified'])
                ->first();
            $menu['modified'] = $menu['modified']->i18nFormat('YYYY/MM/dd HH:mm:ss');
            // 材料取得
            $materials = TableRegistry::get('materials');
            $materials = $materials->find()->where(['menu_id' => $menu->id])->toArray();
            // 作り方取得
            $recipes = TableRegistry::get('recipes');
            $recipes = $recipes->find()->where(['menu_id' => $menu->id])->toArray();
            // コントール名同じ名称は使えない
            // Menusは使用できない。
            // view
            // <?= $this->Form->text('Menus.name',と記載した場合，Menus[Menus][name]の値をvalueに設定する
            $entities = [
                'Menu' => $menu,
                'Materoals' => $materials,
                'Recipes' => $recipes,
            ];
        } elseif ($this->request->is(['patch', 'put'])) {

            // 排他チェック
            $menu = $this->Menus
                ->find()
                ->where(['id' => $datas['Menu']['id']])
                ->select(['modified'])
                ->first();
            $beforeModified = new Time($menu['modified']);
            $afterModified = new Time($datas['Menu']['modified']);
            if ($beforeModified->i18nFormat('yyyyMMddHHmmss') === $afterModified->i18nFormat('yyyyMMddHHmmss')) {
                // 保存
                list($isSuccess, $entities) = $this->Registry->save($this->Auth->user('id'), $datas);
                // エラー判定
                if ($isSuccess === true) {
                    $this->Flash->success('登録しました。');
                    return $this->redirect(['action' => 'index']);
                } elseif ($isSuccess == false) {
                    $this->Flash->error('登録に失敗しました。');
                }
            } else {
                $this->Flash->error('誰かが更新しました。');
                return $this->redirect(['controller' => 'menus', 'action' => 'index']);
            }
        } else {
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('menuTypeSelect', 'menuQuantitySelect', 'materialTypeSelect', 'entities', 'title'));
    }

    /**
     * 入力値チェック
     */
    public function validate()
    {

        if ($this->request->is('ajax')) {

            $this->autoRender = false;
            $userID = $this->Auth->user('id');
            $datas = $this->request->getParsedBody();
            try {
                list($isErrorMenu, $menu) = $this->Registry->validateMenu($userID, $datas);
                list($isErrorMateroal, $materoals) = $this->Registry->validateMaterial($userID, $datas, 0);
                list($isErrorRecipe, $recipes) = $this->Registry->validateRecipe($userID, $datas, 0);

                if ($materoals) {
                    foreach ($materoals as $materoal) {
                        $materoalErrors[] = $materoal->getErrors();
                    }
                } else {
                    $materoalErrors[]['empty'] = '材料がありません';
                }

                if ($recipes) {
                    foreach ($recipes as $recipe) {
                        $recipeErrors[] = $recipe->getErrors();
                    }
                } else {
                    $recipeErrors[]['empty'] = '作り方がありません';
                }

                $errors = [
                    'isException' => false,
                    'Menu' => $menu->getErrors(),
                    'Materoals' => $materoalErrors,
                    'Recipes' => $recipeErrors,
                ];

                if ($isErrorMenu === true || $isErrorMateroal === true || $isErrorRecipe === true) {
                    $this->response->body(json_encode($errors));
                } else {
                    $this->response->body(json_encode(null));
                }
            } catch (Exception $ex) {
                $this->log($ex);
                $errors = [
                    'isException' => true,
                ];
                $this->response->body(json_encode($errors));
            }

        }
    }
}
