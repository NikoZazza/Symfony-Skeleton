<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UtilityController
 * @package App\Controller
 */
class UtilityController extends AbstractController {
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $entity
     */
    public function dbSave($entity) {
        $this->em()->persist($entity);
        $this->em()->flush();
    }

    /**
     * @param $entity
     */
    public function dbDelete($entity) {
        $this->em()->remove($entity);
        $this->em()->flush();
    }

    /**
     * @param $verr
     * @return bool
     */
    protected function hasError($verr) {
        if (is_array($verr))
            return array_key_exists('error', $verr);
        return false;
    }

    /**
     * @return \App\Services\HashService|object
     */
    protected function getHashManager() {
        return $this->get('skeleton.hash');
    }

    /**
     * @return \App\Services\MailService|object
     */
    protected function getMailManager() {
        return $this->get('skeleton.mail');
    }

    /**
     * @param $error
     * @param null $method
     */
    protected function logError($error, $method = null) {
        $logger = $this->get('skeleton.logger_service');
        if (!empty($method))
            $logger->logError($method);
        $logger->logError($error);
    }

    /**
     * @param $formType
     * @param Request $request
     * @param null $arrData
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getSubmittedForm($formType, Request $request, $arrData = null) {
        $form = $this->createForm($formType);
        $form->handleRequest($request);

        if (!empty($arrData)) {
            if (is_array($arrData)) {
                foreach ($arrData as $id => $datum) {
                    (!empty($request->request->get($datum))) ? $form->get($datum)->setData($request->request->get($datum)) : null;
                }
            } else {
                (!empty($request->request->get($arrData))) ? $form->get($arrData)->setData($request->request->get($arrData)) : null;
            }
        }
        $form->submit($request->request->all());

        /**
         * @var  $key
         * @var Form $item
         */
        foreach ($form->all() as $key => $item) {
            if ($item->getConfig()->getRequired()) {
                if (empty($request->request->get($key)) || $request->request->get($key) == null) {
                    $form->addError(new FormError($key . " is empty"));
                    //TODO check length
                }
            }
        }
        return $form;
    }

    /**
     * @param
     * @return User|array|mixed
     */
    public function apiPermissionCheck() {
        $user = $this->getUser();
        if (empty($user) || !($user instanceof User) || !$user->isEnabled())
            return ['error' => 'user not authorized'];
        return $user;
    }
}
