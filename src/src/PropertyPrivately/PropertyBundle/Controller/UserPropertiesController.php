<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use SeerUK\RestBundle\Controller\RestController;
use PropertyPrivately\PropertyBundle\Exception\Utils\ErrorMessages;
use PropertyPrivately\SecurityBundle\Exception\Utils\ErrorMessages as SecurityErrorMessages;

/**
 * User Properties Controller
 */
class UserPropertiesController extends RestController
{
    public function getAllAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException(SecurityErrorMessages::REQUIRE_AUTHENTICATED_FULLY);
        }

        $user       = $this->get('security.context')->getToken()->getUser();
        $repository = $this->get('pp_property.property_repository');
        $assembler  = $this->get('pp_property.resource_assembler.user_properties.get_all_assembler');
        $assembler->setVariable('properties', $repository->findBy([
            'user' => $user->getId()
        ]));

        return new JsonResponse($assembler->assemble());
    }
}
