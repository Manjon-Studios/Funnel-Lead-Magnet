<?php

namespace Blocks\FormLeads;

class MyFormLeads
{
    // Slug en kebab-case y minúsculas
    public $slug = 'form-leads';
    public $title = 'Form Leads';
    public $description = 'This is the leads form.';

    public function __construct()
    {
        add_action('acf/init', array($this, 'register_blocks'), 1);
        add_action('acf/init', array($this, 'register_fields'), 2);
    }

    public function register_blocks()
    {
        if ( ! function_exists('acf_register_block_type') ) {
            return;
        }

        acf_register_block_type(array(
            'name'            => $this->slug,                 // 'form-leads'
            'title'           => $this->title,
            'description'     => $this->description,
            'render_callback' => array($this, 'render_block'),
            'category'        => 'widgets',                   // o registra tu propia categoría 'form'
            'icon'            => 'email-alt',                 // dashicon válido
            'keywords'        => array('form', 'leads', 'newsletter'),
            'mode'            => 'edit',
            'supports'        => array('align' => false, 'multiple' => true, 'jsx' => true),
        ));
    }

    public function register_fields()
    {
        if ( ! function_exists('acf_add_local_field_group') ) {
            return;
        }

        acf_add_local_field_group(array(
            'key'    => 'group_block_' . $this->slug,
            'title'  => $this->title,
            'fields' => array(

                array(
                    'key' => 'field_block_' . $this->slug . '_leads_headline',
                    'label' => 'Título principal',
                    'name' => 'leads_headline',
                    'type' => 'text',
                    'instructions' => 'Texto destacado en la parte superior (ej. "Únete a Nuestra Lista").',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_leads_subheadline',
                    'label' => 'Subtítulo',
                    'name' => 'leads_subheadline',
                    'type' => 'text',
                    'instructions' => 'Breve beneficio (ej. "Recibe la guía gratuita…").',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_label_name',
                    'label' => 'Etiqueta campo Nombre',
                    'name' => 'label_name',
                    'type' => 'text',
                    'required' => 1,
                    'default_value' => 'Escribe tu nombre completo',
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_placeholder_name',
                    'label' => 'Placeholder Nombre',
                    'name' => 'placeholder_name',
                    'type' => 'text',
                    'default_value' => 'Juan Pérez',
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_label_email',
                    'label' => 'Etiqueta campo Email',
                    'name' => 'label_email',
                    'type' => 'text',
                    'required' => 1,
                    'default_value' => 'Correo electrónico',
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_placeholder_email',
                    'label' => 'Placeholder Email',
                    'name' => 'placeholder_email',
                    'type' => 'text',
                    'default_value' => 'tucorreo@ejemplo.com',
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_helper_email',
                    'label' => 'Texto de ayuda Email',
                    'name' => 'helper_email',
                    'type' => 'text',
                    'default_value' => 'Te enviaremos el enlace de descarga. Nada de spam.',
                ),
                array(
                    'key' => 'field_block_' . $this->slug . '_cta_text',
                    'label' => 'Texto del botón',
                    'name' => 'cta_text',
                    'type' => 'text',
                    'required' => 1,
                    'default_value' => 'Únete',
                ),
                array(
                    'key'           => 'field_block_' . $this->slug . '_privacy_text',
                    'label'         => 'Texto de consentimiento',
                    'name'          => 'privacy_text',
                    'type'          => 'textarea',
                    'default_value' => 'Al enviar aceptas la Política de Privacidad. Puedes darte de baja cuando quieras.',
                ),
                array(
                    'key'           => 'field_block_' . $this->slug . '_privacy_url',
                    'label'         => 'URL de Política de Privacidad',
                    'name'          => 'privacy_url',
                    'type'          => 'url',
                    'default_value' => '/privacidad',
                ),
            ),

            'location' => array(
                array(
                    array(
                        'param'    => 'block',
                        'operator' => '==',
                        'value'    => 'acf/' . $this->slug, // acf/form-leads
                    ),
                ),
            ),

            'position'      => 'normal',
            'style'         => 'default',
            'active'        => true,
            'show_in_rest'  => 0,

        ));
    }

    public function render_block($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $template_file_path = dirname(__FILE__) . '/layout/layout-front.php';
        if (file_exists($template_file_path)) {
            include $template_file_path;
        }
    }
}
