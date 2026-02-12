<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:promote-user',
    description: 'Ajoute un rôle à un utilisateur (ex: ROLE_ADMIN) via son email',
)]
class PromoteUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l’utilisateur')
            ->addArgument('role', InputArgument::OPTIONAL, 'Rôle à ajouter', 'ROLE_ADMIN');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = (string) $input->getArgument('email');
        $role  = strtoupper((string) $input->getArgument('role'));

        if (!str_starts_with($role, 'ROLE_')) {
            $io->error('Le rôle doit commencer par ROLE_ (ex: ROLE_ADMIN).');
            return Command::INVALID;
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $io->error("Aucun utilisateur trouvé pour l’email : $email");
            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (!in_array($role, $roles, true)) {
            $roles[] = $role;
        }

        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        $user->setRoles(array_values(array_unique($roles)));

        $this->em->flush();

        $io->success(sprintf('✅ %s est maintenant : %s', $email, implode(', ', $user->getRoles())));
        return Command::SUCCESS;
    }
}