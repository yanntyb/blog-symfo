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

    /**
     * Create Main menu based on user's right
     * @param RequestStack $requestStack
     * @param FactoryInterface $factory
     * @param Security $security
     * @return ItemInterface
     */
    public function mainMenu(RequestStack $requestStack, FactoryInterface $factory, Security $security): ItemInterface
    {

        $user = $security->getUser();

        $menu = $factory->createItem("root");
        $menu->setChildrenAttribute("id", "menu");
        $menu->addChild("Blog", ["uri" => "/"]);
        //If user is connected then show more item
        if($user){
            if(in_array("ROLE_AUTHOR", $user->getRoles())){
                $menu["Blog"]->addChild("Add article", ["uri" =>"/article/add"] );
            }
            $menu->addChild("Logout", ["uri" => "/logout"]);
            //Admin pannel
            if(in_array("ROLE_ADMINISTRATOR",$user->getRoles())){
                $menu->addChild("Admin panel", ["uri" => "/admin"]);
            }
        }
        //If user is not connected show login item
        else{
            $menu->addChild("Login", ["uri" => "/login"]);
            $menu["Login"]->addChild("Register", ["uri" => "/register"]);
        }
        return $menu;
    }

}
