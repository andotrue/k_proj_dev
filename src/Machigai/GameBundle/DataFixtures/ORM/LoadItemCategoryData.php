<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\ItemCategory;
use DateTime;

class LoadItemCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //アイテム1
        $itemCategory1 = new ItemCategory();
        $itemCategory1->setCategoryCode(1);
        $itemCategory1->setName('壁紙');

        //アイテム2
        $itemCategory2 = new ItemCategory();
        $itemCategory2->setCategoryCode(2);
        $itemCategory2->setName('スタンプ');

        //アイテム0
        $itemCategory0 = new ItemCategory();
        $itemCategory0->setCategoryCode(0);
        $itemCategory0->setName('その他');

        $manager->persist($itemCategory1);
        $manager->persist($itemCategory2);
        $manager->persist($itemCategory0);
        $manager->flush();

        $this->addReference('itemCategory1', $itemCategory1);
        $this->addReference('itemCategory2', $itemCategory2);
        $this->addReference('itemCategory0', $itemCategory0);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}


