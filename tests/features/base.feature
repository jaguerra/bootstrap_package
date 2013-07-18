Feature: Base Feature
  In order to ensure that my website has not "dead" pages
  As a Visitor
  I want to make sure I can see something relevant on every page

  Scenario: Page "Welcome" has relevant content
    Given I am on "/"
    Then I should see "Congratulations, you have successfully installed TYPO3"

  Scenario: Page "/examples/site-map/" has relevant content
    Given I am on "/examples/site-map/"
    Then I should access all pages of site map "c118"

  Scenario: Page "/examples-extended/news/" has relevant content
    Given I am on "/examples-extended/news/"
    Then I should see "All news Articles"

  Scenario: Click a News in list view and check whether it opens the detail view of News
    Given I am on "/examples-extended/news/"
    And I follow "TYPO3 - An idea is born"
    Then I should be on "/examples-extended/news/d/article/typo3-an-idea-is-born/"
    And I should see "TYPO3 - An idea is born"

  Scenario: Click go back link in detail view and check whether it goes to list view of News
    Given I am on "/examples-extended/news/d/article/typo3-an-idea-is-born/"
    And I follow "Back"
    Then I should be on "/examples-extended/news/"