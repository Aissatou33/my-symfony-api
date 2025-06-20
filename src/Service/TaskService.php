<?php

namespace App\Service;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Service pour gérer les opérations sur les tâches.
 */
class TaskService
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Crée une nouvelle tâche à partir d'un DTO.
     *
     * @param TaskDTO $dto
     * @return Task
     */
    public function createTask(TaskDTO $dto): Task
    {
        $task = new Task();
        $this->mapDtoToTask($dto, $task);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * Récupère toutes les tâches actives.
     *
     * @return Task[]
     */
    public function getActiveTasks(): array
    {
        return $this->taskRepository->findAllActive();
    }

    /**
     * Récupère une tâche par son ID.
     *
     * @param int $id
     * @return Task
     * @throws NotFoundHttpException
     */
    public function getTaskById(int $id): Task
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new NotFoundHttpException('Tâche non trouvée.');
        }
        return $task;
    }

    /**
     * Met à jour une tâche existante.
     *
     * @param int $id
     * @param TaskDTO $dto
     * @return Task
     * @throws NotFoundHttpException
     */
    public function updateTask(int $id, TaskDTO $dto): Task
    {
        $task = $this->getTaskById($id);
        $this->mapDtoToTask($dto, $task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * Archive une tâche.
     *
     * @param int $id
     * @throws NotFoundHttpException
     */
    public function archiveTask(int $id): void
    {
        $task = $this->getTaskById($id);
        $task->setIsArchived(true);
        $this->entityManager->flush();
    }

    /**
     * Récupère toutes les tâches archivées.
     *
     * @return Task[]
     */
    public function getArchivedTasks(): array
    {
        return $this->taskRepository->findAllArchived();
    }

    /**
     * Supprime toutes les tâches archivées.
     */
    public function clearArchive(): void
    {
        $this->taskRepository->deleteAllArchived();
        $this->entityManager->flush();
    }

    /**
     * Mappe les données d'un DTO vers une entité Task.
     *
     * @param TaskDTO $dto
     * @param Task $task
     */
    private function mapDtoToTask(TaskDTO $dto, Task $task): void
    {
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setDueDate($dto->dueDate ? new \DateTime($dto->dueDate->format('Y-m-d H:i:s')) : null);
        $task->setDescription($dto->description);
        $task->setPriority($dto->priority);
        $task->setAuthor($dto->author);
    }
}