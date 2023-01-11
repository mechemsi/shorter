<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Controller;

use App\Service\UrlShortener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private UrlShortener $shortener;

    public function __construct(UrlShortener $shortener)
    {
        $this->shortener = $shortener;
    }

    /**
     * @Route("/", name="app_index", methods={"GET"})
     *
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        $redirect = $this->getParameter('app.redirect');

        return $this->redirect(strval($redirect), RedirectResponse::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/{short}", name="app_short", methods={"GET"})
     *
     * @param string $short
     *
     * @return Response
     */
    public function shortLink(string $short): Response
    {
        $shortUrl = $this->shortener->getShortUrl($short);

        if (null === $shortUrl || null === $shortUrl->getLongUrl()) {
            throw $this->createNotFoundException('The link does not exist');
        }

        return $this->redirect($shortUrl->getLongUrl(), RedirectResponse::HTTP_MOVED_PERMANENTLY);
    }
}
