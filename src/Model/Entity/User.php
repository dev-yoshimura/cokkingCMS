<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $auth
 * @property \Cake\I18n\FrozenTime $created
 * @property int $creator
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $modifier
 *
 * @property \App\Model\Entity\ConditionEvaluation[] $condition_evaluations
 * @property \App\Model\Entity\ConditionMaterial[] $condition_materials
 * @property \App\Model\Entity\History[] $histories
 * @property \App\Model\Entity\SelectMenu[] $select_menus
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'email' => true,
        'password' => true,
        'auth' => true,
        'created' => true,
        'creator' => true,
        'modified' => true,
        'modifier' => true,
        'condition_evaluations' => true,
        'condition_materials' => true,
        'histories' => true,
        'select_menus' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($value)
    {
        if(strlen($value)) {
            $hasher = new DefaultPasswordHasher();
            
            return $hasher->hash($value);
        }
    }
}
