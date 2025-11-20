<div class="wrap ctab-youtube-slider-admin">
    <h1 class="wp-heading-inline">
        <svg viewBox="0 0 24 24" width="32" height="32" style="vertical-align: middle; margin-right: 10px;">
            <path fill="#FF0000" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
        CTAB YouTube Slider
    </h1>
    
    <div class="ctab-yt-admin-container">
        <div class="ctab-yt-admin-sidebar">
            <!-- Boîte d'information -->
            <div class="ctab-yt-admin-info">
                <h2>
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    Comment utiliser le plugin
                </h2>
                <p>1. Ajoutez vos vidéos YouTube ci-dessous</p>
                <p>2. Utilisez le shortcode pour afficher le slider:</p>
                <div class="ctab-yt-shortcode-box">
                    <code>[ctab_youtube_slider]</code>
                    <button class="ctab-yt-copy-shortcode" data-clipboard-text="[ctab_youtube_slider]">Copier</button>
                </div>
            </div>
            
            <!-- Guide d'utilisation -->
            <div class="ctab-yt-guide">
                <h3>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="#667eea">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    Comment trouver l'ID YouTube
                </h3>
                <ul class="ctab-yt-guide-steps">
                    <li>Ouvrez la vidéo YouTube dans votre navigateur</li>
                    <li>L'ID est dans l'URL: youtube.com/watch?v=<strong>ID_ICI</strong></li>
                    <li>Ou copiez simplement toute l'URL, le plugin extraira l'ID automatiquement</li>
                </ul>
            </div>
            
            <!-- Formulaire d'ajout -->
            <div class="ctab-yt-admin-form">
                <h2>Ajouter une nouvelle vidéo</h2>
                <form method="post" action="">
                    <?php wp_nonce_field('ctab_youtube_slider_add_video'); ?>
                    <input type="hidden" name="ctab_youtube_action" value="add">
                    
                    <div class="ctab-yt-form-field">
                        <label for="title">Titre de la vidéo:</label>
                        <input type="text" id="title" name="title" required placeholder="Ex: Guide de l'agriculture biologique">
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="video_id">ID ou URL YouTube:</label>
                        <input type="text" id="video_id" name="video_id" required placeholder="Ex: dQw4w9WgXcQ ou https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                        <div class="ctab-yt-video-id-help">
                            <strong>Astuce:</strong> Vous pouvez coller l'URL complète de la vidéo YouTube, l'ID sera extrait automatiquement. Par exemple: <code>https://www.youtube.com/watch?v=dQw4w9WgXcQ</code>
                        </div>
                    </div>

                    <div class="ctab-yt-form-field">
                        <label for="custom_thumbnail">Image miniature (obligatoire):</label>
                        <div class="ctab-yt-image-upload">
                            <input type="text" id="custom_thumbnail" name="custom_thumbnail" required placeholder="Cliquez sur 'Choisir une image' pour uploader">
                            <button type="button" class="button ctab-yt-upload-thumbnail">Choisir une image</button>
                        </div>
                        <small style="color: #6b7280; display: block; margin-top: 5px;">
                            <strong>Obligatoire:</strong> Vous devez uploader une image miniature. Format conseillé: 1280x720px (16:9)
                        </small>
                        <div class="ctab-yt-custom-thumbnail-preview"></div>
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="3" required placeholder="Brève description de la vidéo..."></textarea>
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="display_order">Ordre d'affichage:</label>
                        <input type="number" id="display_order" name="display_order" value="0" min="0" placeholder="0">
                        <small style="color: #6b7280;">Plus le numéro est petit, plus la vidéo apparaîtra en premier</small>
                    </div>
                    
                    <div class="ctab-yt-form-submit">
                        <button type="submit" class="ctab-yt-btn ctab-yt-btn-primary">Ajouter la vidéo</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="ctab-yt-admin-content">
            <div class="ctab-yt-videos-list">
                <h2>Liste des vidéos (<?php echo count($videos); ?>)</h2>
                
                <?php if (empty($videos)): ?>
                    <div class="ctab-yt-empty-state">
                        <svg viewBox="0 0 24 24" fill="#9ca3af">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                        <h3>Aucune vidéo pour le moment</h3>
                        <p>Ajoutez votre première vidéo YouTube en utilisant le formulaire ci-contre.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="ctab-yt-video-item">
                            <div class="ctab-yt-video-thumb">
                                <img src="<?php echo esc_url($video->thumbnail_url); ?>" alt="<?php echo esc_attr($video->title); ?>">
                            </div>
                            <div class="ctab-yt-video-details">
                                <h3><?php echo esc_html($video->title); ?></h3>
                                <div class="ctab-yt-video-meta">
                                    <span>
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                        </svg>
                                        ID: <?php echo esc_html($video->video_id); ?>
                                    </span>
                                    <span>
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                        Ordre: <?php echo esc_html($video->display_order); ?>
                                    </span>
                                </div>
                                <p class="ctab-yt-video-description"><?php echo esc_html($video->description); ?></p>
                                <div class="ctab-yt-video-actions">
                                    <button type="button" class="ctab-yt-btn ctab-yt-btn-edit ctab-yt-edit-video" 
                                        data-id="<?php echo $video->id; ?>"
                                        data-title="<?php echo esc_attr($video->title); ?>"
                                        data-video-id="<?php echo esc_attr($video->video_id); ?>"
                                        data-description="<?php echo esc_attr($video->description); ?>"
                                        data-order="<?php echo esc_attr($video->display_order); ?>">
                                        Modifier
                                    </button>
                                    <a href="<?php echo wp_nonce_url(add_query_arg(array('action' => 'delete', 'video_id' => $video->id)), 'delete_video_' . $video->id); ?>" 
                                       class="ctab-yt-btn ctab-yt-btn-delete ctab-yt-delete-video">
                                        Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal pour modifier -->
    <div id="ctab-yt-edit-modal" class="ctab-yt-modal">
        <div class="ctab-yt-modal-content">
            <div class="ctab-yt-modal-header">
                <h3>Modifier la vidéo</h3>
                <button class="ctab-yt-modal-close">&times;</button>
            </div>
            <div class="ctab-yt-modal-body">
                <form method="post" action="">
                    <?php wp_nonce_field('ctab_youtube_slider_edit_video'); ?>
                    <input type="hidden" name="ctab_youtube_action" value="edit">
                    <input type="hidden" id="edit_video_id" name="video_id">
                    
                    <div class="ctab-yt-form-field">
                        <label for="edit_title">Titre de la vidéo:</label>
                        <input type="text" id="edit_title" name="title" required>
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="edit_video_id_field">ID ou URL YouTube:</label>
                        <input type="text" id="edit_video_id_field" name="video_id_field" required>
                    </div>

                    <div class="ctab-yt-form-field">
                        <label for="edit_custom_thumbnail">Image miniature (obligatoire):</label>
                        <div class="ctab-yt-image-upload">
                            <input type="text" id="edit_custom_thumbnail" name="custom_thumbnail" required placeholder="Cliquez sur 'Choisir une image' pour uploader">
                            <button type="button" class="button ctab-yt-upload-thumbnail-edit">Choisir une image</button>
                        </div>
                        <small style="color: #6b7280; display: block; margin-top: 5px;">
                            <strong>Obligatoire:</strong> Vous devez uploader une image miniature. Format: 1280x720px (16:9)
                        </small>
                        <div class="ctab-yt-custom-thumbnail-preview"></div>
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="edit_description">Description:</label>
                        <textarea id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    
                    <div class="ctab-yt-form-field">
                        <label for="edit_display_order">Ordre d'affichage:</label>
                        <input type="number" id="edit_display_order" name="display_order" min="0" required>
                    </div>
                    
                    <div class="ctab-yt-form-submit">
                        <button type="submit" class="ctab-yt-btn ctab-yt-btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
