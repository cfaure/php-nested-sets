<?php
    $I = new AcceptanceTester($scenario);
    $I->wantTo('ensure that tree was created with 6 nodes');
    $I->amOnPage('/');
    $I->see('6 nodes');
