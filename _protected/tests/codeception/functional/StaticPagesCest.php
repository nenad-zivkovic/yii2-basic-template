<?php
namespace tests\codeception\functional;

use tests\codeception\_pages\AboutPage;
use tests\codeception\_pages\ContactPage;
use Yii;

class StaticPagesCest
{   
    /**
     * =========================================================================
     * This method is called before each test method.
     * =========================================================================
     *
     * @param \Codeception\Event\TestEvent $event
     * _________________________________________________________________________
     */
    public function _before($event)
    {
    }

    /**
     * =========================================================================
     * This method is called after each test method, even if test failed.
     * =========================================================================
     *
     * @param \Codeception\Event\TestEvent $event
     * _________________________________________________________________________
     */
    public function _after($event)
    {
    }

    /**
     * =========================================================================
     * This method is called when test fails.
     * =========================================================================
     *
     * @param \Codeception\Event\FailEvent $event
     * _________________________________________________________________________
     */
    public function _fail($event)
    {
    }
    
    /**
     * =========================================================================
     * Test home page.
     * =========================================================================
     *
     * @param \codeception\FunctionalTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testHomePage($I, $scenario)
    {
        $I->wantTo('ensure that home page works');
        $I->amOnPage(Yii::$app->homeUrl);
        $I->see('My Company');
        $I->seeLink('About');
        $I->click('About');
        $I->see('This is the About page.');
    }

    /**
     * =========================================================================
     * Test about page.
     * =========================================================================
     *
     * @param \codeception\FunctionalTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testAboutPage($I, $scenario)
    {
        $I->wantTo('ensure that about page works');
        AboutPage::openBy($I);
        $I->see('About', 'h1');
    }

    /**
     * =========================================================================
     * Test contact page.
     * =========================================================================
     *
     * @param \codeception\FunctionalTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testContact($I, $scenario)
    {
        $I->wantTo('ensure that contact works');
        $contactPage = ContactPage::openBy($I);
        $I->see('Contact', 'h1');

        //-- submit form with no data --//
        $I->amGoingTo('submit contact form with no data');
        $contactPage->submit([]);

        $I->expectTo('see validations errors');
        $I->see('Contact', 'h1');
        $I->see('Name cannot be blank', '.help-block');
        $I->see('Email cannot be blank', '.help-block');
        $I->see('Subject cannot be blank', '.help-block');
        $I->see('Body cannot be blank', '.help-block');
        $I->see('The verification code is incorrect', '.help-block');

        //-- submit form with not correct email --//
        $I->amGoingTo('submit contact form with not correct email');
        $contactPage->submit([
            'name'       => 'tester',
            'email'      => 'tester.email',
            'subject'    => 'test subject',
            'body'       => 'test content',
            'verifyCode' => 'testme',
        ]);

        $I->expectTo('see that email adress is wrong');
        $I->dontSee('Name cannot be blank', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');
        $I->dontSee('Subject cannot be blank', '.help-block');
        $I->dontSee('Body cannot be blank', '.help-block');
        $I->dontSee('The verification code is incorrect', '.help-block');

        //-- submit form with correct data --//
        $I->amGoingTo('submit contact form with correct data');
        $contactPage->submit([
            'name'       => 'tester',
            'email'      => 'tester@example.com',
            'subject'    => 'test subject',
            'body'       => 'test content',
            'verifyCode' => 'testme',
        ]);

        if (method_exists($I, 'wait')) 
        {
            $I->wait(3); // only for selenium
        }
        
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');
    }
}
