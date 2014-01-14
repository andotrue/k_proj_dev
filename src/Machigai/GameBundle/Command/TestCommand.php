<?php
namespace Machigai\GameBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Machigai\GameBundle\Entity\ItemRepository;
use Machigai\GameBundle\Entity\Item;


class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('machigai:game:test')
            ->setDescription('Greet someone')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $items = $this->getContainer('doctrine')->get('doctrine')
        ->getRepository('MachigaiGameBundle:Item')
        ->findAll();

        $output->writeln($items[0]->getId());
    }
}