<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * User form
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('password'),
            ChoiceField::new("roles")->setChoices(
                [
                    "ROLE_ADMINISTRATOR" => "ROLE_ADMINISTRATOR",
                    "ROLE_MODERATOR" => "ROLE_MODERATOR",
                    "ROLE_USER" => "ROLE_USER",
                    "ROLE_AUTHOR" => "ROLE_AUTHOR"
                ]
            )->allowMultipleChoices()
        ];
    }

}
