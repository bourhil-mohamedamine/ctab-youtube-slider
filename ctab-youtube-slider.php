<?php
/**
 * Plugin Name: CTAB YouTube Slider
 * Description: Affiche un slider de vidéos YouTube élégant et personnalisable pour le CTAB.
 * Version: 1.2
 * Author: CTAB
 * Text Domain: ctab-youtube-slider
 * Domain Path: /languages
 */

// Si ce fichier est appelé directement, abandonnez.
if (!defined('WPINC')) {
    die;
}

define('CTAB_YOUTUBE_SLIDER_VERSION', '1.2');
define('CTAB_YOUTUBE_SLIDER_PATH', plugin_dir_path(__FILE__));
define('CTAB_YOUTUBE_SLIDER_URL', plugin_dir_url(__FILE__));

// Activation du plugin
function activate_ctab_youtube_slider() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctab_youtube_videos';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title text NOT NULL,
        video_id varchar(50) NOT NULL,
        thumbnail_url varchar(255) NOT NULL,
        description text NOT NULL,
        display_order int(11) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Insérer des vidéos d'exemple si la table est vide
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    
    if ($count == 0) {
        $default_videos = array(
            array(
                'title' => "Introduction à l'Agriculture Biologique",
                'video_id' => "dQw4w9WgXcQ",
                'thumbnail_url' => "https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg",
                'description' => "Découvrez les principes fondamentaux de l'agriculture biologique et ses avantages pour l'environnement.",
                'display_order' => 1
            ),
            array(
                'title' => "Techniques de Compostage",
                'video_id' => "dQw4w9WgXcQ",
                'thumbnail_url' => "https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg",
                'description' => "Apprenez les meilleures techniques de compostage pour enrichir votre sol naturellement.",
                'display_order' => 2
            ),
            array(
                'title' => "Culture des Plantes Aromatiques",
                'video_id' => "dQw4w9WgXcQ",
                'thumbnail_url' => "https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg",
                'description' => "Guide complet pour cultiver et transformer les plantes aromatiques en mode biologique.",
                'display_order' => 3
            )
        );
        
        foreach ($default_videos as $video) {
            $wpdb->insert($table_name, $video);
        }
    }
}
register_activation_hook(__FILE__, 'activate_ctab_youtube_slider');

// Désactivation du plugin
function deactivate_ctab_youtube_slider() {
    // Code de désactivation du plugin si nécessaire
}
register_deactivation_hook(__FILE__, 'deactivate_ctab_youtube_slider');

// Chargement des fichiers d'administration
require_once CTAB_YOUTUBE_SLIDER_PATH . 'admin/admin.php';

// Enregistrement du shortcode [ctab_youtube_slider]
function ctab_youtube_slider_shortcode($atts) {
    // Charger les scripts et styles nécessaires
    wp_enqueue_style('ctab-youtube-slider', CTAB_YOUTUBE_SLIDER_URL . 'public/css/slider.css', array(), CTAB_YOUTUBE_SLIDER_VERSION);
    wp_enqueue_script('ctab-youtube-slider', CTAB_YOUTUBE_SLIDER_URL . 'public/js/slider.js', array('jquery'), CTAB_YOUTUBE_SLIDER_VERSION, true);
    
    // Récupérer les vidéos depuis la base de données
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctab_youtube_videos';
    $videos = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC, id DESC");
    
    // Démarrer la capture de sortie
    ob_start();
    ?>
    <div class="ctab-youtube-slider">
        <div class="ctab-yt-container">
            <div class="ctab-yt-header">
                <div class="ctab-yt-header-content">
                    <div class="ctab-yt-icon">
                        <svg viewBox="0 0 24 24" width="40" height="40">
                            <path fill="#FF0000" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </div>
                    <div class="ctab-yt-header-text">
                        <h2 class="ctab-yt-title">Médiathèque</h2>
                        <p class="ctab-yt-subtitle">Suivez notre chaîne YouTube du CTAB.</p>
                    </div>
                </div>
            </div>
            
            <div class="ctab-yt-slider-wrapper">
                <button class="ctab-yt-slider-prev" aria-label="Précédent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
                    </svg>
                </button>
                
                <button class="ctab-yt-slider-next" aria-label="Suivant">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                    </svg>
                </button>
                
                <div class="ctab-yt-slider-track">
                    <?php foreach ($videos as $video): ?>
                    <div class="ctab-yt-slide" data-video-id="<?php echo esc_attr($video->video_id); ?>">
                        <div class="ctab-yt-video-card">
                            <div class="ctab-yt-thumbnail-wrapper">
                                <img src="<?php echo esc_url($video->thumbnail_url); ?>" 
                                     alt="<?php echo esc_attr($video->title); ?>"
                                     class="ctab-yt-thumbnail">
                                <div class="ctab-yt-play-overlay">
                                    <div class="ctab-yt-play-button">
                                        <svg viewBox="0 0 24 24" width="60" height="60">
                                            <path fill="#FFFFFF" d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ctab-yt-duration-badge">
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ctab-yt-video-info">
                                <h3 class="ctab-yt-video-title"><?php echo esc_html($video->title); ?></h3>
                                <p class="ctab-yt-video-description"><?php echo esc_html($video->description); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="ctab-yt-dots-container">
                    <div class="ctab-yt-dots"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour la lecture vidéo -->
    <div id="ctab-yt-modal" class="ctab-yt-modal">
        <div class="ctab-yt-modal-content">
            <button class="ctab-yt-modal-close" aria-label="Fermer">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
            <div class="ctab-yt-video-container">
                <div id="ctab-yt-player"></div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ctab_youtube_slider', 'ctab_youtube_slider_shortcode');
?>
