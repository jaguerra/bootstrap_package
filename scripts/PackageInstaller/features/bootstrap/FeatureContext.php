<?php

use Behat\Behat\Context\ClosuredContextInterface,
	Behat\Behat\Context\TranslatedContextInterface,
	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
	Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext {

	/**
	 * @array
	 */
	protected $parameters = array();
	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 *
	 * @param array $parameters context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters) {
		$this->parameters = $parameters;
	}

	/**
	 * @Given /^I wait "([^"]*)" seconds$/
	 */
	public function iWaitSeconds($seconds) {
		sleep($seconds);
	}

	/**
	 * @Given /^I fill in the database credentials$/
	 */
	public function iFillInTheDatabaseCredentials() {
		return array(
			new Behat\Behat\Context\Step\When(sprintf('I fill in "Username" with "%s"', $this->parameters['database']['user'])),
			new Behat\Behat\Context\Step\When(sprintf('I fill in "Password" with "%s"', $this->parameters['database']['password'])),
			new Behat\Behat\Context\Step\When(sprintf('I fill in "Host" with "%s"', $this->parameters['database']['host'])),
		);
	}

	/**
	 * @Given /^I fill in the database name$/
	 */
	public function iFillInTheDatabaseName() {
		return array(
			new Behat\Behat\Context\Step\When(sprintf('I fill in "t3-install-123-newdatabase" with "%s"', $this->parameters['database']['databaseName'])),
		);
	}

	/**
	 * @Then /^I should access all pages of site map "([^"]*)"$/
	 */
	public function iShouldAccessAllPagesOfSiteMap($selector) {

		$page = $this->getSession()->getPage();
		$locator = sprintf('#%s a', $selector);
		$elements = $page->findAll('css', $locator);

		$steps = array();
		foreach ($elements as $element) {
			/** @var \Behat\Mink\Element\NodeElement $element */
			$steps[] = new Behat\Behat\Context\Step\When(sprintf('I print out page "%s"', $element->getAttribute('href')));
			$steps[] = new Behat\Behat\Context\Step\When(sprintf('I go to "%s"', $element->getAttribute('href')));
			$steps[] = new Behat\Behat\Context\Step\Then('the response status code should be 200');
		}
		return $steps;
	}

	/**
	 * @When /^I print out page "([^"]*)"$/
	 */
	public function iPrintOutThePage($page) {
		$string = sprintf('Visiting page ' . $page);
		echo "\033[36m    ->  " . strtr($string, array("\n" => "\n|  ")) . "\033[0m\n";
	}

	/**
	 * @Given /^I select the "([^"]*)" radio button$/
	 */
	public function iSelectTheRadioButton($label) {
		$radioButton = $this->getSession()->getPage()->findField($label);
		if (null === $radioButton) {
			throw new ElementNotFoundException(
				$this->getSession(), 'form field', 'id|name|label|value', $label);
		}
		$value = $radioButton->getAttribute('value');
		$this->fillField($label, $value);
	}
}

?>