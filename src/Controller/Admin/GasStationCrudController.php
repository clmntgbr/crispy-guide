<?php

namespace App\Controller\Admin;

use App\Entity\GasStation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GasStationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GasStation::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::DELETE)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName) {
            return [];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [
                IdField::new('id'),
                TextField::new('pop'),
                TextField::new('name'),
                TextField::new('company'),
                BooleanField::new('isClosed'),
                BooleanField::new('isFoundOnGouvMap'),
                AssociationField::new('gasStationStatus'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                DateTimeField::new('closedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
            ];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [];
        }

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                IdField::new('id'),
                TextField::new('name'),
                BooleanField::new('isClosed')->setFormTypeOption('disabled','disabled'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                AssociationField::new('gasStationStatus'),
            ];
        }

        return [];
    }
}
