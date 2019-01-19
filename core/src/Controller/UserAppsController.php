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
            'path' => 'src/Controller/UserAppsController.php',
        ]);
    }
}
