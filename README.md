tecnocen Yii2 Active Record Fixture
=========================

[![Latest Stable Version](https://poser.pugx.org/tecnocen-com/yii2-arfixture/v/stable)](https://packagist.org/packages/tecnocen-com/yii2-arfixture) [![Total Downloads](https://poser.pugx.org/tecnocen-com/yii2-arfixture/downloads)](https://packagist.org/packages/tecnocen-com/yii2-arfixture) [![Latest Unstable Version](https://poser.pugx.org/tecnocen-com/yii2-arfixture/v/unstable)](https://packagist.org/packages/tecnocen-com/yii2-arfixture) [![License](https://poser.pugx.org/tecnocen-com/yii2-arfixture/license)](https://packagist.org/packages/tecnocen-com/yii2-arfixture)

Library to load data fixutres using using the methods defined by `yii\db\ActiveRecord` and show progress log.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require --prefer-dist "tecnocen/yii2-arfixture:*"
```

or add

```
"tecnocen/yii2-arfixture": "*"
```

to the `require` section of your `composer.json` file.

## Differences

`yii\test\Fixture` and `yii\test\ActiveF`ixture load data to the tables using
the name of the table and the `yii\db\Schema::insert()` method which means all
the methods defined in the model such as validations, events, behaviors and even
table prefix are ignored.

`tecnocen\arfixutre\ARFixture` loads fixtures by creating a model using the
`$modelClass` property and then passes by all the workflow of saving the data
using `ActiveRecord` considering scenarios, exceptions, events, safe attributes,
validation errors and showing detailed information to the user of the procedure.

| \                           | ARFixture              | ActiveFixture & Fixture
| --------------------------- | ---------------------- | -----------------------
| How is the data saved?      | `ActiveRecord::save()` | `Schema::insert()`
| Works will all the db's     | Without modification   | Have to change classes
| Support to change scenario  | :+1:                   | :-1:
| Support ActiveRecord events | :+1:                   | :-1:
| Support Behaviors           | :+1:                   | :-1:
| Shows details on each row   | :+1:                   | :-1:
| Check validated attributes  | :+1:                   | :-1:
| Shows how many tests passed | :+1:                   | :-1:
| Shows how many tests failed | :+1:                   | :-1:
| Support silent mode         | :+1:                   | :-1:
| Exception handling          | :+1:                   | :-1:

## Usage

### ARFixture

```php
class UserFixture extends ARFixture
{
    public $modelClass = 'common\models\User';
}
```

If [[$dataFile]] is not defined then it will seek on the `data/` subfolder the
file with the same name as this class except for the `Fixture` keyword at the
end. Example: `UserFixture` will return the file `data/User.php` or  an empty
array when the file can not found.

The data must follow this structure

```php
return [
    // record with no explicit alias, will only show the key number on the log.
    [
        'username' => 'faryshta',
        'name' => 'Angel',
        'lastname' => 'Guevara',
        'email' => 'angeldelcaos@gmail.com',
    ],

    // record with explicit alias, will be shown on the log
    'duplicated' => [
        'username' => 'faryshta',
        'name' => 'Angel',
        'lastname' => 'Guevara',
        'email' => 'angeldelcaos@gmail.com',

        // optional, will apply the scenario before loading the models.
        'scenario' => 'api-save',

        // optional, will check the validation errors.
        'attributeErrors' => [
            // will check that username has this exact validation error.
            'username' => 'Username already in use',
            // will check that email has any validation error
            'email',
            // the other attributes are not expected to have a validation error.
        ],
    ]    
];
```

Each row can contain the following special options:

- attributeErrors: array list of expected validation errors in the format
  ```php
  [
   'attribute1', // will check that it contains any error.
   'attribute2' => 'Error Message' // This has to be the error found.
  ]
  ```
  > Warning: If this option is defined the record won't be saved even if
  > it passes all the validations.

- scenario: string to be used as scenario for the model to handle
  the methods `Model::load()` and `Model::validate()`, if not defined
  the [[$scenarioDefault]] will be used

## Documentation

TODO

## License

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.
