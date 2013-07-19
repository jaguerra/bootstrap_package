Feature: Base Feature
  In order to install my package
  As a Webmaster
  I want to go through all steps of the installers

  Scenario: Go through all steps of the installer
    Given I am on "/typo3/install/index.php?mode=123&step=1&password=joh316"
    And I press "Continue"
    And I fill in the database credentials
    And I press "Continue"
    And I fill in the database name
    And I press "Continue"
    And I select the "systemToInstallIntroduction" radio button
    And I press "Continue"
    And I fill in "password" for "password"
    And I press "Continue"
    And I press "Go to your Website"
    Then I should see "Congratulations, you have successfully installed TYPO3"

  Scenario: website has valid links
    Given I am on "/examples/site-map/"
    Then I should access all pages of site map "c118"