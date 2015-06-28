<?php
namespace Kanahei\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kanahei\GameBundle\Entity\Item;
use DateTime;

class LoadItemData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //アイテム1
        $item1 = new Item();
        $item1->setItemCode(1);
        $item1->setCategory($this->getReference('itemCategory1'));
        $item1->setConsumePoint(1000);
        $item1->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item1->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item1->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item1->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム2
        $item2 = new Item();
        $item2->setItemCode(2);
        $item2->setCategory($this->getReference('itemCategory1'));
        $item2->setConsumePoint(1000);
        $item2->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item2->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item2->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item2->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム3
        $item3 = new Item();
        $item3->setItemCode(3);
        $item3->setCategory($this->getReference('itemCategory1'));
        $item3->setConsumePoint(1000);
        $item3->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item3->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item3->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item3->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム4
        $item4 = new Item();
        $item4->setItemCode(4);
        $item4->setCategory($this->getReference('itemCategory1'));
        $item4->setConsumePoint(1000);
        $item4->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item4->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item4->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item4->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム5
        $item5 = new Item();
        $item5->setItemCode(5);
        $item5->setCategory($this->getReference('itemCategory1'));
        $item5->setConsumePoint(1000);
        $item5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item5->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item5->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム6
        $item6 = new Item();
        $item6->setItemCode(6);
        $item6->setCategory($this->getReference('itemCategory2'));
        $item6->setConsumePoint(1000);
        $item6->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item6->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item6->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item6->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム7
        $item7 = new Item();
        $item7->setItemCode(7);
        $item7->setCategory($this->getReference('itemCategory2'));
        $item7->setConsumePoint(1000);
        $item7->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item7->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item7->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item7->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム8
        $item8 = new Item();
        $item8->setItemCode(8);
        $item8->setCategory($this->getReference('itemCategory2'));
        $item8->setConsumePoint(1000);
        $item8->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item8->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item8->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item8->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム9
        $item9 = new Item();
        $item9->setItemCode(9);
        $item9->setCategory($this->getReference('itemCategory2'));
        $item9->setConsumePoint(1000);
        $item9->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item9->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item9->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item9->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム10
        $item10 = new Item();
        $item10->setItemCode(10);
        $item10->setCategory($this->getReference('itemCategory2'));
        $item10->setConsumePoint(1000);
        $item10->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item10->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item10->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item10->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        //アイテム11
        $item11 = new Item();
        $item11->setItemCode(11);
        $item11->setCategory($this->getReference('itemCategory0'));
        $item11->setConsumePoint(1000);
        $item11->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $item11->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $item11->setDistributedFrom(new DateTime('2013/01/10 00:00:00'));
        $item11->setDistributedTo(new DateTime('2214/01/10 00:00:00'));

        $manager->persist($item1);
        $manager->persist($item2);
        $manager->persist($item3);
        $manager->persist($item4);
        $manager->persist($item5);
        $manager->persist($item6);
        $manager->persist($item7);
        $manager->persist($item8);
        $manager->persist($item9);
        $manager->persist($item10);
        $manager->persist($item11);
        $manager->flush();

        $this->addReference('item1', $item1);
        $this->addReference('item2', $item2);
        $this->addReference('item3', $item3);
        $this->addReference('item4', $item4);
        $this->addReference('item5', $item5);
        $this->addReference('item6', $item6);
        $this->addReference('item7', $item7);
        $this->addReference('item8', $item8);
        $this->addReference('item9', $item9);
        $this->addReference('item10', $item10);
        $this->addReference('item11', $item11);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}

