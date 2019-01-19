<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserAppsController extends AbstractController
{
    /**
     * @Route("/user-apps", name="user_apps")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }

    /**
     * @Route("/user-apps/get-all")
     * @return object|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAll()
    {
        $user = [
            'id' => 1,
            'firstName' => 'Admin',
            'lastName' => 'Adminov',
            'email' => 'admin@localhost',
            'login' => 'admin',
        ];

        return $this->json([
            [
                'id' => 1,
                'name' => 'Приложение 1',
                'creator' => $user,
                'url' => '/apps/view/1',
                'type' => 1,
                'status' => true,
            ],
            [
                'id' => 1,
                'name' => 'Приложение 2',
                'creator' => $user,
                'url' => '/apps/view/1',
                'type' => 1,
                'status' => true,
            ]
        ]);
    }
}
