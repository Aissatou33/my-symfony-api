<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object pour les tâches, utilisé pour les requêtes API.
 */
class TaskDTO
{
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    public ?string $title = null;

    #[Assert\NotBlank(message: 'Le statut est obligatoire.')]
    #[Assert\Choice(choices: ['À faire', 'En cours', 'Terminé'], message: 'Statut invalide.')]
    public ?string $status = null;

    public ?\DateTimeInterface $dueDate = null;

    public ?string $description = null;

    #[Assert\Choice(choices: ['Basse', 'Moyenne', 'Haute'], message: 'Priorité invalide.')]
    public ?string $priority = null;

    public ?string $author = null;
}