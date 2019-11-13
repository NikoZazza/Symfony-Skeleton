<?php

namespace App\Controller\Api;

use App\Controller\UtilityController;
use App\Form\ApiUser\ApiUserLoginType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class ApiUserController
 * @package App\Controller\Api
 * @SWG\Tag(name="api_user")
 */
class ApiUserController extends UtilityController {
    /**
     * Api login of user
     * @param Request $request
     *
     * @Route("/api/user/login", name="user_login", methods={"POST"}, options={"expose"=true})
     * @return JsonResponse|RedirectResponse
     *
     * @SWG\Response(response=200, description="OK")
     * @SWG\Parameter(name="form", in="body", @Model(type=ApiUserLoginType::class))
     */
    public function loginAction(Request $request) {
        try {
            $user = $this->getUser();
            if (!empty($user))
                return new JsonResponse(['user' => $user->serializer("all")], Response::HTTP_OK);

            $form = $this->getSubmittedForm(ApiUserLoginType::class, $request);

            if ($form->isValid()) {
                preg_match("/^.{6,}$/", $form->get("password")->getData(), $checkPassword);
                if (empty($checkPassword))
                    return new JsonResponse(['error' => 'password not valid'], Response::HTTP_NOT_ACCEPTABLE);

                if (!filter_var($form->get('email')->getData(), FILTER_VALIDATE_EMAIL))
                    return new JsonResponse(['error' => 'email not valid'], Response::HTTP_NOT_ACCEPTABLE);

                $user = $this->em()->getRepository('App:User')->findOneBy(["email" => $form->get('email')->getData()]);
                if (empty($user))
                    return new JsonResponse(['error' => 'user not found'], Response::HTTP_NOT_ACCEPTABLE);

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);

                if ($encoder->isPasswordValid($user->getPassword(), $form->get('password')->getData(), $user->getSalt())) {
                    if (!$user->isEnabled())
                        return new JsonResponse(['error' => 'user not enabled'], Response::HTTP_NOT_ACCEPTABLE);

                    $session = $request->getSession();
                    $session->set('id', $user->getId());

                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $this->get('security.token_storage')->setToken($token);
                    $this->get('session')->set('_security_main', serialize($token));
                    $user->setLastLogin(new \DateTime());

                    return new JsonResponse(['user' => $user->serializer()], Response::HTTP_OK);
                } else {
                    return new JsonResponse(['error' => 'password not valid'], Response::HTTP_NOT_ACCEPTABLE);
                }
            }
            return new JsonResponse(['error' => 'invalid data'], Response::HTTP_NOT_ACCEPTABLE);
        } catch (\Exception $e) {
            $this->logError($e, 'Api User - Login');
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Api logout of user
     * @param Request $request
     *
     * @Route("/api/user/logout", name="user_logout", methods={"POST"}, options={"expose"=true})
     * @return JsonResponse|RedirectResponse
     *
     * @SWG\Response(response=200, description="OK")
     */
    public function logoutAction(Request $request) {
        try {
            $user = $this->getUser();
            if (!empty($user)) {
                $this->get('security.token_storage')->setToken(null);
                $this->get('session')->set('_security_main', serialize(null));
            }
            return new JsonResponse([], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logError($e, 'Api User - Logout');
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
