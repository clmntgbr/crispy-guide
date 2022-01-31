<?php

namespace App\Controller\Admin;

use App\Entity\GasType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GasTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GasType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('reference'),
            TextField::new('label'),
            DateTimeField::new('createdAt')->setFormat('dd/MM/Y HH:mm:ss')->renderAsNativeWidget(),
            DateTimeField::new('updatedAt')->setFormat('dd/MM/Y HH:mm:ss')->renderAsNativeWidget(),
        ];
    }
}
