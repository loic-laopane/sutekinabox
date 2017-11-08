<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Product controller.
 *
 * @Route("/api/products")
 */
class ProductApiController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("", name="api_products_list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        $data = $serializer->serialize($products, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="api_products_show", requirements={"id"="\d+"})
     * @Method({"GET"})
     */
    public function showAction(Product $product)
    {
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($product, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @Route("/create", name="api_products_create")
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        $data = $request->getContent();
        $serializer = $this->get('serializer');
        $product =$serializer->deserialize($data, Product::class, 'json');
        $validator = $this->get('validator');
        $response = [];
        if($product instanceof Product)
        {
            $errors = $validator->validate($product);
            if(count($errors) == 0)
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                $response['status'] = Response::HTTP_CREATED;
                $response['message'] = 'Produit ajouté';

            }
            else {
                $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                foreach($errors as $err)
                {
                    $response['messages'][] = (string) $err;
                }
            }
        }
        else {
            $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['message'] = 'Erreur d\'ajout';
        }

        //$response = $serializer->serialize($response, 'json');
        return $this->json($response, Response::HTTP_OK);
    }

}
