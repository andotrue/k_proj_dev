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
        //購入履歴1
        $purchaseHistory1 = new PurchaseHistory();
        $purchaseHistory1->setUser($this->getReference('user1'));
        $purchaseHistory1->setItem($this->getReference('item1'));
        $purchaseHistory1->setPointBeforePurchase(30000);
        $purchaseHistory1->setPointAfterPurchase(29700);
        $purchaseHistory1->setConsumePoint(300);
        $purchaseHistory1->setCreatedAt(new DateTime('2014/01/01 00:00:00'));
        $purchaseHistory1->setUpdatedAt(new DateTime('2014/01/01 00:00:30'));

        //購入履歴2
        $purchaseHistory2 = new PurchaseHistory();
        $purchaseHistory2->setUser($this->getReference('user1'));
        $purchaseHistory2->setItem($this->getReference('item2'));
        $purchaseHistory2->setPointBeforePurchase(29700);
        $purchaseHistory2->setPointAfterPurchase(29300);
        $purchaseHistory2->setConsumePoint(400);
        $purchaseHistory2->setCreatedAt(new DateTime('2014/01/01 10:00:00'));
        $purchaseHistory2->setUpdatedAt(new DateTime('2014/01/01 10:00:40'));

        //購入履歴3
        $purchaseHistory3 = new PurchaseHistory();
        $purchaseHistory3->setUser($this->getReference('user1'));
        $purchaseHistory3->setItem($this->getReference('item3'));
        $purchaseHistory3->setPointBeforePurchase(29300);
        $purchaseHistory3->setPointAfterPurchase(28800);
        $purchaseHistory3->setConsumePoint(500);
        $purchaseHistory3->setCreatedAt(new DateTime('2014/01/02 00:00:00'));
        $purchaseHistory3->setUpdatedAt(new DateTime('2014/01/02 00:50:00'));


        //購入履歴4
        $purchaseHistory4 = new PurchaseHistory();
        $purchaseHistory4->setUser($this->getReference('user1'));
        $purchaseHistory4->setItem($this->getReference('item4'));
        $purchaseHistory4->setPointBeforePurchase(28800);
        $purchaseHistory4->setPointAfterPurchase(28500);
        $purchaseHistory4->setConsumePoint(300);
        $purchaseHistory4->setCreatedAt(new DateTime('2014/01/02 09:30:00'));
        $purchaseHistory4->setUpdatedAt(new DateTime('2014/01/02 09:30:05'));

        //購入履歴5
        $purchaseHistory5 = new PurchaseHistory();
        $purchaseHistory5->setUser($this->getReference('user2'));
        $purchaseHistory5->setItem($this->getReference('item1'));
        $purchaseHistory5->setPointBeforePurchase(35000);
        $purchaseHistory5->setPointAfterPurchase(34700);
        $purchaseHistory5->setConsumePoint(300);
        $purchaseHistory5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $purchaseHistory5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        $manager->persist($purchaseHistory1);
        $manager->persist($purchaseHistory2);
        $manager->persist($purchaseHistory3);
        $manager->persist($purchaseHistory4);
        $manager->persist($purchaseHistory5);
        $manager->flush();

        $this->addReference('purchaseHistory1', $purchaseHistory1);
        $this->addReference('purchaseHistory2', $purchaseHistory2);
        $this->addReference('purchaseHistory3', $purchaseHistory3);
        $this->addReference('purchaseHistory4', $purchaseHistory4);
        $this->addReference('purchaseHistory5', $purchaseHistory5);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
}
