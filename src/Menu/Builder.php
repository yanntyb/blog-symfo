<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class Builder
{

    use ContainerAwareTrait;

    public function mainMenu(RequestStack $requestStack, FactoryInterface $factory, Security $security): ItemInterface
    {

        $user = $security->getUser();

        $menu = $factory->createItem("root");
        $menu->setChildrenAttribute("id", "menu");
        $menu->addChild("Home", ["uri" => "/"]);
        if($user){
            $menu->addChild("Logout", ["uri" => "/logout"]);
            if(in_array("ROLE_ADMINISTRATOR",$user->getRoles())){
                $menu->addChild("Admin panel", ["uri" => "/admin"]);
            }
        }
        else{
            $menu->addChild("Login", ["uri" => "/login"]);
        }
        return $menu;
    }

}
