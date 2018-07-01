<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\ConditionEvaluationsTable|\Cake\ORM\Association\HasMany $ConditionEvaluations
 * @property \App\Model\Table\ConditionMaterialsTable|\Cake\ORM\Association\HasMany $ConditionMaterials
 * @property \App\Model\Table\HistoriesTable|\Cake\ORM\Association\HasMany $Histories
 * @property \App\Model\Table\SelectMenusTable|\Cake\ORM\Association\HasMany $SelectMenus
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ConditionEvaluations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ConditionMaterials', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Histories', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('SelectMenus', [
            'foreignKey' => 'user_id'
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
            ->notEmpty('name', '名前が未設定です');

        $validator
            ->email('email', FALSE, 'メールアドレスの形式が正しくありません')
            ->requirePresence('email', 'create')
            ->notEmpty('email', 'メールアドレスが未設定です');

        $validator
            ->scalar('password')
            ->maxLength('password', 255, '最大255文字です')
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'パスワードが未設定です');

        $validator
            ->integer('auth')
            ->requirePresence('auth', 'create')
            ->notEmpty('auth');

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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /**
     * 認証時の検索クエリー
     */
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->select(['id', 'email', 'password'])
            ->where(['auth' => 1]);

        return $query;
    }
}
