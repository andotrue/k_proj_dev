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
        //ニックネーム付のユーザ
        $user = new User();
        $user->setAuId('auid1');
        $user->setNickname('ユーザ1');
        $user->setCurrentPoint(1000);
        $user->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ニックネームなしのユーザ
        $user2 = new User();
        $user2->setAuId('auid2');
        $user2->setNickname('ユーザ2');
        $user2->setCurrentPoint(1000);
        $user2->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $user2->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();

        $this->addReference('user', $user);
        $this->addReference('user2', $user2);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
