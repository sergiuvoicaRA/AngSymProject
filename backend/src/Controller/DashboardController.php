<?php

namespace App\Controller;

use App\Email\ConfirmationMail;
use App\Entity\Partners;
use App\Entity\User;
use App\Security\AddPartnerService;
use App\Security\TokenGenerator;
use Doctrine\Persistence\ManagerRegistry;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/dashboard")
 */
class DashboardController extends AbstractController
{
    private $addPartner;

    public function __construct(
        AddPartnerService $addPartner
    )
    {
        $this->addPartner = $addPartner;
    }

    /**
     * @Route ("/", name="list_partners");
    */
    public function listPartners() {
        $repository = $this->getDoctrine()->getRepository(Partners::class);
        $items = $repository->findAll();

        return $this->json($items);
    }

    /**
     * @Route("/add", name="add_partner", methods={"POST"})
     */
    public function addPartner(Request $request) {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $partner = $serializer->deserialize($request->getContent(), Partners::class, 'json');
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($partner);
        $em->flush();

        $this->addPartner->addPartner($user);

        return $this->json($partner);
    }

    /**
     * @Route("/edit/{id}", name="edit_partner", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function editPartner(Request $request, $id) {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $_partner = $serializer->deserialize($request->getContent(), Partners::class, 'json');

        $em = $this->getDoctrine()->getManager();

        $existing_partner = $em->getRepository(Partners::class)->find($id);
        $existing_partner->setName($_partner->getName());
        $existing_partner->setEmail($_partner->getEmail());
        $existing_partner->setPersonalInfo($_partner->getPersonalInfo());
        $existing_partner->setOrganization($_partner->getOrganization());
        $existing_partner->setRole($_partner->getRole());
        $existing_partner->setStatus($_partner->getStatus());

        $em->flush();

        return $this->json($existing_partner);
    }
    /**
     * @Route("/delete/{id}", name="delete_partner")
     */
    public function deletePartner(Partners $partner) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($partner);
        $em->flush();

        return $this->json(NULL, Response::HTTP_NO_CONTENT);
    }
}