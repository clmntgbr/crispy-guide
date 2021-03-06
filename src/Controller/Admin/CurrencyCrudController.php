<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class CurrencyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Currency::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('createdAt'))
            ->add(DateTimeFilter::new('updatedAt'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName) {
            return [
                TextField::new('reference'),
                TextField::new('label'),
            ];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [
                IdField::new('id'),
                TextField::new('reference'),
                TextField::new('label'),
                DateTimeField::new('createdAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
                DateTimeField::new('updatedAt')
                    ->setFormat('dd/MM/Y HH:mm:ss')
                    ->renderAsNativeWidget(),
            ];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [
                IdField::new('id')
                    ->setFormTypeOption('disabled','disabled'),
                TextField::new('reference')
                    ->setFormTypeOption('disabled','disabled'),
                TextField::new('label'),
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
                TextField::new('reference'),
                TextField::new('label'),
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
