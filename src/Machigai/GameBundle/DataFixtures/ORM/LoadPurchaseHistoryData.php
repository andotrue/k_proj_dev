<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\PurchaseHistory;
use DateTime;

class LoadPurchaseHistoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //問題１
/*        $manager->persist($purchaseHistory);
        $manager->persist($purchaseHistory2);
        $manager->persist($purchaseHistory3);
        $manager->flush();

        $this->addReference('purchaseHistory', $purchaseHistory);
        $this->addReference('purchaseHistory2', $purchaseHistory2);
        $this->addReference('purchaseHistory3', $purchaseHistory3);
  */  }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}

