<?php

namespace Visca\Bundle\CoreBundle\Controller\Api\Abstracts;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ApiController.
 */
abstract class ApiController extends Controller
{
    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function removeNullParameters(array $parameters)
    {
        foreach ($parameters as $k => $v) {
            if ($v === null) {
                unset($parameters[$k]);
            }
        }

        return $parameters;
    }

    /**
     * @param string $entityName Entity name (Ex: ViscaPredictionBundle:Grid)
     * @param int    $id         The entity id
     *
     * @return mixed The entity
     */
    protected function getEntityOrThrowNotFoundException($entityName, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository($entityName)
            ->find($id);

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }
}
