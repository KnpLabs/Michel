<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I have an empty database
     */
    public function iHaveAnEmptyDatabase()
    {
        throw new PendingException();
    }

    /**
     * @When I run the import command
     */
    public function iRunTheImportCommand()
    {
        throw new PendingException();
    }

    /**
     * @Then I should have data in the database
     */
    public function iShouldHaveDataInTheDatabase()
    {
        throw new PendingException();
    }
}
