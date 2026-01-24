<?php

namespace App\Controller;

use App\Model\Platform;
use App\Service\Router;
use App\Service\Templating;

class PlatformController
{
    public function index(Templating $templating, Router $router): ?string
    {
        $platformResult = $_SESSION['add_platform_result'] ?? null;
        unset($_SESSION['add_platform_result']);

        $platforms = Platform::findAll();
        return $templating->render('admin/platform/index.html.php', [
            'platforms' => $platforms,
            'router' => $router,
            'add_platform_result' => $platformResult,
        ]);
    }

    public function addPlatform(Router $router, string $platformName)
    {
        if (empty($platformName)) {
            $_SESSION['add_platform_result'] = [
                'status' => 'error',
                'message' => 'Platform name cannot be empty'
            ];
            $router->redirect($router->generatePath('platform-index'));
            return;
        }
        $existingPlatform = Platform::findByName($platformName);
        if ($existingPlatform) {
            $_SESSION['add_platform_result'] = [
                'status' => 'error',
                'message' => 'Platform already exists!'
            ];
            $router->redirect($router->generatePath('platform-index'));
            return;
        }

        $newPlatform = new Platform();
        $newPlatform->setName($platformName);
        $newPlatform->save();
        $_SESSION['add_platform_result'] = [
            'status' => 'success',
            'message' => 'Platform added successfully',
        ];
        $router->redirect($router->generatePath('platform-index'));
        return;
    }

    public function deletePlatform(Router $router, ?int $platformId): void
    {
        if (empty($platformId)) {
            //todo message
            $router->redirect($router->generatePath('platform-index'));
            return;
        }
        $platform = Platform::find($platformId);
        $platform->delete();
        $router->redirect($router->generatePath('platform-index'));
        //todo message
        return;
    }

    public function editPlatform(Router $router, ?int $platformId, ?string $platformName): void
    {
        if (empty($platformId) || empty($platformName)) {
            //todo message
            $router->redirect($router->generatePath('platform-index'));
            return;
        }

        $platform = Platform::find($platformId);
        $platform->setName($platformName);
        $platform->save();
        $router->redirect($router->generatePath('platform-index'));
        return;
    }
}