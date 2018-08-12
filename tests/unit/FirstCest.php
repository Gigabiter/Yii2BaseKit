<?php


use kosuhin\Yii2BaseKit\Services\BaseModelService;

class FirstCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function baseModelServiceTest(UnitTester $I)
    {
        $modelService = new BaseModelService();

        $I->assertInstanceOf(BaseModelService::class, $modelService);
    }
}
