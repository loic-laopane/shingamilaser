<?php

use AppBundle\Entity\Game;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;

/**
 * Defines application features from the specific context.
 */
class GameContext extends MinkContext
{
    use KernelDictionary;

    private $title;

    private $game;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->title = 'NewFakeGame';
    }

    /**
     * @BeforeScenario @create_fake_game
     */
    public function createGame()
    {
        $this->game = new Game();
        $this->game->setTitle($this->title);
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $manager->persist($this->game);
        $manager->flush();
    }

    /**
     * @AfterScenario @create_fake_game
     */
    public function deleteGame()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $game = $em->getRepository(Game::class)->findOneBy(['title' => $this->title]);
        if(!$game instanceof Game)
        {
            throw new Exception('The fake game does not exist');
        }

        $em->remove($game);
        $em->flush();
    }

    /**
     * @Given I am on the edit game page
     */
    public function iAmOnTheEditGamePage()
    {
        if(!$this->game instanceof Game) {
            $this->createGame();
        }

        $this->visitPath('/staff/game/'.$this->game->getId().'/edit');
    }


    /**
     * @Then I should be on the manage page
     */
    public function iShouldBeOnTheManagePage()
    {
        if(!$this->game instanceof Game) {
            $this->createGame();
        }
        $this->visitPath('/staff/game/'.$this->game->getId().'/manage');
    }

    /**
     * @Given I am on the game manage page
     */
    public function iAmOnTheGameManagePage()
    {
        $this->visitPath('/staff/game/'.$this->game->getId().'/manage');
    }

    /**
     * @When I wait for :arg1 seconds
     */
    public function iWaitForSeconds($arg1)
    {
        $this->getSession()->wait($arg1 * 1000);
    }


    /**
     * @Given The game is over
     */
    public function theGameIsOver()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->game->setEndedAt(new DateTime());
        $em->flush();
    }
}
