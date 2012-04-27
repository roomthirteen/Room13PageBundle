<?php

namespace Room13\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('aliases','entity',array(
                'class'     =>'Balkanride\\MainBundle\\Entity\\PageAlias',
                'multiple'  =>true,

            ))
            ->add('title',null, array('required' => false))
            ->add('subTitle',null, array('required' => false))
            ->add('breadcrumpTitle',null, array('required' => false))
            ->add('headerAction',null, array('required' => false))
            ->add('subHeaderAction',null, array('required' => false))
            ->add('footerAction',null, array('required' => false))
            ->add('subFooterAction',null, array('required' => false))
            ->add('content', 'textarea', array(
                'attr'  => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ),
                'required' => false,
            ))


            //->add('enabled', null, array('required' => false))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            //->add('path')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('id')
            ->addIdentifier('paths')
        ;
    }
  /*
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('pageName')
            ->assertMaxLength(array('limit' => 255))
            ->end();
    }
  */
}