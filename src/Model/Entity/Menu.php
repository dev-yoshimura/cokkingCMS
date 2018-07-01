<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Menu Entity
 *
 * @property int $id
 * @property string $name
 * @property string $hiragana
 * @property int $type
 * @property int $quantity
 * @property string|resource $image
 * @property \Cake\I18n\FrozenTime $created
 * @property int $creator
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $modifier
 *
 * @property \App\Model\Entity\Material[] $materials
 * @property \App\Model\Entity\Recipe[] $recipes
 */
class Menu extends Entity
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
        'hiragana' => true,
        'type' => true,
        'quantity' => true,
        'image' => true,
        'created' => true,
        'creator' => true,
        'modified' => true,
        'modifier' => true,
        'materials' => true,
        'recipes' => true
    ];
}
