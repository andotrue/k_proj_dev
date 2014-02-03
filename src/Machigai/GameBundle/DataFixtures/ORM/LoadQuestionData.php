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
        $failLimit = 5;
        $timeLimit = 1000;
        $clearPoint = 1000;
        $bonusPoint = 500;
        $disFrom(new DateTime('2014/01/01 00:00:00'));
        $disTo(new DateTime('2015/01/10 00:00:00'));
        $creDate(new DateTime('2014/01/01 00:00:00'));
        $upDate(new DateTime('2014/01/10 00:00:00'));
        $copyrightFileName = '';
        $qs = array(
            '1' => array(
                    array (1,1,2,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,2,7,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,3,15,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,4,16,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,5,23,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,6,24,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,7,30,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,8,33,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,9,34,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,10,35,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,11,38,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,12,39,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,13,41,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,14,43,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,15,44,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,16,52,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,17,53,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,18,70,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,19,71,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,20,72,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,21,79,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,22,82,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,23,84,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,24,91,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,25,92,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,26,97,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,27,99,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,28,105,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,29,108,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (1,30,109,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                ),
            '2' => array(
                    array (2,1,3,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,2,5,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,3,6,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,4,8,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,5,10,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,6,11,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,7,12,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,8,13,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,9,14,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,10,17,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,11,18,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,12,19,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,13,20,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,14,21,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,15,26,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,16,28,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,17,32,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,18,36,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,19,47,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,20,48,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,21,49,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,22,51,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,23,55,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,24,56,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,25,58,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,26,60,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,27,61,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,28,62,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,29,63,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,30,64,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,31,65,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,32,66,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,33,67,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,34,68,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,35,69,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,36,73,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,37,75,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,38,76,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,39,78,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,40,81,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,41,83,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,42,85,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,43,86,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,44,87,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,45,88,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,46,93,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,47,94,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,48,95,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,49,96,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,50,100,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,51,103,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,52,104,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,53,106,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,54,107,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,55,110,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,56,111,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,57,113,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,58,114,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,59,115,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,60,116,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,61,119,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,62,120,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,63,121,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,64,127,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,65,130,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,66,131,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,67,132,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,68,134,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,69,137,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,70,138,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,71,139,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,72,146,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,73,147,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,74,149,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,75,151,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,76,155,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,77,156,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,78,157,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,79,158,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,80,159,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,81,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,82,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,83,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,84,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,85,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,86,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,87,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,88,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,89,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,90,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,91,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,92,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,93,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,94,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,95,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,96,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,97,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,98,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,99,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                    array (2,100,161,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                ),
            '3' => array(
                    array (3,1,1,$failLimit,$timePoint,$clearPoint,$bonusPoint, $disFrom, $disTo, $creDate, $upDate, $copyrightFileName);
                )
            );
        //問題1
        $question1 = new Question();
        $question1->setLevel(1);
        $question1->setQcode(1);
        $question1->setQuestionNumber(2);
        $question1->setFailLimit(5);
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

