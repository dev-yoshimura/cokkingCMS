<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * 登録コンポーネント
 */
class RegistryComponent extends Component
{

    public $components = ['Convert'];
    private $__Menus;
    private $__Materials;
    private $__Recipes;

    /**
     * 初期化処理
     */
    public function initialize(array $config)
    {
        $this->__Menus = TableRegistry::get('menus');
        $this->__Materials = TableRegistry::get('materials');
        $this->__Recipes = TableRegistry::get('recipes');
    }

    /**
     * 保存
     * @param string $userID ログインユーザーID
     * @param list   $datas  postデータ
     * @return list(成功有無, エンティティ)
     */
    public function save($userID, $datas)
    {

        $materoal = null;
        $recipe = null;
        $connection = ConnectionManager::get('default');
        $connection->begin();
        try {

            list($isSuccess, $menu) = $this->__saveMenu($userID, $datas);
            if ($isSuccess) {

                list($isSuccess, $materoal) = $this->__saveMateroal($userID, $datas, $menu->id);
                if ($isSuccess) {
                    list($isSuccess, $recipe) = $this->__saveRecipe($userID, $datas, $menu->id);
                }
            }

            $entities = [
                'Menu' => $menu,
                'Materoals' => $materoal,
                'Recipes' => $recipe,
            ];

            if ($isSuccess) {
                $connection->commit();
                return array(true, $entities);
            } else {

                $connection->rollback();
                return array(false, $entities);
            }
        } catch (Exception $ex) {

            Log::write('debug', $ex);
            $connection->rollback();
            return array(false, null);
        }
    }

    /**
     * 献立保存
     * @param string $userID ログインユーザーID
     * @param list   $datas   postデータ
     * @return list(成功有無, エンティティ)
     */
    private function __saveMenu($userID, $datas)
    {

        list($isError, $entitie) = $this->validateMenu($userID, $datas);

        if ($isError === true) {
            return array(false, $entitie);
        }

        if ($this->__Menus->save($entitie)) {
            return array(true, $entitie);
        }

        return array(false, $entitie);
    }

    /**
     * 献立入力値チェック
     * @param string $userID ログインユーザーID
     * @param list   $datas   postデータ
     * @return list(エラー有無, エンティティ)
     */
    public function validateMenu($userID, $datas)
    {

        $saveData['name'] = $datas['Menu']['name'];
        $saveData['hiragana'] = $this->Convert->toHiragana($datas['Menu']['name']);
        $saveData['type'] = $datas['Menu']['type'];
        $saveData['quantity'] = $datas['Menu']['quantity'];
        $saveData['modifier'] = $userID;

        if (empty($datas['Menu']['id'])) {
            $menu = $this->__Menus->newEntity();

            $saveData['creator'] = $userID;
            $saveData['image'] = $datas['Menu']['image'];
        } else {
            $menu = $this->__Menus->get($datas['Menu']['id']);

            if (0 < $datas['Menu']['image']['size']) {
                $saveData['image'] = $datas['Menu']['image'];
            }
        }

        $entitie = $this->__Menus->patchEntity($menu, $saveData);

        $isError = false;
        if ($entitie->getErrors()) {
            $isError = true;
        }

        return array($isError, $entitie);
    }

    /**
     * 材料保存
     * @param string $userID ログインユーザーID
     * @param list   $datas  postデータ
     * @param int    $menuID 追加したメニューID
     * @return list(成功有無, エンティティ)
     */
    private function __saveMateroal($userID, $datas, $menuID)
    {

        list($isError, $entities) = $this->validateMaterial($userID, $datas, $menuID);

        if ($isError === true) {
            return array(false, $entities);
        }

        if (!empty($datas['Menu']['id'])) {
            if (!$this->__Materials->deleteAll(['menu_id' => $menuID])) {
                return array(false, $entities);
            }
        }
        if ($this->__Materials->saveMany($entities)) {
            return array(true, $entities);
        }

        return array(false, $entities);
    }

    /**
     * 材料入力値チェック
     * @param string $userID ログインユーザーID
     * @param list   $datas  postデータ
     * @param int    $menuID 追加したメニューID
     * @return list(エラー有無, エンティティ)
     */
    public function validateMaterial($userID, $datas, $menuID)
    {

        if (array_key_exists('Materoals', $datas) === false) {
            return array(true, null);
        }

        $count = count($datas['Materoals']);
        for ($i = 0; $i < $count; $i++) {

            $saveData['menu_id'] = $menuID;
            $saveData['name'] = $datas['Materoals'][$i]['name'];
            $saveData['hiragana'] = $this->Convert->toHiragana($datas['Materoals'][$i]['name']);
            $saveData['type'] = $datas['Materoals'][$i]['type'];
            $saveData['quantity'] = $datas['Materoals'][$i]['quantity'];
            $saveData['creator'] = $userID;
            $saveData['modifier'] = $userID;

            $saveDatas[] = $saveData;
        }

        $entities = $this->__Materials->newEntities($saveDatas);

        $isError = false;
        foreach ($entities as $entitie) {
            if ($entitie->getErrors()) {
                $isError = true;
            }
        }

        return array($isError, $entities);
    }

    /**
     * 作り方保存
     * @param string $userID  ログインユーザーID
     * @param list   $datas   postデータ
     * @param int    $menuID  追加したメニューID
     * @return list(成功有無, エンティティ)
     */
    private function __saveRecipe($userID, $datas, $menuID)
    {

        list($isError, $entities) = $this->validateRecipe($userID, $datas, $menuID);

        if ($isError === true) {
            return array(false, $entities);
        }

        if (!empty($datas['Menu']['id'])) {
            if (!$this->__Recipes->deleteAll(['menu_id' => $menuID])) {
                return array(false, $entities);
            }
        }

        if ($this->__Recipes->saveMany($entities)) {
            return array(true, $entities);
        }

        return array(false, $entities);
    }

    /**
     * 作り方入力値チェック
     * @param string $userID  ログインユーザーID
     * @param list   $datas   postデータ
     * @param int    $menuID  追加したメニューID
     * @return list(エラー有無, エンティティ)
     */
    public function validateRecipe($userID, $datas, $menuID)
    {

        if (array_key_exists('Recipes', $datas) === false) {
            return array(true, null);
        }

        $count = count($datas['Recipes']);
        for ($i = 0; $i < $count; $i++) {

            $saveData['menu_id'] = $menuID;
            $saveData['detail'] = $datas['Recipes'][$i]['detail'];
            $saveData['creator'] = $userID;
            $saveData['modifier'] = $userID;

            $saveDatas[] = $saveData;
        }

        $entities = $this->__Recipes->newEntities($saveDatas);

        $isError = false;
        foreach ($entities as $entitie) {
            if ($entitie->getErrors()) {
                $isError = true;
            }
        }

        return array($isError, $entities);
    }

}
