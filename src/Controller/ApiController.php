<?php

/**
 * Script for adding license line to PHP files
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Controller;

use App\Form\ShortUrlType;
use App\Service\UrlShortener;
use Shortener\ShortUrl\Form\ShortUrlForm;
use Shortener\ShortUrl\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends AbstractController
{
    private UrlShortener $shortener;

    public function __construct(
        UrlShortener $shortener
    ) {
        $this->shortener = $shortener;
    }

    /**
     * @Route("/generate", name="app_api_generate", host="api.%app.base_host%", env="prod", methods={"POST"})
     * @Route("/generate", name="app_api_generate_dev", env="dev", methods={"POST"})
     * @Route("/generate", name="app_generate", env="test", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function generate(Request $request): JsonResponse
    {
        $content = strval($request->getContent());
        $proto = new ShortUrlForm();

        try {
            $proto->mergeFromJsonString($content);
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'message' => 'Invalid form provided',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $form = $this->createForm(ShortUrlType::class);

        $form->submit((array) json_decode($proto->serializeToJsonString(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $shortUrl = $this->shortener->generateShortUrl($form);

            $proto = new ShortUrl(
                [
                    'success' => true,
                    'shortUrl' => $this->generateUrl(
                        'app_short',
                        [
                            'short' => $shortUrl->getShortUrl(),
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                ]
            );

            return new JsonResponse(
                $proto->serializeToJsonString(),
                JsonResponse::HTTP_OK,
                [],
                true
            );
        }

        return new JsonResponse(
            [
                'message' => 'Invalid form validation',
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/health", name="app_api_health", host="api.%app.base_host%", env="prod", methods={"GET"})
     * @Route("/health", name="app_api_health_dev", env="dev", methods={"GET"})
     * @Route("/health", name="app_health", env="test", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function healthCheck(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
        ], JsonResponse::HTTP_OK);
    }
}
