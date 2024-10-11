<?php
namespace App\User\Console;

use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name       : 'app:user:create',
    description: 'Create an user.',
)]
class Generate extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        ?string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new UserEntity('Test user', 'test@app.com', 'secret');

        $this->em->persist($user);

        $this->em->flush();

        $output->write('create a user.');
        return Command::SUCCESS;
    }
}