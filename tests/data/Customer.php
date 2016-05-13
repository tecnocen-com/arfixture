<?php
namespace tecnocen\arfixture\tests\data;

/**
 * @property integer $id
 * @property string $email
 * @property string $name
 */
class Customer extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'customer';
    }
    public function rules()
    {
        return [
            [
                ['email'],
                'default',
                'value' => function ($model) {
                    return strtolower(preg_replace('/\s+/', '', $model->name))
                        . '@tecnocen.com';
                },
                'on' => 'autoemail',
            ],
            [['email', 'name'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['name'], 'string'],
        ];
    }
}
