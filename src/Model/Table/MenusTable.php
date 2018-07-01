<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Menus Model
 *
 * @property \App\Model\Table\MaterialsTable|\Cake\ORM\Association\HasMany $Materials
 * @property \App\Model\Table\RecipesTable|\Cake\ORM\Association\HasMany $Recipes
 *
 * @method \App\Model\Entity\Menu get($primaryKey, $options = [])
 * @method \App\Model\Entity\Menu newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Menu[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Menu|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Menu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Menu[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Menu findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MenusTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('menus');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Materials', [
            'foreignKey' => 'menu_id'
        ]);
        $this->hasMany('Recipes', [
            'foreignKey' => 'menu_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255, '最大255文字です')
            ->requirePresence('name', 'create')
            ->notEmpty('name', '料理名が未設定です');

        $validator
            ->scalar('hiragana')
            ->maxLength('hiragana', 255)
            ->requirePresence('hiragana', 'create')
            ->notEmpty('hiragana');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type', '種別が未設定です');

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity', '量が未設定です');

        $validator
            ->requirePresence('image', 'create')
            ->notEmpty('image', '画像が未設定です')
            ->add('image', [
                'uploadedFile' => [
                    'rule' => ['uploadedFile', ['types' => ['image/jpeg'], 'maxSize' => '20KB']],
                    'last' => true,
                    'message' => 'JPEGファイルを選択してください。 (max size is 20KB).'
            ]]);

        $validator
            ->integer('creator')
            ->requirePresence('creator', 'create')
            ->notEmpty('creator');

        $validator
            ->integer('modifier')
            ->requirePresence('modifier', 'create')
            ->notEmpty('modifier');

        return $validator;
    }
    
    public function beforeSave($event, $entity, $options) {
        
        if ($entity->image['error'] === UPLOAD_ERR_OK) {
            $entity->image = $this->_buildImage($entity->image);
        } else {
            unset($entity->image);
        }
    }

    protected function _buildImage($image) {

        $ret = file_get_contents($image['tmp_name']);
        if ($ret === false) {
            throw new RuntimeException('Can not get image image.');
        }

        return $ret;
    }
}
