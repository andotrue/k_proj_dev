<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\User;
use DateTime;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //ユーザ1
        $user1 = new User();
        $user1->setAuId('auid1');
        $user1->setNickname('ユーザ1');
        $user1->setCurrentPoint(1000);
        $user1->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user1->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ2
        $user2 = new User();
        $user2->setAuId('auid2');
        $user2->setNickname('ユーザ2');
        $user2->setCurrentPoint(1000);
        $user2->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user2->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ3
        $user3 = new User();
        $user3->setAuId('auid3');
        $user3->setNickname('ユーザ3');
        $user3->setCurrentPoint(1000);
        $user3->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user3->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ4
        $user4 = new User();
        $user4->setAuId('auid4');
        $user4->setNickname('ユーザ4');
        $user4->setCurrentPoint(1000);
        $user4->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user4->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ5
        $user5 = new User();
        $user5->setAuId('auid5');
        $user5->setNickname('ユーザ5');
        $user5->setCurrentPoint(1000);
        $user5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ6
        $user6 = new User();
        $user6->setAuId('auid6');
        $user6->setNickname('ユーザ6');
        $user6->setCurrentPoint(1000);
        $user6->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user6->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ7
        $user7 = new User();
        $user7->setAuId('auid7');
        $user7->setNickname('ユーザ7');
        $user7->setCurrentPoint(1000);
        $user7->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user7->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ8
        $user8 = new User();
        $user8->setAuId('auid8');
        $user8->setNickname('ユーザ8');
        $user8->setCurrentPoint(1000);
        $user8->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user8->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ9
        $user9 = new User();
        $user9->setAuId('auid9');
        $user9->setNickname('ユーザ9');
        $user9->setCurrentPoint(1000);
        $user9->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user9->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ10
        $user10 = new User();
        $user10->setAuId('auid10');
        $user10->setNickname('ユーザ10');
        $user10->setCurrentPoint(1000);
        $user10->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user10->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ユーザ11
        $user11 = new User();
        $user11->setAuId('auid11');
        $user11->setNickname('ユーザ11');
        $user11->setCurrentPoint(1000);
        $user11->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user11->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->persist($user7);
        $manager->persist($user8);
        $manager->persist($user9);
        $manager->persist($user10);
        $manager->persist($user11);
        $manager->flush();

        $this->addReference('user1', $user1);
        $this->addReference('user2', $user2);
        $this->addReference('user3', $user3);
        $this->addReference('user4', $user4);
        $this->addReference('user5', $user5);
        $this->addReference('user6', $user6);
        $this->addReference('user7', $user7);
        $this->addReference('user8', $user8);
        $this->addReference('user9', $user9);
        $this->addReference('user10', $user10);
        $this->addReference('user11', $user11);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
