<?php

namespace tecnocen\arfixture\tests\data;

class CustomerFixture extends \tecnocen\arfixture\ARFixture
{
    public $modelClass = 'tecnocen\arfixture\tests\data\Customer';

    protected function getData()
    {
        return [
            ['name' => 'Customer 1', 'email' => 'customer1@tecnocen.com'],
            ['name' => 'Customer 2', 'email' => 'customer2@tecnocen.com'],

            'autoemail' => [
                'name' => 'Customer Auto',
                'scenario' => 'autoemail',
            ],

            'required' => [
                'attributeErrors' => ['name', 'email'],
            ],
            'invalid' => [
                'name' => 123,
                'email' => 'xyz',
                'attributeErrors' => ['name', 'email'],
            ],
            'unique' => [
                'name' => 'Customer 1',
                'email' => 'customer1@tecnocen.com',

                'attributeErrors' => ['email'],
            ],
            'autoemail_required' => [
                'attributeErrors' => ['name', 'email'],
            ],
        ];
    }
}
