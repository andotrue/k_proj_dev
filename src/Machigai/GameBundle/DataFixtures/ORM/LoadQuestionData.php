<?php
namespace Machigai\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Machigai\GameBundle\Entity\Question;
use DateTime;

class LoadQuestionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //問題1
        $question1 = new Question();
        $question1->setQuestionNumber('1');
        $question1->setLevel(1);
        $question1->setFailLimit(255);
        $question1->setTimeLimit(1000);
        $question1->setClearPoint(1000);
        $question1->setBonusPoint(500);
        $question1->setDistributedFrom(new DateTime('2014/01/10 00:00:00'));
        $question1->setDistributedTo(new DateTime('2014/01/10 00:00:00'));
        $question1->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $question1->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $question1->setCopyrightFileName(1000);

        //問題2
        $question2 = new Question();
        $question2->setQuestionNumber('2');
        $question2->setLevel(2);
        $question2->setFailLimit(255);
        $question2->setTimeLimit(1000);
        $question2->setClearPoint(1000);
        $question2->setBonusPoint(500);
        $question2->setDistributedFrom(new DateTime('2014/01/10 00:00:00'));
        $question2->setDistributedTo(new DateTime('2014/01/10 00:00:00'));
        $question2->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $question2->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $question2->setCopyrightFileName(1000);

        //問題3
        $question3 = new Question();
        $question3->setQuestionNumber('3');
        $question3->setLevel(3);
        $question3->setFailLimit(255);
        $question3->setTimeLimit(1000);
        $question3->setClearPoint(1000);
        $question3->setBonusPoint(500);
        $question3->setDistributedFrom(new DateTime('2014/01/10 00:00:00'));
        $question3->setDistributedTo(new DateTime('2014/01/10 00:00:00'));
        $question3->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $question3->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $question3->setCopyrightFileName(1000);


        //問題4
        $question4 = new Question();
        $question4->setQuestionNumber('4');
        $question4->setLevel(1);
        $question4->setFailLimit(255);
        $question4->setTimeLimit(1000);
        $question4->setClearPoint(1000);
        $question4->setBonusPoint(500);
        $question4->setDistributedFrom(new DateTime('2014/01/10 00:00:00'));
        $question4->setDistributedTo(new DateTime('2014/01/10 00:00:00'));
        $question4->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $question4->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $question4->setCopyrightFileName(1000);

        //問題5
        $question5 = new Question();
        $question5->setQuestionNumber('5');
        $question5->setLevel(2);
        $question5->setFailLimit(255);
        $question5->setTimeLimit(1000);
        $question5->setClearPoint(1000);
        $question5->setBonusPoint(500);
        $question5->setDistributedFrom(new DateTime('2014/01/10 00:00:00'));
        $question5->setDistributedTo(new DateTime('2014/01/10 00:00:00'));
        $question5->setCreatedAt(new DateTime('2014/01/10 00:00:00'));
        $question5->setUpdatedAt(new DateTime('2014/01/10 00:00:00'));
        $question5->setCopyrightFileName(1000);

        $manager->persist($question1);
        $manager->persist($question2);
        $manager->persist($question3);
        $manager->persist($question4);
        $manager->persist($question5);
        $manager->flush();

        $this->addReference('question1', $question1);
        $this->addReference('question2', $question2);
        $this->addReference('question3', $question3);
        $this->addReference('question4', $question4);
        $this->addReference('question5', $question5);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}

