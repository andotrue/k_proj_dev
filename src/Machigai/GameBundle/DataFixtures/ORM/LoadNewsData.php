<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\News;
use DateTime;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //ニュース1
        $news1 = new News();
        $news1->setTitle('お知らせ1');
        $news1->setDescription('お知らせ1です。<br />このようにHTMLも挿入されます。');
        $news1->setStartedAt(new DateTime('2014/01/05 00:00:00'));
        $news1->setEndedAt(new DateTime('2014/01/06 00:00:00'));
        $news1->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $news1->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ニュース2
        $news2 = new News();
        $news2->setTitle('お知らせ2です。<br />このようにHTMLも挿入されます。');
        $news2->setDescription();
        $news2->setStartedAt(new DateTime('2014/01/05 00:00:00'));
        $news2->setEndedAt(new DateTime('2014/01/08 00:00:00'));
        $news2->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $news2->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ニュース3
        $news3 = new News();
        $news3->setTitle('お知らせ3');
        $news3->setDescription('お知らせ3です。<br />このようにHTMLも挿入されます。');
        $news3->setStartedAt(new DateTime('2014/01/7 00:00:00'));
        $news3->setEndedAt(new DateTime('2014/01/10 00:00:00'));
        $news3->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $news3->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));


        //ニュース4
        $news4 = new News();
        $news4->setTitle('お知らせ4');
        $news4->setDescription('お知らせ4です。<br />このようにHTMLも挿入されます。');
        $news4->setStartedAt(new DateTime('2014/01/7 00:00:00'));
        $news4->setEndedAt(new DateTime('2014/01/30 00:00:00'));
        $news4->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $news4->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        //ニュース5
        $news5 = new News();
        $news5->setTitle('お知らせ5');
        $news5->setDescription('お知らせ5です。<br />このようにHTMLも挿入されます。');
        $news5->setStartedAt(new DateTime('2014/01/8 00:00:00'));
        $news5->setEndedAt(new DateTime('2014/01/30 00:00:00'));
        $news5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $news5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));

        $manager->persist($news1);
        $manager->persist($news2);
        $manager->persist($news3);
        $manager->persist($news4);
        $manager->persist($news5);
        $manager->flush();

        $this->addReference('news1', $news1);
        $this->addReference('news2', $news2);
        $this->addReference('news3', $news3);
        $this->addReference('news4', $news4);
        $this->addReference('news5', $news5);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
}



