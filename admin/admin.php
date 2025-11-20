<?php
/**
 * Fonctions d'administration du plugin CTAB YouTube Slider
 */

// Ajouter le menu d'administration
function ctab_youtube_slider_admin_menu() {
    add_menu_page(
        'CTAB YouTube Slider',
        'YouTube Slider',
        'manage_options',
        'ctab-youtube-slider',
        'ctab_youtube_slider_admin_page',
        'dashicons-video-alt3',
        30
    );
}
add_action('admin_menu', 'ctab_youtube_slider_admin_menu');

// Enregistrer les scripts et styles d'administration
function ctab_youtube_slider_admin_scripts($hook) {
    if ($hook != 'toplevel_page_ctab-youtube-slider') {
        return;
    }
    
    wp_enqueue_style('ctab-youtube-slider-admin', CTAB_YOUTUBE_SLIDER_URL . 'admin/css/admin.css', array(), CTAB_YOUTUBE_SLIDER_VERSION);
    wp_enqueue_script('ctab-youtube-slider-admin', CTAB_YOUTUBE_SLIDER_URL . 'admin/js/admin.js', array('jquery'), CTAB_YOUTUBE_SLIDER_VERSION, true);
    
    // Pour la gestion des médias
    wp_enqueue_media();
    
    // Passer des variables à JavaScript
    wp_localize_script('ctab-youtube-slider-admin', 'ctabYoutubeAdmin', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ctab_youtube_slider_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'ctab_youtube_slider_admin_scripts');

// Page d'administration principale
function ctab_youtube_slider_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctab_youtube_videos';
    
    // Traiter les actions
    if (isset($_POST['ctab_youtube_action']) && $_POST['ctab_youtube_action'] == 'add' && check_admin_referer('ctab_youtube_slider_add_video')) {
        // Ajouter une nouvelle vidéo
        $title = sanitize_text_field($_POST['title']);
        $video_id = sanitize_text_field($_POST['video_id']);
        $description = sanitize_textarea_field($_POST['description']);
        $display_order = intval($_POST['display_order']);
        $thumbnail_url = sanitize_text_field($_POST['custom_thumbnail']);

        // Vérifier que la miniature a bien été uploadée
        if (empty($thumbnail_url)) {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur: Vous devez uploader une image miniature!</p></div>';
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'title' => $title,
                    'video_id' => $video_id,
                    'thumbnail_url' => $thumbnail_url,
                    'description' => $description,
                    'display_order' => $display_order,
                )
            );

            echo '<div class="notice notice-success is-dismissible"><p>Vidéo ajoutée avec succès!</p></div>';
        }
    }
    
    if (isset($_POST['ctab_youtube_action']) && $_POST['ctab_youtube_action'] == 'edit' && check_admin_referer('ctab_youtube_slider_edit_video')) {
        // Modifier une vidéo existante
        $id = intval($_POST['video_id']);
        $title = sanitize_text_field($_POST['title']);
        $video_id = sanitize_text_field($_POST['video_id_field']);
        $description = sanitize_textarea_field($_POST['description']);
        $display_order = intval($_POST['display_order']);
        $thumbnail_url = sanitize_text_field($_POST['custom_thumbnail']);

        // Vérifier que la miniature a bien été uploadée
        if (empty($thumbnail_url)) {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur: Vous devez uploader une image miniature!</p></div>';
        } else {
            $wpdb->update(
                $table_name,
                array(
                    'title' => $title,
                    'video_id' => $video_id,
                    'thumbnail_url' => $thumbnail_url,
                    'description' => $description,
                    'display_order' => $display_order,
                ),
                array('id' => $id)
            );

            echo '<div class="notice notice-success is-dismissible"><p>Vidéo mise à jour avec succès!</p></div>';
        }
    }
    
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['video_id']) && check_admin_referer('delete_video_' . $_GET['video_id'])) {
        // Supprimer une vidéo
        $id = intval($_GET['video_id']);
        
        $wpdb->delete(
            $table_name,
            array('id' => $id)
        );
        
        echo '<div class="notice notice-success is-dismissible"><p>Vidéo supprimée avec succès!</p></div>';
    }
    
    // Récupérer toutes les vidéos
    $videos = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC, id DESC");
    
    // Afficher le formulaire et la liste des vidéos
    require_once CTAB_YOUTUBE_SLIDER_PATH . 'admin/views/admin-page.php';
}

// AJAX: Extraire les informations de la vidéo YouTube
// Fonction désactivée - L'upload manuel de miniature est maintenant obligatoire
/*
add_action('wp_ajax_ctab_get_youtube_info', 'ctab_get_youtube_info');
function ctab_get_youtube_info() {
    check_ajax_referer('ctab_youtube_slider_nonce', 'nonce');

    $video_id = sanitize_text_field($_POST['video_id']);

    // Générer l'URL de la miniature
    $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";

    wp_send_json_success(array(
        'thumbnail_url' => $thumbnail_url,
        'video_id' => $video_id
    ));
}
*/
?>
