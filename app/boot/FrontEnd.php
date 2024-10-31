<?php
/**
 * Created by PhpStorm.
 * User: mrrms
 * Date: 10/10/16
 * Time: 5:53 PM
 */

namespace RMS\Boot;

use RMS\Views\ShareWidgetView;

class FrontEnd{

    function __construct()
    {

        add_action('wp_head',array($this,'javascript_variables'));
        add_action('wp_footer',array($this,'rms_share_widget'));
        add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts_hooking'));
    }

    function wp_enqueue_scripts_hooking()
    {

        wp_enqueue_style( 'login-register-style', RMS_URL . '/assets/css/style.css', false );
        wp_enqueue_style( 'fonts-fa', RMS_URL. '/assets/css/fa.css', false );
        wp_enqueue_style( 'loginrm-style', RMS_URL . '/assets/css/stylesheet.css', false );
        wp_enqueue_style( 'animate-style', RMS_URL . '/assets/css/animate.css', false );

        wp_enqueue_script('jquery');
        wp_enqueue_script( 'rms-script', RMS_URL . '/assets/js/rms.js', false );
        wp_enqueue_script( 'rms-share', RMS_URL . '/assets/js/rms-share.js', false );
        wp_enqueue_script( 'rms-order', RMS_URL . '/assets/js/rms-order.js', true );
        wp_enqueue_script( 'rms-tags', RMS_URL . '/assets/js/rms-tags.js', true );
        wp_enqueue_script( 'rms-auth', RMS_URL . '/assets/js/rms-auth.js', true );
        wp_enqueue_script( 'rms-chosen', RMS_URL . '/assets/js/chosen.jquery.min.js', true );

    }

    function javascript_variables(){
        ?>
        <script type="text/javascript">
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }

    function rms_share_widget(){
        ShareWidgetView::content();
    }
}