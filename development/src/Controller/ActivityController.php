<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Activity;
use App\Enum\ActivityType;
use App\Service\ActivityProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Punto de entrada para la gestión de actividades (Fuerza y Cardio).
 */
#[Route('/api/activities/', name: 'api_activity_')]
final class ActivityController extends AbstractController
{
    public function __construct(
        private readonly ActivityProcessor $processor,
        private readonly ActivityRepository $activityRepository 
    ) {}


    /**
     * LISTADO: GET http://localhost:8080/api/activities
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $activities = $this->activityRepository->findAllLatest();
        
        // Symfony serializa automáticamente el array de objetos a JSON
        return $this->json($activities);
    }



    /**
     * Crea una nueva actividad a partir del texto estilo Google Keep.
     */
    #[Route('create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // 1. Recibimos los datos del JSON
        $payload = json_decode($request->getContent(), true);
        
        try {
            $type = ActivityType::from($payload['type'] ?? '');
            $sets = $this->processor->process($type, $payload['content'] ?? '');

            $activity = new Activity();
            $activity->setType($type);
            $activity->setRawContent($payload['content']);
            $activity->setPayloadFromSets($sets);
            $activity->setActive(true);
            $activity->setCreatedAt(new \DateTimeImmutable('now'));

            // Usamos el nuevo método del Repository
            $this->activityRepository->save($activity);

            return $this->json([
                'status' => 'Actividad registrada',
                'id' => $activity->getId()
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}