<?php
declare(strict_types=1);

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\RouterInterface;


#[When(env: 'prod')]
class ExceptionListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        
        $exception = $event->getThrowable();
        
        //*
        if ($exception instanceof HttpExceptionInterface) {
            $response = new RedirectResponse(
                $this->router->generate(
                    'exception.httpException',
                    [
                        "X-Status-Code" => $exception->getStatusCode(), 
                        "X-Code" => $exception->getCode(),
                        "X-Message" => $exception->getMessage()
                    ]
                ),

            );

            $event->setResponse($response);

            return;
        }

        $response = new RedirectResponse(
            $this->router->generate(
                'exception.server-error',
                [
                    "X-Code" => $exception->getCode(),
                    "X-Message" => $exception->getMessage()
                ]
            ),

        );

        $event->setResponse($response);

        //*/
            
        /*
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->headers->add(["yyy"=>"xx"]);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
        //*/

    }
    

}