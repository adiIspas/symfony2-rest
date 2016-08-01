<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/29/2016
 * Time: 3:35 PM
 */

namespace AppBundle\Controller\Api;
use AppBundle\Controller\BaseController;
use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class ProgrammerController extends BaseController
{
    /**
     * @Route("/api/programmers")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $programmer = new Programmer();
        $form = $this->createForm(new ProgrammerType(), $programmer);
        $form->submit($data);
        $programmer->setUser($this->findUserByUsername('weaverryan'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($programmer);
        $em->flush();

        $programmerUrl = $this->generateUrl(
            'api_programmers_show',
            ['nickname' => $programmer->getNickname()]
        );

        $data = $this->serializeProgrammer($programmer);

        $response = new JsonResponse($data,201);

//        $response = new Response(json_encode($data), 201);
//        $response->headers->set('Content-type', 'application/json');

        $response->headers->set('Location', $programmerUrl);
        return $response;
    }
    /**
     * @Route("/api/programmers/{nickname}", name="api_programmers_show")
     */
    public function showAction($nickname)
    {
        $programmer = $this->getDoctrine()
            ->getRepository('AppBundle:Programmer')
            ->findOneByNickname($nickname);
        if (!$programmer) {
            throw $this->createNotFoundException(sprintf(
                'No programmer found with nickname "%s"',
                $nickname
            ));
        }
        $data = $this->serializeProgrammer($programmer);

        $response = new JsonResponse($data);

//        $response = new Response(json_encode($data), 200);
//        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/programmers")
     * @Method("GET")
     */
    public function listAction()
    {
        $programmers = $this->getDoctrine()
            ->getRepository('AppBundle:Programmer')
            ->findAll();

        $data = ['programmers' => []];

        foreach ($programmers as $programmer) {
            $data['programmers'][] = $this->serializeProgrammer($programmer);
        }

        $response = new JsonResponse($data);

//        $response = new Response(json_encode($data), 200);
//        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function serializeProgrammer(Programmer $programmer)
    {
        return array(
            'nickname' => $programmer->getNickname(),
            'avatarNumber' => $programmer->getAvatarNumber(),
            'powerLevel' => $programmer->getPowerLevel(),
            'tagLine' => $programmer->getTagLine(),
        );
    }
}