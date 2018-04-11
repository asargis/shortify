<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RequestContext;

class ShortifyController extends Controller
{
    /**
     * @Route("/{shortUrl}", name="shortify")
     * @param null $shortUrl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index($shortUrl = null)
    {
        $repository = $this->getDoctrine()->getRepository(ShortUrl::class);
        $shortifiedUrls = $repository->findAll();

        if (!empty($shortUrl)) {
            $baseUrl = $this->container->get('router')->getContext()->getHost();

            $redirectedUrl = $repository->findOneBy([
                'shortUrl' => $baseUrl . '/' . $shortUrl
            ]);

            if (!empty($redirectedUrl)) {
                return $this->redirect($redirectedUrl->getUrl(), 308);
            } else {
                $this->addFlash(
                    'notice',
                    "В базе данных нет сылки " . $baseUrl . '/' . $shortUrl
                );
                return $this->redirectToRoute("shortify");
            }
        }

        return $this->render('shortify/index.html.twig', [
            'controller_name' => 'ShortifyController',
            'shortifiedUrls' => $shortifiedUrls
        ]);
    }

    /**
     * @Route("/shortify/encode", name="encode")
     */
    public function encode(Request $request)
    {
        $url = json_decode($request->getContent())->encodingUrl;
        $hash = md5($url);

        $repository = $this->getDoctrine()->getRepository(ShortUrl::class);
        $shortUrl = $repository->findOneBy([
            'hash' => $hash
        ]);

        if (empty($shortUrl)) {
            $entityManager = $this->getDoctrine()->getManager();
            $encodedUrl = new ShortUrl();
            $encodedUrl->setUrl($url);
            $encodedUrl->setHash($hash);

            $link = $this->createShortLink($hash);

            $baseUrl = $this->container->get('router')->getContext()->getHost();
            $encodedUrl->setShortUrl($baseUrl . '/' . $link);
            $date = new \DateTime('now', new \DateTimeZone('UTC'));
            $date->format('\'Y-m-d h:i:s\'');
            $encodedUrl->setCreateDate($date);

            $entityManager->persist($encodedUrl);
            $entityManager->flush();
        } else {
            return new JsonResponse([
                'id' => $shortUrl->getId(),
                'url' => $shortUrl->getUrl(),
                'shortUrl' => $shortUrl->getShortUrl(),
                'exist' => true
            ]);
        }

        return new JsonResponse([
            'id' => $encodedUrl->getId(),
            'url' => $encodedUrl->getUrl(),
            'shortUrl' => $encodedUrl->getShortUrl(),
            'exist' => false
        ]);
    }


    /**
     * @param $hash Хэш исходного URL-адреса
     * @return string Короткая ссылка длиной от 4 до 6 символов
     */
    public function createShortLink($hash)
    {
        $length = rand(4, 6);
        $start_index = rand(1, 32);

        $link = '';
        for ($i = $start_index; $i < $start_index + $length; $i++) {
            $link .= $hash[$i];
        }
        return $link;
    }

}
