<?php

namespace App\Controller\Admin;

use App\Entity\GooglePlace;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GooglePlaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GooglePlace::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE, Action::NEW)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName) {
            return [];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [
                IdField::new('id')
                    ->setFormTypeOption('disabled','disabled'),
                TextField::new('googleId'),
                TextField::new('url'),
                TextField::new('website'),
                TextField::new('phoneNumber'),
                TextField::new('placeId'),
                TextField::new('compoundCode'),
                TextField::new('globalCode'),
                TextField::new('googleRating'),
                TextField::new('rating'),
                TextField::new('reference'),
                TextField::new('userRatingsTotal'),
                TextField::new('icon'),
                TextField::new('businessStatus'),
                ArrayField::new('openingHours'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget()
                    ->setFormTypeOption('disabled','disabled'),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget()
                    ->setFormTypeOption('disabled','disabled'),
            ];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [
                IdField::new('id')
                    ->setFormTypeOption('disabled','disabled'),
                TextField::new('googleId'),
                TextField::new('url'),
                TextField::new('website'),
                TextField::new('phoneNumber'),
                TextField::new('placeId'),
                TextField::new('compoundCode'),
                TextField::new('globalCode'),
                TextField::new('googleRating'),
                TextField::new('rating'),
                TextField::new('reference'),
                TextField::new('userRatingsTotal'),
                TextField::new('icon'),
                TextField::new('businessStatus'),
                ArrayField::new('openingHours'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget()
                    ->setFormTypeOption('disabled','disabled'),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget()
                    ->setFormTypeOption('disabled','disabled'),
            ];
        }

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                IdField::new('id'),
                TextField::new('placeId'),
                TextField::new('googleId'),
                TextField::new('businessStatus'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
            ];
        }

        return [];
    }
}
