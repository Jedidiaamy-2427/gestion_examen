<?php
namespace App\Controller;

use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController extends AbstractController
{
    public function __construct(private TokenService $tokenService) {}

    #[Route('/api/refresh_token', name: 'api_token_refresh', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $refreshToken = $data['refresh_token'] ?? null;

        if (!$refreshToken) {
            return new JsonResponse([
                'error' => 'Refresh token missing',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_OK);
        }

        try {
            $newAccessToken = $this->tokenService->refreshAccessToken($refreshToken);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_OK);  
        }

        return new JsonResponse($newAccessToken);
    }

}
