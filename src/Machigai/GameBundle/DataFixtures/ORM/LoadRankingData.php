<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\Ranking;
use DateTime;

class LoadRankingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //2013年12月Level Easy 1位
        $ranking201312Easy1 = new Ranking();
        $ranking201312Easy1->setYear(2013);
        $ranking201312Easy1->setMonth(12);
        $ranking201312Easy1->setLevel(1);
        $ranking201312Easy1->setRank(1);
        $ranking201312Easy1->setClearTime(30000);
        $ranking201312Easy1->setBonusPoint(2000);
        $ranking201312Easy1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 2位
        $ranking201312Easy2 = new Ranking();
        $ranking201312Easy2->setYear(2013);
        $ranking201312Easy2->setMonth(12);
        $ranking201312Easy2->setLevel(1);
        $ranking201312Easy2->setRank(2);
        $ranking201312Easy2->setClearTime(40000);
        $ranking201312Easy2->setBonusPoint(2000);
        $ranking201312Easy2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 3位
        $ranking201312Easy3 = new Ranking();
        $ranking201312Easy3->setYear(2013);
        $ranking201312Easy3->setMonth(12);
        $ranking201312Easy3->setLevel(1);
        $ranking201312Easy3->setRank(3);
        $ranking201312Easy3->setClearTime(52000);
        $ranking201312Easy3->setBonusPoint(2000);
        $ranking201312Easy3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 4位
        $ranking201312Easy4 = new Ranking();
        $ranking201312Easy4->setYear(2013);
        $ranking201312Easy4->setMonth(12);
        $ranking201312Easy4->setLevel(1);
        $ranking201312Easy4->setRank(4);
        $ranking201312Easy4->setClearTime(55000);
        $ranking201312Easy4->setBonusPoint(2000);
        $ranking201312Easy4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 5位
        $ranking201312Easy5 = new Ranking();
        $ranking201312Easy5->setYear(2013);
        $ranking201312Easy5->setMonth(12);
        $ranking201312Easy5->setLevel(1);
        $ranking201312Easy5->setRank(5);
        $ranking201312Easy5->setClearTime(60000);
        $ranking201312Easy5->setBonusPoint(2000);
        $ranking201312Easy5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 6位
        $ranking201312Easy6 = new Ranking();
        $ranking201312Easy6->setYear(2013);
        $ranking201312Easy6->setMonth(12);
        $ranking201312Easy6->setLevel(1);
        $ranking201312Easy6->setRank(6);
        $ranking201312Easy6->setClearTime(62000);
        $ranking201312Easy6->setBonusPoint(2000);
        $ranking201312Easy6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 7位
        $ranking201312Easy7 = new Ranking();
        $ranking201312Easy7->setYear(2013);
        $ranking201312Easy7->setMonth(12);
        $ranking201312Easy7->setLevel(1);
        $ranking201312Easy7->setRank(7);
        $ranking201312Easy7->setClearTime(65310);
        $ranking201312Easy7->setBonusPoint(2000);
        $ranking201312Easy7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 8位
        $ranking201312Easy8 = new Ranking();
        $ranking201312Easy8->setYear(2013);
        $ranking201312Easy8->setMonth(12);
        $ranking201312Easy8->setLevel(1);
        $ranking201312Easy8->setRank(8);
        $ranking201312Easy8->setClearTime(66351);
        $ranking201312Easy8->setBonusPoint(2000);
        $ranking201312Easy8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 9位
        $ranking201312Easy9 = new Ranking();
        $ranking201312Easy9->setYear(2013);
        $ranking201312Easy9->setMonth(12);
        $ranking201312Easy9->setLevel(1);
        $ranking201312Easy9->setRank(9);
        $ranking201312Easy9->setClearTime(66851);
        $ranking201312Easy9->setBonusPoint(2000);
        $ranking201312Easy9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 10位
        $ranking201312Easy10 = new Ranking();
        $ranking201312Easy10->setYear(2013);
        $ranking201312Easy10->setMonth(12);
        $ranking201312Easy10->setLevel(1);
        $ranking201312Easy10->setRank(10);
        $ranking201312Easy10->setClearTime(67483);
        $ranking201312Easy10->setBonusPoint(2000);
        $ranking201312Easy10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Easy10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 1位
        $ranking201312Normal1 = new Ranking();
        $ranking201312Normal1->setYear(2013);
        $ranking201312Normal1->setMonth(12);
        $ranking201312Normal1->setLevel(2);
        $ranking201312Normal1->setRank(1);
        $ranking201312Normal1->setClearTime(30000);
        $ranking201312Normal1->setBonusPoint(2000);
        $ranking201312Normal1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 2位
        $ranking201312Normal2 = new Ranking();
        $ranking201312Normal2->setYear(2013);
        $ranking201312Normal2->setMonth(12);
        $ranking201312Normal2->setLevel(2);
        $ranking201312Normal2->setRank(2);
        $ranking201312Normal2->setClearTime(40000);
        $ranking201312Normal2->setBonusPoint(2000);
        $ranking201312Normal2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 3位
        $ranking201312Normal3 = new Ranking();
        $ranking201312Normal3->setYear(2013);
        $ranking201312Normal3->setMonth(12);
        $ranking201312Normal3->setLevel(2);
        $ranking201312Normal3->setRank(3);
        $ranking201312Normal3->setClearTime(52000);
        $ranking201312Normal3->setBonusPoint(2000);
        $ranking201312Normal3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 4位
        $ranking201312Normal4 = new Ranking();
        $ranking201312Normal4->setYear(2013);
        $ranking201312Normal4->setMonth(12);
        $ranking201312Normal4->setLevel(2);
        $ranking201312Normal4->setRank(4);
        $ranking201312Normal4->setClearTime(55000);
        $ranking201312Normal4->setBonusPoint(2000);
        $ranking201312Normal4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 5位
        $ranking201312Normal5 = new Ranking();
        $ranking201312Normal5->setYear(2013);
        $ranking201312Normal5->setMonth(12);
        $ranking201312Normal5->setLevel(2);
        $ranking201312Normal5->setRank(5);
        $ranking201312Normal5->setClearTime(60000);
        $ranking201312Normal5->setBonusPoint(2000);
        $ranking201312Normal5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 6位
        $ranking201312Normal6 = new Ranking();
        $ranking201312Normal6->setYear(2013);
        $ranking201312Normal6->setMonth(12);
        $ranking201312Normal6->setLevel(2);
        $ranking201312Normal6->setRank(6);
        $ranking201312Normal6->setClearTime(62000);
        $ranking201312Normal6->setBonusPoint(2000);
        $ranking201312Normal6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 7位
        $ranking201312Normal7 = new Ranking();
        $ranking201312Normal7->setYear(2013);
        $ranking201312Normal7->setMonth(12);
        $ranking201312Normal7->setLevel(2);
        $ranking201312Normal7->setRank(7);
        $ranking201312Normal7->setClearTime(65310);
        $ranking201312Normal7->setBonusPoint(2000);
        $ranking201312Normal7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 8位
        $ranking201312Normal8 = new Ranking();
        $ranking201312Normal8->setYear(2013);
        $ranking201312Normal8->setMonth(12);
        $ranking201312Normal8->setLevel(2);
        $ranking201312Normal8->setRank(8);
        $ranking201312Normal8->setClearTime(66351);
        $ranking201312Normal8->setBonusPoint(2000);
        $ranking201312Normal8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 9位
        $ranking201312Normal9 = new Ranking();
        $ranking201312Normal9->setYear(2013);
        $ranking201312Normal9->setMonth(12);
        $ranking201312Normal9->setLevel(2);
        $ranking201312Normal9->setRank(9);
        $ranking201312Normal9->setClearTime(66851);
        $ranking201312Normal9->setBonusPoint(2000);
        $ranking201312Normal9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 10位
        $ranking201312Normal10 = new Ranking();
        $ranking201312Normal10->setYear(2013);
        $ranking201312Normal10->setMonth(12);
        $ranking201312Normal10->setLevel(2);
        $ranking201312Normal10->setRank(10);
        $ranking201312Normal10->setClearTime(67483);
        $ranking201312Normal10->setBonusPoint(2000);
        $ranking201312Normal10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Normal10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 1位
        $ranking201312Hard1 = new Ranking();
        $ranking201312Hard1->setYear(2013);
        $ranking201312Hard1->setMonth(12);
        $ranking201312Hard1->setLevel(3);
        $ranking201312Hard1->setRank(1);
        $ranking201312Hard1->setClearTime(30000);
        $ranking201312Hard1->setBonusPoint(2000);
        $ranking201312Hard1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 2位
        $ranking201312Hard2 = new Ranking();
        $ranking201312Hard2->setYear(2013);
        $ranking201312Hard2->setMonth(12);
        $ranking201312Hard2->setLevel(3);
        $ranking201312Hard2->setRank(2);
        $ranking201312Hard2->setClearTime(40000);
        $ranking201312Hard2->setBonusPoint(2000);
        $ranking201312Hard2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 3位
        $ranking201312Hard3 = new Ranking();
        $ranking201312Hard3->setYear(2013);
        $ranking201312Hard3->setMonth(12);
        $ranking201312Hard3->setLevel(3);
        $ranking201312Hard3->setRank(3);
        $ranking201312Hard3->setClearTime(52000);
        $ranking201312Hard3->setBonusPoint(2000);
        $ranking201312Hard3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 4位
        $ranking201312Hard4 = new Ranking();
        $ranking201312Hard4->setYear(2013);
        $ranking201312Hard4->setMonth(12);
        $ranking201312Hard4->setLevel(3);
        $ranking201312Hard4->setRank(4);
        $ranking201312Hard4->setClearTime(55000);
        $ranking201312Hard4->setBonusPoint(2000);
        $ranking201312Hard4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 5位
        $ranking201312Hard5 = new Ranking();
        $ranking201312Hard5->setYear(2013);
        $ranking201312Hard5->setMonth(12);
        $ranking201312Hard5->setLevel(3);
        $ranking201312Hard5->setRank(5);
        $ranking201312Hard5->setClearTime(60000);
        $ranking201312Hard5->setBonusPoint(2000);
        $ranking201312Hard5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 6位
        $ranking201312Hard6 = new Ranking();
        $ranking201312Hard6->setYear(2013);
        $ranking201312Hard6->setMonth(12);
        $ranking201312Hard6->setLevel(3);
        $ranking201312Hard6->setRank(6);
        $ranking201312Hard6->setClearTime(62000);
        $ranking201312Hard6->setBonusPoint(2000);
        $ranking201312Hard6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 7位
        $ranking201312Hard7 = new Ranking();
        $ranking201312Hard7->setYear(2013);
        $ranking201312Hard7->setMonth(12);
        $ranking201312Hard7->setLevel(3);
        $ranking201312Hard7->setRank(7);
        $ranking201312Hard7->setClearTime(65310);
        $ranking201312Hard7->setBonusPoint(2000);
        $ranking201312Hard7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 8位
        $ranking201312Hard8 = new Ranking();
        $ranking201312Hard8->setYear(2013);
        $ranking201312Hard8->setMonth(12);
        $ranking201312Hard8->setLevel(3);
        $ranking201312Hard8->setRank(8);
        $ranking201312Hard8->setClearTime(66351);
        $ranking201312Hard8->setBonusPoint(2000);
        $ranking201312Hard8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 9位
        $ranking201312Hard9 = new Ranking();
        $ranking201312Hard9->setYear(2013);
        $ranking201312Hard9->setMonth(12);
        $ranking201312Hard9->setLevel(3);
        $ranking201312Hard9->setRank(9);
        $ranking201312Hard9->setClearTime(66851);
        $ranking201312Hard9->setBonusPoint(2000);
        $ranking201312Hard9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 10位
        $ranking201312Hard10 = new Ranking();
        $ranking201312Hard10->setYear(2013);
        $ranking201312Hard10->setMonth(12);
        $ranking201312Hard10->setLevel(3);
        $ranking201312Hard10->setRank(10);
        $ranking201312Hard10->setClearTime(67483);
        $ranking201312Hard10->setBonusPoint(2000);
        $ranking201312Hard10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $ranking201312Hard10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));


        $manager->persist($ranking201312Easy1);
        $manager->persist($ranking201312Easy2);
        $manager->persist($ranking201312Easy3);
        $manager->persist($ranking201312Easy4);
        $manager->persist($ranking201312Easy5);
        $manager->persist($ranking201312Easy6);
        $manager->persist($ranking201312Easy7);
        $manager->persist($ranking201312Easy8);
        $manager->persist($ranking201312Easy9);
        $manager->persist($ranking201312Easy10);
        $manager->persist($ranking201312Normal1);
        $manager->persist($ranking201312Normal2);
        $manager->persist($ranking201312Normal3);
        $manager->persist($ranking201312Normal4);
        $manager->persist($ranking201312Normal5);
        $manager->persist($ranking201312Normal6);
        $manager->persist($ranking201312Normal7);
        $manager->persist($ranking201312Normal8);
        $manager->persist($ranking201312Normal9);
        $manager->persist($ranking201312Normal10);
        $manager->persist($ranking201312Hard1);
        $manager->persist($ranking201312Hard2);
        $manager->persist($ranking201312Hard3);
        $manager->persist($ranking201312Hard4);
        $manager->persist($ranking201312Hard5);
        $manager->persist($ranking201312Hard6);
        $manager->persist($ranking201312Hard7);
        $manager->persist($ranking201312Hard8);
        $manager->persist($ranking201312Hard9);
        $manager->persist($ranking201312Hard10);
        $manager->flush();

        $this->addReference('ranking201312Easy1', $ranking201312Easy1);
        $this->addReference('ranking201312Easy2', $ranking201312Easy2);
        $this->addReference('ranking201312Easy3', $ranking201312Easy3);
        $this->addReference('ranking201312Easy4', $ranking201312Easy4);
        $this->addReference('ranking201312Easy5', $ranking201312Easy5);
        $this->addReference('ranking201312Easy6', $ranking201312Easy6);
        $this->addReference('ranking201312Easy7', $ranking201312Easy7);
        $this->addReference('ranking201312Easy8', $ranking201312Easy8);
        $this->addReference('ranking201312Easy9', $ranking201312Easy9);
        $this->addReference('ranking201312Easy10', $ranking201312Easy10);
        $this->addReference('ranking201312Normal1', $ranking201312Normal1);
        $this->addReference('ranking201312Normal2', $ranking201312Normal2);
        $this->addReference('ranking201312Normal3', $ranking201312Normal3);
        $this->addReference('ranking201312Normal4', $ranking201312Normal4);
        $this->addReference('ranking201312Normal5', $ranking201312Normal5);
        $this->addReference('ranking201312Normal6', $ranking201312Normal6);
        $this->addReference('ranking201312Normal7', $ranking201312Normal7);
        $this->addReference('ranking201312Normal8', $ranking201312Normal8);
        $this->addReference('ranking201312Normal9', $ranking201312Normal9);
        $this->addReference('ranking201312Normal10', $ranking201312Normal10);
        $this->addReference('ranking201312Hard1', $ranking201312Hard1);
        $this->addReference('ranking201312Hard2', $ranking201312Hard2);
        $this->addReference('ranking201312Hard3', $ranking201312Hard3);
        $this->addReference('ranking201312Hard4', $ranking201312Hard4);
        $this->addReference('ranking201312Hard5', $ranking201312Hard5);
        $this->addReference('ranking201312Hard6', $ranking201312Hard6);
        $this->addReference('ranking201312Hard7', $ranking201312Hard7);
        $this->addReference('ranking201312Hard8', $ranking201312Hard8);
        $this->addReference('ranking201312Hard9', $ranking201312Hard9);
        $this->addReference('ranking201312Hard10', $ranking201312Hard10);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}

