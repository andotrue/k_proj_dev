<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\RankingPoint;
use DateTime;

class LoadRankingPointData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //2013年12月Level Easy 1位
        $rankingPointEasy1 = new RankingPoint();
        $rankingPointEasy1->setLevel(1);
        $rankingPointEasy1->setRank(1);
        $rankingPointEasy1->setBonusPoint(2000);
        $rankingPointEasy1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 2位
        $rankingPointEasy2 = new RankingPoint();
        $rankingPointEasy2->setLevel(1);
        $rankingPointEasy2->setRank(2);
        $rankingPointEasy2->setBonusPoint(2000);
        $rankingPointEasy2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 3位
        $rankingPointEasy3 = new RankingPoint();
        $rankingPointEasy3->setLevel(1);
        $rankingPointEasy3->setRank(3);
        $rankingPointEasy3->setBonusPoint(2000);
        $rankingPointEasy3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 4位
        $rankingPointEasy4 = new RankingPoint();
        $rankingPointEasy4->setLevel(1);
        $rankingPointEasy4->setRank(4);
        $rankingPointEasy4->setBonusPoint(2000);
        $rankingPointEasy4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 5位
        $rankingPointEasy5 = new RankingPoint();
        $rankingPointEasy5->setLevel(1);
        $rankingPointEasy5->setRank(5);
        $rankingPointEasy5->setBonusPoint(2000);
        $rankingPointEasy5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 6位
        $rankingPointEasy6 = new RankingPoint();
        $rankingPointEasy6->setLevel(1);
        $rankingPointEasy6->setRank(6);
        $rankingPointEasy6->setBonusPoint(2000);
        $rankingPointEasy6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 7位
        $rankingPointEasy7 = new RankingPoint();
        $rankingPointEasy7->setLevel(1);
        $rankingPointEasy7->setRank(7);
        $rankingPointEasy7->setBonusPoint(2000);
        $rankingPointEasy7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 8位
        $rankingPointEasy8 = new RankingPoint();
        $rankingPointEasy8->setLevel(1);
        $rankingPointEasy8->setRank(8);
        $rankingPointEasy8->setBonusPoint(2000);
        $rankingPointEasy8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 9位
        $rankingPointEasy9 = new RankingPoint();
        $rankingPointEasy9->setLevel(1);
        $rankingPointEasy9->setRank(9);
        $rankingPointEasy9->setBonusPoint(2000);
        $rankingPointEasy9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Easy 10位
        $rankingPointEasy10 = new RankingPoint();
        $rankingPointEasy10->setLevel(1);
        $rankingPointEasy10->setRank(10);
        $rankingPointEasy10->setBonusPoint(2000);
        $rankingPointEasy10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointEasy10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 1位
        $rankingPointNormal1 = new RankingPoint();
        $rankingPointNormal1->setLevel(2);
        $rankingPointNormal1->setRank(1);
        $rankingPointNormal1->setBonusPoint(2000);
        $rankingPointNormal1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 2位
        $rankingPointNormal2 = new RankingPoint();
        $rankingPointNormal2->setLevel(2);
        $rankingPointNormal2->setRank(2);
        $rankingPointNormal2->setBonusPoint(2000);
        $rankingPointNormal2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 3位
        $rankingPointNormal3 = new RankingPoint();
        $rankingPointNormal3->setLevel(2);
        $rankingPointNormal3->setRank(3);
        $rankingPointNormal3->setBonusPoint(2000);
        $rankingPointNormal3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 4位
        $rankingPointNormal4 = new RankingPoint();
        $rankingPointNormal4->setLevel(2);
        $rankingPointNormal4->setRank(4);
        $rankingPointNormal4->setBonusPoint(2000);
        $rankingPointNormal4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 5位
        $rankingPointNormal5 = new RankingPoint();
        $rankingPointNormal5->setLevel(2);
        $rankingPointNormal5->setRank(5);
        $rankingPointNormal5->setBonusPoint(2000);
        $rankingPointNormal5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 6位
        $rankingPointNormal6 = new RankingPoint();
        $rankingPointNormal6->setLevel(2);
        $rankingPointNormal6->setRank(6);
        $rankingPointNormal6->setBonusPoint(2000);
        $rankingPointNormal6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 7位
        $rankingPointNormal7 = new RankingPoint();
        $rankingPointNormal7->setLevel(2);
        $rankingPointNormal7->setRank(7);
        $rankingPointNormal7->setBonusPoint(2000);
        $rankingPointNormal7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 8位
        $rankingPointNormal8 = new RankingPoint();
        $rankingPointNormal8->setLevel(2);
        $rankingPointNormal8->setRank(8);
        $rankingPointNormal8->setBonusPoint(2000);
        $rankingPointNormal8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 9位
        $rankingPointNormal9 = new RankingPoint();
        $rankingPointNormal9->setLevel(2);
        $rankingPointNormal9->setRank(9);
        $rankingPointNormal9->setBonusPoint(2000);
        $rankingPointNormal9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Normal 10位
        $rankingPointNormal10 = new RankingPoint();
        $rankingPointNormal10->setLevel(2);
        $rankingPointNormal10->setRank(10);
        $rankingPointNormal10->setBonusPoint(2000);
        $rankingPointNormal10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointNormal10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 1位
        $rankingPointHard1 = new RankingPoint();
        $rankingPointHard1->setLevel(3);
        $rankingPointHard1->setRank(1);
        $rankingPointHard1->setBonusPoint(2000);
        $rankingPointHard1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard1->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 2位
        $rankingPointHard2 = new RankingPoint();
        $rankingPointHard2->setLevel(3);
        $rankingPointHard2->setRank(2);
        $rankingPointHard2->setBonusPoint(2000);
        $rankingPointHard2->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard2->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 3位
        $rankingPointHard3 = new RankingPoint();
        $rankingPointHard3->setLevel(3);
        $rankingPointHard3->setRank(3);
        $rankingPointHard3->setBonusPoint(2000);
        $rankingPointHard3->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard3->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 4位
        $rankingPointHard4 = new RankingPoint();
        $rankingPointHard4->setLevel(3);
        $rankingPointHard4->setRank(4);
        $rankingPointHard4->setBonusPoint(2000);
        $rankingPointHard4->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard4->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 5位
        $rankingPointHard5 = new RankingPoint();
        $rankingPointHard5->setLevel(3);
        $rankingPointHard5->setRank(5);
        $rankingPointHard5->setBonusPoint(2000);
        $rankingPointHard5->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard5->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 6位
        $rankingPointHard6 = new RankingPoint();
        $rankingPointHard6->setLevel(3);
        $rankingPointHard6->setRank(6);
        $rankingPointHard6->setBonusPoint(2000);
        $rankingPointHard6->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard6->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 7位
        $rankingPointHard7 = new RankingPoint();
        $rankingPointHard7->setLevel(3);
        $rankingPointHard7->setRank(7);
        $rankingPointHard7->setBonusPoint(2000);
        $rankingPointHard7->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard7->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 8位
        $rankingPointHard8 = new RankingPoint();
        $rankingPointHard8->setLevel(3);
        $rankingPointHard8->setRank(8);
        $rankingPointHard8->setBonusPoint(2000);
        $rankingPointHard8->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard8->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 9位
        $rankingPointHard9 = new RankingPoint();
        $rankingPointHard9->setLevel(3);
        $rankingPointHard9->setRank(9);
        $rankingPointHard9->setBonusPoint(2000);
        $rankingPointHard9->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard9->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));

        //2013年12月Level Hard 10位
        $rankingPointHard10 = new RankingPoint();
        $rankingPointHard10->setLevel(3);
        $rankingPointHard10->setRank(10);
        $rankingPointHard10->setBonusPoint(2000);
        $rankingPointHard10->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $rankingPointHard10->setUpdatedAt(new DateTime('2014/01/01 00:00:00'));


        $manager->persist($rankingPointEasy1);
        $manager->persist($rankingPointEasy2);
        $manager->persist($rankingPointEasy3);
        $manager->persist($rankingPointEasy4);
        $manager->persist($rankingPointEasy5);
        $manager->persist($rankingPointEasy6);
        $manager->persist($rankingPointEasy7);
        $manager->persist($rankingPointEasy8);
        $manager->persist($rankingPointEasy9);
        $manager->persist($rankingPointEasy10);
        $manager->persist($rankingPointNormal1);
        $manager->persist($rankingPointNormal2);
        $manager->persist($rankingPointNormal3);
        $manager->persist($rankingPointNormal4);
        $manager->persist($rankingPointNormal5);
        $manager->persist($rankingPointNormal6);
        $manager->persist($rankingPointNormal7);
        $manager->persist($rankingPointNormal8);
        $manager->persist($rankingPointNormal9);
        $manager->persist($rankingPointNormal10);
        $manager->persist($rankingPointHard1);
        $manager->persist($rankingPointHard2);
        $manager->persist($rankingPointHard3);
        $manager->persist($rankingPointHard4);
        $manager->persist($rankingPointHard5);
        $manager->persist($rankingPointHard6);
        $manager->persist($rankingPointHard7);
        $manager->persist($rankingPointHard8);
        $manager->persist($rankingPointHard9);
        $manager->persist($rankingPointHard10);
        $manager->flush();

        $this->addReference('rankingPointEasy1', $rankingPointEasy1);
        $this->addReference('rankingPointEasy2', $rankingPointEasy2);
        $this->addReference('rankingPointEasy3', $rankingPointEasy3);
        $this->addReference('rankingPointEasy4', $rankingPointEasy4);
        $this->addReference('rankingPointEasy5', $rankingPointEasy5);
        $this->addReference('rankingPointEasy6', $rankingPointEasy6);
        $this->addReference('rankingPointEasy7', $rankingPointEasy7);
        $this->addReference('rankingPointEasy8', $rankingPointEasy8);
        $this->addReference('rankingPointEasy9', $rankingPointEasy9);
        $this->addReference('rankingPointEasy10', $rankingPointEasy10);
        $this->addReference('rankingPointNormal1', $rankingPointNormal1);
        $this->addReference('rankingPointNormal2', $rankingPointNormal2);
        $this->addReference('rankingPointNormal3', $rankingPointNormal3);
        $this->addReference('rankingPointNormal4', $rankingPointNormal4);
        $this->addReference('rankingPointNormal5', $rankingPointNormal5);
        $this->addReference('rankingPointNormal6', $rankingPointNormal6);
        $this->addReference('rankingPointNormal7', $rankingPointNormal7);
        $this->addReference('rankingPointNormal8', $rankingPointNormal8);
        $this->addReference('rankingPointNormal9', $rankingPointNormal9);
        $this->addReference('rankingPointNormal10', $rankingPointNormal10);
        $this->addReference('rankingPointHard1', $rankingPointHard1);
        $this->addReference('rankingPointHard2', $rankingPointHard2);
        $this->addReference('rankingPointHard3', $rankingPointHard3);
        $this->addReference('rankingPointHard4', $rankingPointHard4);
        $this->addReference('rankingPointHard5', $rankingPointHard5);
        $this->addReference('rankingPointHard6', $rankingPointHard6);
        $this->addReference('rankingPointHard7', $rankingPointHard7);
        $this->addReference('rankingPointHard8', $rankingPointHard8);
        $this->addReference('rankingPointHard9', $rankingPointHard9);
        $this->addReference('rankingPointHard10', $rankingPointHard10);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}


