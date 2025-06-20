<?php

namespace App\Controller\Api;

use App\DTO\TaskDTO;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Contrôleur API pour la gestion des tâches.
 *
 * @Route("/tasks")
 */
class TaskController extends AbstractController
{
    private TaskService $taskService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(TaskService $taskService, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->taskService = $taskService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Crée une nouvelle tâche.
     *
     * @Route("", name="api_create_task", methods={"POST"})
     */
    public function createTask(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), TaskDTO::class, 'json');
        $errors = $this->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $task = $this->taskService->createTask($dto);
        return $this->json($task, 201);
    }

    /**
     * Liste toutes les tâches actives.
     *
     * @Route("", name="api_get_tasks", methods={"GET"})
     */
    public function getTasks(): JsonResponse
    {
        $tasks = $this->taskService->getActiveTasks();
        return $this->json($tasks);
    }

    /**
     * Modifie une tâche existante.
     *
    * @Route("/{id<\d+>}", name="api_update_task", methods={"PUT"})
    */
    public function updateTask(int $id, Request $request): JsonResponse

    {
        $dto = $this->serializer->deserialize($request->getContent(), TaskDTO::class, 'json');
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $task = $this->taskService->updateTask($id, $dto);
        return $this->json($task);
    }

    /**
     * Archive une tâche.
     *
     * @Route("/{id<\d+>}", name="api_archive_task", methods={"DELETE"})
    */
    public function archiveTask(int $id): JsonResponse
    {
        $this->taskService->archiveTask($id);
        return $this->json(['message' => 'Tâche archivée avec succès.'], 200);
    }

    /**
     * Liste toutes les tâches archivées.
     *
     * @Route("/archived", name="api_get_archived_tasks", methods={"GET"})
     */
    public function getArchivedTasks(): JsonResponse
    {
        $tasks = $this->taskService->getArchivedTasks();
        return $this->json($tasks);
    }

    /**
     * Supprime toutes les tâches archivées.
     *
     * @Route("/archived", name="api_clear_archive", methods={"DELETE"})
     */
    public function clearArchive(): JsonResponse
    {
        $this->taskService->clearArchive();
        return $this->json(['message' => 'Archive vidée avec succès.']);
    }

    /**
     * Valide un DTO et retourne les erreurs sous forme de tableau.
     *
     * @param object $dto
     * @return array
     */
    private function validate($dto): array
    {
        $errorsList = $this->validator->validate($dto);
        $errorMessages = [];
        foreach ($errorsList as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errorMessages;
    }
}