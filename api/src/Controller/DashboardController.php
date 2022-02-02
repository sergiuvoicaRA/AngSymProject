<?php

namespace App\Controller;

use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/dashboard")
 */
class DashboardController extends AbstractController
{    /**
     * @Route ("/", name="list_partners");
     */
    public function listPartners() {
        $repository = $this->getDoctrine()-getRepository(Partner::class);
    }

    /**
     * @Route("/add", name="add_partner", methods={"POST"})
     */
    public function addPartner(Request $request) {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $partner = $serializer->deserialize($request->getContent(), Partner::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($partner);
        $em->flush();

        return $this->json($partner);

//        return new JsonResponse(array_merge(self::PARTNERS, [
//            'name' => 'Frank',
//            'email' => 'Sinatra',
//            'personal_information' => 'He is a musician',
//        ]));
    }

    /**
     * @Route("/edit/{id}", name="edit_partner", requirements={"id"="\d+"})
     */
    public function editPartner($id) {
        return $this->json(
            self::PARTNERS[array_search($id, array_column(self::PARTNERS, 'id'))]
        );
    }
    /**
     * @Route("/delete/{id}", name="delete_partner", method={"DELETE"})
     */
    public function deletePartner(Partner $partner) {
        $em = $this->getDoctrine()->getManager();
        $em->remove();
    }
}