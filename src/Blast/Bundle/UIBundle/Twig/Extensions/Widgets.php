<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Twig_Environment;
use Symfony\Component\Form\FormView;

class Widgets extends \Twig_Extension
{
    use BlastUIExtensionTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            $this->registerFunction('widget_ajax_card', 'renderAjaxCard', true),
            $this->registerFunction('widget_card', 'renderCard', true),
            $this->registerFunction('widget_field', 'renderField', true),
            $this->registerFunction('widget_field_group', 'renderFieldGroup', true),
            $this->registerFunction('widget_form_group', 'renderFormGroup', true),
            $this->registerFunction('widget_panel', 'renderPanel', true),
            $this->registerFunction('widget_show_group', 'renderShowGroup', true),
            $this->registerFunction('widget_table', 'renderTable', true),
            $this->registerFunction('widget_step_header', 'renderStepHeader', true),
            $this->registerFunction('widget_step_nav', 'renderStepNav', true),
            $this->registerFunction('widget_modal', 'renderModal', true),
        ];
    }

    /**
     * Renders an ajax card.
     *
     * @param Twig_Environment $env
     * @param string           $title
     * @param mixed            $data
     * @param array            $fields
     * @param FormView         $form
     * @param string           $template
     * @param string           $showTemplate
     * @param string           $formTemplate
     *
     * @return string
     */
    public function renderAjaxCard(
        Twig_Environment $env,
        string $title,
        $data,
        array $fields,
        ?FormView $form = null,
        ?string $classes = null,
        ?string $template = null,
        ?string $showTemplate = null,
        ?string $formTemplate = null
    ): string {
        if ($classes !== null && strpos('ajax', $classes) !== -1) {
            $classes .= ' ajax';
        } else {
            $classes = 'ajax';
        }

        return $this->renderCard(
            $env,
            $title,
            $data,
            $fields,
            $form,
            $classes,
            $template,
            $showTemplate,
            $formTemplate
        );
    }

    /**
     * Renders a card.
     *
     * @param Twig_Environment $env
     * @param string           $title
     * @param mixed            $data
     * @param array            $fields
     * @param FormView         $form
     * @param string           $template
     * @param string           $showTemplate
     * @param string           $formTemplate
     *
     * @return string
     */
    public function renderCard(
        Twig_Environment $env,
        string $title,
        $data,
        array $fields,
        ?FormView $form = null,
        ?string $classes = null,
        ?string $template = null,
        ?string $showTemplate = null,
        ?string $formTemplate = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_datacard_card');
        $showTemplate = $showTemplate ?? $this->getTemplate('widget_field_show_group');
        $formTemplate = $formTemplate ?? $this->getTemplate('widget_field_form_group');

        return $env->render($template, [
            'title'        => $title,
            'data'         => $data,
            'fields'       => $fields,
            'form'         => $form,
            'class'        => $classes,
            'showTemplate' => $showTemplate,
            'formTemplate' => $formTemplate,
        ]);
    }

    /**
     * Renders a field group.
     *
     * @param Twig_Environment $env
     * @param mixed            $entity
     * @param array            $fields
     * @param FormView         $form
     * @param string           $showTemplate
     * @param string           $formTemplate
     *
     * @return [type]
     */
    public function renderFieldGroup(
        Twig_Environment $env,
        $entity,
        array $fields,
        ?FormView $form = null,
        ?string $showTemplate = null,
        ?string $formTemplate = null
    ): string {
        $showTemplate = $showTemplate ?? $this->getTemplate('widget_field_show_group');
        $formTemplate = $formTemplate ?? $this->getTemplate('widget_field_form_group');

        $rendered = $this->renderShowGroup($env, $entity, $fields, $showTemplate);

        if ($form !== null) {
            $rendered .= $this->renderFormGroup($env, $form, $formTemplate);
        }

        return $rendered;
    }

    /**
     * Renders a single field.
     *
     * @param Twig_Environment $env
     * @param string           $type
     * @param array            $fieldData
     * @param string           $template
     *
     * @return string
     */
    public function renderField(
        Twig_Environment $env,
        string $type,
        array $fieldData,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('form_type_' . $type);

        return $env->render($template, [
            'field' => $fieldData,
        ]);
    }

    /**
     * Renders a simple panel.
     *
     * @param Twig_Environment $env
     * @param string           $title
     * @param string           $content
     * @param string           $classes
     * @param string           $template
     *
     * @return string
     */
    public function renderPanel(
        Twig_Environment $env,
        ?string $title,
        string $content,
        ?string $classes = '',
        ?string $template = null
    ): string {
        $templateName = ($title !== null ? 'widget_panel' : 'widget_simple_panel');
        $template = $template ?? $this->getTemplate($templateName);

        return $env->render($template, [
            'title'     => $title,
            'content'   => $content,
            'classes'   => $classes,
        ]);
    }

    /**
     * Renders a form group.
     *
     * @param Twig_Environment $env
     * @param FormView         $form
     * @param string           $template
     *
     * @return string
     */
    public function renderFormGroup(
        Twig_Environment $env,
        FormView $form,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_field_form_group');

        return $env->render($template, [
            'form' => $form,
        ]);
    }

    /**
     * Renders a show group.
     *
     * @param Twig_Environment $env
     * @param mixed            $data
     * @param array            $fields
     * @param string           $template
     *
     * @return string
     */
    public function renderShowGroup(
        Twig_Environment $env,
        $data,
        array $fields,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_field_show_group');

        return $env->render($template, [
            'data'   => $data,
            'fields' => $fields,
        ]);
    }

    /**
     * Renders a table.
     *
     * @param Twig_Environment $env
     * @param array            $data
     * @param string           $template
     *
     * @return string
     */
    public function renderTable(
        Twig_Environment $env,
        array $data,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_table');

        $data = array_merge([
            'headers'        => [],
            'data'           => [],
            'actions'        => [],
            'allowSelection' => true,
            'pagination'     => null,
        ], $data);

        return $env->render($template, [
            'table' => $data,
        ]);
    }

    /**
     * Renders a step header item.
     *
     * @param Twig_Environment $env
     * @param string           $name
     * @param string           $title
     * @param string           $description
     * @param string           $icon
     * @param bool             $active
     * @param string           $template
     *
     * @return string
     */
    public function renderStepHeader(
        Twig_Environment $env,
        string $name,
        string $title,
        string $description,
        string $icon,
        ?bool $active = false,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_step_header');

        return $env->render($template, [
            'name'        => $name,
            'title'       => $title,
            'description' => $description,
            'icon'        => $icon,
            'active'      => $active,
        ]);
    }

    /**
     * Renders steps navigation buttons.
     *
     * @param Twig_Environment $env
     * @param bool             $withCancelButton
     * @param string           $template
     *
     * @return string
     */
    public function renderStepNav(
        Twig_Environment $env,
        ?bool $withCancelButton = true,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_step_nav');

        return $env->render($template, [
            'withCancelButton' => $withCancelButton,
        ]);
    }

    /**
     * Renders a rich modal.
     *
     * @param Twig_Environment $env
     * @param string           $identifier
     * @param string           $title
     * @param string           $content
     * @param array            $actions
     * @param string           $classes
     * @param string           $template
     *
     * @return string
     */
    public function renderModal(
        Twig_Environment $env,
        string $identifier,
        string $title,
        string $content,
        ?array $actions = [],
        ?string $classes = null,
        ?string $template = null
    ): string {
        $template = $template ?? $this->getTemplate('widget_modal');

        return $env->render($template, [
            'identifier' => $identifier,
            'title'      => $title,
            'content'    => $content,
            'actions'    => $actions,
            'classes'    => $classes,
        ]);
    }
}
