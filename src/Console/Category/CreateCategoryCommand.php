<?php

namespace App\Console\Category;

use App\Domain\Project\Entity\CategoryEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:create-category', description: 'Create a new category')]
class CreateCategoryCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Type name of category: ');

        $name = $helper->ask($input, $output, $question);

        if (empty($name)) {
            $output->writeln('<error>Category name cannot be empty</error>');
            return Command::FAILURE;
        }

        if (null !== $this->em->getRepository(CategoryEntity::class)->findOneBy(['name' => $name])) {
            $output->writeln('<error>Category name already exists</error>');
            return Command::FAILURE;
        }

        $category = new CategoryEntity($name);

        $this->em->persist($category);
        $this->em->flush();

        $output->writeln('<info>Category created</info>');

        return Command::SUCCESS;
    }
}