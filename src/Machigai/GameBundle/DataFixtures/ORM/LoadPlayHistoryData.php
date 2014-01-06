<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\PlayHistory;
use DateTime;

class LoadPlayHistoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //プレイ履歴1
        $playHistory1 = new PlayHistory();
        $playHistory1->setUser($this->getReference('user1'));
        $playHistory1->setQuestion($this->getReference('question1'));
        $playHistory1->setClearTime(30000);
        $playHistory1->setPlayStartedAt(new DateTime('2014/01/01 09:00:00'));
        $playHistory1->setPlayEndedAt(new DateTime('2014/01/01 09:00:30.000'));
        $playHistory1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $playHistory1->setUpdatedAt(new DateTime('2014/01/01 00:00:30'));

        //プレイ履歴2
        $playHistory2 = new PlayHistory();
        $playHistory2->setUser($this->getReference('user1'));
        $playHistory2->setQuestion($this->getReference('question2'));
        $playHistory2->setClearTime(40000);
        $playHistory2->setPlayStartedAt(new DateTime('2014/01/01 10:00:00'));
        $playHistory2->setPlayEndedAt(new DateTime('2014/01/01 10:00:40'));
        $playHistory2->setCreatedAt(new DateTime('2014/01/01 10:00:00'));
        $playHistory2->setUpdatedAt(new DateTime('2014/01/01 10:00:40'));

        //プレイ履歴3
        $playHistory3 = new PlayHistory();
        $playHistory3->setUser($this->getReference('user1'));
        $playHistory3->setQuestion($this->getReference('question3'));
        $playHistory3->setClearTime(50000);
        $playHistory3->setPlayStartedAt(new DateTime('2014/01/02 00:00:00'));
        $playHistory3->setPlayEndedAt(new DateTime('2014/01/02 00:50:00'));
        $playHistory3->setCreatedAt(new DateTime('2014/01/02 00:00:00'));
        $playHistory3->setUpdatedAt(new DateTime('2014/01/02 00:50:00'));


        //プレイ履歴4
        $playHistory4 = new PlayHistory();
        $playHistory4->setUser($this->getReference('user1'));
        $playHistory4->setQuestion($this->getReference('question4'));
        $playHistory4->setSuspendTime(5000);
        $playHistory4->setPlayStartedAt(new DateTime('2014/01/02 09:30:00'));
        $playHistory4->setCreatedAt(new DateTime('2014/01/02 09:30:00'));
        $playHistory4->setUpdatedAt(new DateTime('2014/01/02 09:30:05'));

        //プレイ履歴5
        $playHistory5 = new PlayHistory();
        $playHistory5->setUser($this->getReference('user2'));
        $playHistory5->setQuestion($this->getReference('question1'));
        $playHistory5->setClearTime(35000);
        $playHistory5->setPlayStartedAt(new DateTime('2014/01/05 00:00:00'));
        $playHistory5->setPlayEndedAt(new DateTime('2014/01/05 00:00:35.000'));
        $playHistory5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $playHistory5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        $manager->persist($playHistory1);
        $manager->persist($playHistory2);
        $manager->persist($playHistory3);
        $manager->persist($playHistory4);
        $manager->persist($playHistory5);
        $manager->flush();

        $this->addReference('playHistory1', $playHistory1);
        $this->addReference('playHistory2', $playHistory2);
        $this->addReference('playHistory3', $playHistory3);
        $this->addReference('playHistory4', $playHistory4);
        $this->addReference('playHistory5', $playHistory5);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
}


