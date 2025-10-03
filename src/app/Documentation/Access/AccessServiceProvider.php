<?php

namespace App\Documentation\Access;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Access\Contracts\ContentAccessManagerInterface;
use App\Documentation\Access\Managers\ContentAccessManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class AccessServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ContentAccessManagerInterface::class, ContentAccessManager::class);

        $this->app->extend(ContentAccessManager::class, function (ContentAccessManager $manager) {
            $config = $this->app->make(ConfigDTO::class);

//            $manager->add(new CanSeePage(
//                $config->indexPages
//            ));
//            $manager->add(new CanSeeProduct(
//                $config->R2018b_products
//            ));
            return $manager;
        });
    }
}
