<?php
/**
 * Template: Bloque de Leads / Suscripción
 * Depende de los siguientes ACF fields:
 * - leads_headline (text, required)
 * - leads_subheadline (text, required)
 * - label_name (text, required)
 * - placeholder_name (text)
 * - label_email (text, required)
 * - placeholder_email (text)
 * - helper_email (text)
 * - cta_text (text, required)
 * - privacy_text (textarea)
 * - privacy_url (url)
 */

$headline          = get_field('leads_headline') ?: 'Únete a Nuestra Lista';
$subheadline       = get_field('leads_subheadline') ?: 'Recibe la guía gratuita y tácticas listas para aplicar hoy.';
$label_name        = get_field('label_name') ?: 'Escribe tu nombre completo';
$placeholder_name  = get_field('placeholder_name') ?: 'Juan Pérez';
$label_email       = get_field('label_email') ?: 'Correo electrónico';
$placeholder_email = get_field('placeholder_email') ?: 'tucorreo@ejemplo.com';
$helper_email      = get_field('helper_email') ?: 'Te enviaremos el enlace de descarga. Nada de spam.';
$cta_text          = get_field('cta_text') ?: 'Únete';
$privacy_text      = trim((string) get_field('privacy_text'));
$privacy_url       = trim((string) get_field('privacy_url'));

// IDs únicos por instancia del bloque
$uid            = uniqid('leads_');
$form_title_id  = $uid . '_form_title';
$form_desc_id   = $uid . '_form_desc';
$email_hint_id  = $uid . '_email_hint';
$form_status_id = $uid . '_form_status';

// Construcción del párrafo de privacidad (texto + link opcional)
$privacy_paragraph = '';
if ($privacy_text !== '' || $privacy_url !== '') {
    // Si hay URL, añadimos un enlace "Política de Privacidad"
    if ($privacy_url !== '') {
        $link = sprintf(
            '<a href="%s" class="underline text-white" target="_blank" rel="noopener">%s</a>',
            esc_url($privacy_url),
            esc_html__('Política de Privacidad', 'your-textdomain')
        );
        // Si hay texto, lo mostramos y añadimos el enlace al final.
        // Ej.: "Al enviar aceptas la ... Puedes darte de baja cuando quieras. [Política de Privacidad]"
        $privacy_paragraph = trim(esc_html($privacy_text));
        if ($privacy_paragraph !== '') {
            $privacy_paragraph .= ' ' . $link;
        } else {
            // Solo enlace si no hay texto
            $privacy_paragraph = $link;
        }
        // Sanitizamos permitiendo únicamente el <a>
        $privacy_paragraph = wp_kses($privacy_paragraph, array(
            'a' => array(
                'href'   => array(),
                'class'  => array(),
                'target' => array(),
                'rel'    => array(),
            ),
        ));
    } else {
        // Solo texto plano
        $privacy_paragraph = esc_html($privacy_text);
    }
}
?>

<section class="w-full flex flex-col gap-6 my-16 py-[8rem] px-4 bg-blue-600" aria-labelledby="<?php echo esc_attr($form_title_id); ?>">
    <div class="max-w-xl w-full mx-auto text-center">
        <h2 id="<?php echo esc_attr($form_title_id); ?>" class="font-poppins font-bold text-5xl text-white mb-4">
            <?php echo esc_html($headline); ?>
        </h2>
        <p id="<?php echo esc_attr($form_desc_id); ?>" class="font-inter text-white/90 text-base">
            <?php echo esc_html($subheadline); ?>
        </p>
    </div>

    <form class="max-w-[480px] w-full mx-auto flex flex-col gap-4"
          action="<?php echo esc_url('/suscribir'); ?>"
          method="post"
          aria-describedby="<?php echo esc_attr($form_status_id . ' ' . $form_desc_id); ?>"
          novalidate>
        <?php wp_nonce_field('suscribir_form', 'suscribir_nonce'); ?>

        <div class="w-full flex flex-col gap-2">
            <label for="<?php echo esc_attr($uid); ?>_name" class="font-inter text-white font-semibold text-sm">
                <?php echo esc_html($label_name); ?>
            </label>
            <input
                id="<?php echo esc_attr($uid); ?>_name"
                name="name"
                type="text"
                autocomplete="name"
                required
                class="font-inter bg-white/10 text-white placeholder:text-white/70 border-2 border-white/70 rounded py-3 px-4 text-sm outline-none focus:ring-4 focus:ring-white/40 focus:border-white w-full"
                placeholder="<?php echo esc_attr($placeholder_name); ?>" />
        </div>

        <div class="w-full flex flex-col gap-2">
            <label for="<?php echo esc_attr($uid); ?>_email" class="font-inter text-white font-semibold text-sm">
                <?php echo esc_html($label_email); ?>
            </label>
            <input
                id="<?php echo esc_attr($uid); ?>_email"
                name="email"
                type="email"
                inputmode="email"
                autocomplete="email"
                required
                aria-describedby="<?php echo esc_attr($email_hint_id); ?>"
                class="font-inter bg-white/10 text-white placeholder:text-white/70 border-2 border-white/70 rounded py-3 px-4 text-sm outline-none focus:ring-4 focus:ring-white/40 focus:border-white w-full"
                placeholder="<?php echo esc_attr($placeholder_email); ?>" />
            <?php if (!empty($helper_email)) : ?>
                <p id="<?php echo esc_attr($email_hint_id); ?>" class="font-inter text-white/80 text-xs">
                    <?php echo esc_html($helper_email); ?>
                </p>
            <?php endif; ?>
        </div>

        <button type="submit"
                class="w-full md:w-auto border-2 border-white text-white px-8 py-3 font-bold font-poppins text-base cursor-pointer transition-colors hover:bg-white hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-white/40">
            <?php echo esc_html($cta_text); ?>
        </button>

        <?php if ($privacy_paragraph !== '') : ?>
            <p class="font-inter text-white/80 text-xs">
                <?php echo $privacy_paragraph; ?>
            </p>
        <?php endif; ?>

        <!-- Región accesible para mensajes -->
        <div id="<?php echo esc_attr($form_status_id); ?>" class="sr-only" role="status" aria-live="polite"></div>
    </form>
</section>
