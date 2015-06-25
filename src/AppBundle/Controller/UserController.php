<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 23/06/15
 * Time: 22:28
 */

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Model\Exception\UserDoesNotExist;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;

class UserController extends FOSRestController
{
    /**
     * @param Request $request
     */
    public function getUserAction(Request $request, $id)
    {
        try {
            return $this->get('app.user.handler')->get((int)$id);
        } catch (UserDoesNotExist $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     *
     * @return FormTypeInterface|View
     */
    public function postUsersAction(Request $request)
    {
        $userHandler = $this->get('app.user.handler');
        try {
            $user = $userHandler->save($request->request->all());

            return $this->routeRedirectView('api_get_user', array('id' => $user->getId()), Codes::HTTP_CREATED);
        } catch (InvalidFormException $ex) {
            return $ex->getForm();
        }
    }

    /**
     * @param Request $request
     */
    public function putUsersAction(Request $request, $id)
    {
        $userHandler = $this->get('app.user.handler');

        try {
            $user = $userHandler->get($id);
            $user = $userHandler->update($user, $request->request->all());
            return $this->routeRedirectView('api_get_user', array('id' => $user->getId()), Codes::HTTP_CREATED);
        } catch (UserDoesNotExist $ex) {
            throw new ResourceNotFoundException($ex->getMessage());
        } catch (InvalidFormException $ex) {
            return $ex->getForm();
        }
    }
}