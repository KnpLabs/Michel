Feature: Fetch data from packagist.org
  In order to feed the API with data
  As an Unix User
  I need to be able to save packagist's API results
  Scenario: Fetching data
    Given I have an empty database
    When I run the import command
    Then I should have data in the database