<?php
declare(strict_types=1);

namespace App\Controller;


/* This should be reworked in order to not use it */
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends AbstractController
{
    /** 
     * This route is used to add error message for user when a problem is encoutered
     * @param Request $request
     * @return Response
     */
    #[Route('/exception', name: 'exception.httpException', methods: ['GET'])]
    public function index(
        Request $request
    ): Response {

        // Accédez aux en-têtes personnalisés
        $statusCode = $request->query->get('X-Status-Code');
        $code = $request->query->get('X-Code');
        $message = $request->query->get('X-Message');

        //Faille XSS ? Non car message fournit par symfony donc sensé être xss proof ?
        if ($statusCode === "403") {
            $this->addFlash(
                'danger',
                sprintf('<strong>403 :</strong> Il semblerait que la page que vous cherchiez à accéder vous est interdite. Vous avez été redirigé à l\'accueuil.<br>
                <strong>Message d\'erreur:</strong> %s',
                $message)
            );
        }

        if ($statusCode === "404") {
            $this->addFlash(
                'danger',
                sprintf('<strong>404 :</strong> Il semblerait que la page que vous cherchiez à accéder n\'existe pas. Vous avez été redirigé à l\'accueuil.<br>
                <strong>Message d\'erreur:</strong> %s',
                $message)
            );
        }

        return $this->redirectToRoute("home");
    }

    /** 
     * This route is used to add error message for user when a server problem is encoutered
     * @param Request $request
     * @return Response
     */
    #[Route('/exception/500', name: 'exception.server-error', methods: ['GET'])]
    public function error500(
        Request $request
    ): Response {

        // Accédez aux en-têtes personnalisés
        $code = $request->query->get('X-Code');
        $message = $request->query->get('X-Message');

        $this->addFlash(
            'danger',
            sprintf(
                '<strong>500 :</strong> Il semblerait que le serveur ai rencontré un problème. Vous avez été redirigé. <br>
                    <strong>Code d\'erreur :</strong> %s <br>
                    <strong>Message d\'erreur:</strong> %s',
                $code,
                $message
            )

        );

        return $this->redirectToRoute("home");
    }
}