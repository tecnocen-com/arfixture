<?php
namespace tecnocen\arfixture\tests;

use tecnocen\arfixture\ARFixture;
use tecnocen\arfixture\tests\data\Customer;
use tecnocen\arfixture\tests\data\CustomerFixture;
use Yii;

/**
 * Test the functionality for the enum extension
 * @group db
 */
class ARFixtueTest extends DatabaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        Yii::$app->set('db', $this->getConnection());
    }

    public function testCheckErrors()
    {
        $fixture = new ARFixture(['modelClass' => Customer::className()]);

        $fixture->checkErrors(
            [
                'attribute1' => 'Error1',
                'attribute2' => 'Error2',
                'attribute3' => 'Error3',
                'attribute5' => 'Error5',
            ],
            [
                'attribute2' => 'Error3',
                'attribute3' => 'Error3',
                'attribute4' => 'Not Found',
                'attribute5',
            ]
        );

        $this->assertEquals(2, $fixture->passed);
        $this->assertEquals(3, $fixture->failed);
    }

    public function testLoad()
    {
        $fixture = new CustomerFixture();
        $fixture->load();

        $this->assertEquals(3, Customer::find()->count());
        $this->assertTrue(Customer::find()
            ->andWhere([
                'name' => 'Customer 1',
                'email' => 'customer1@tecnocen.com'
            ])->exists()
        );
        $this->assertEquals(0, $fixture->failed);
        $this->assertEquals(10, $fixture->passed);
    }
}
