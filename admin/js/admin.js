(function($) {
    'use strict';

    $(document).ready(function() {

        // Fonction pour afficher des notifications temporaires
        function showNotification(message, type) {
            // Supprimer les notifications existantes
            $('.ctab-yt-notification').remove();

            // Créer la notification
            const notificationClass = type === 'error' ? 'ctab-yt-notification-error' : 'ctab-yt-notification-success';
            const notification = $('<div class="ctab-yt-notification ' + notificationClass + '">' + message + '</div>');

            // Ajouter au body
            $('body').append(notification);

            // Afficher avec animation
            setTimeout(function() {
                notification.addClass('show');
            }, 100);

            // Masquer après 4 secondes
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 4000);
        }

        // Extraire l'ID YouTube d'une URL
        function extractYoutubeId(url) {
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
                /^([a-zA-Z0-9_-]{11})$/
            ];
            
            for (let pattern of patterns) {
                const match = url.match(pattern);
                if (match) {
                    return match[1];
                }
            }
            
            return null;
        }
        
        // Extraire l'ID YouTube lors de la saisie (sans prévisualisation auto)
        $('#video_id, #edit_video_id_field').on('input', function() {
            const input = $(this);
            const videoIdOrUrl = input.val().trim();
            const videoId = extractYoutubeId(videoIdOrUrl);

            if (videoId) {
                // Mettre à jour l'input avec seulement l'ID
                input.val(videoId);
            }
        });
        
        // Gestionnaire pour le bouton de sélection d'image personnalisée (Ajout)
        $('.ctab-yt-upload-thumbnail').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var imageInput = button.prev('input');
            var imagePreview = button.parent().next().next('.ctab-yt-custom-thumbnail-preview');
            
            // Créer le sélecteur de média
            var mediaUploader = wp.media({
                title: 'Sélectionner une miniature vidéo',
                button: {
                    text: 'Utiliser cette image'
                },
                multiple: false
            });
            
            // Quand une image est sélectionnée
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                imageInput.val(attachment.url);
                
                // Afficher une prévisualisation
                imagePreview.html('<img src="' + attachment.url + '" alt="Miniature personnalisée" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 10px;">');
            });
            
            // Ouvrir le sélecteur de média
            mediaUploader.open();
        });
        
        // Gestionnaire pour le bouton de sélection d'image personnalisée (Édition)
        $('.ctab-yt-upload-thumbnail-edit').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var imageInput = button.prev('input');
            var imagePreview = button.parent().next().next('.ctab-yt-custom-thumbnail-preview');
            
            // Créer le sélecteur de média
            var mediaUploader = wp.media({
                title: 'Sélectionner une miniature vidéo',
                button: {
                    text: 'Utiliser cette image'
                },
                multiple: false
            });
            
            // Quand une image est sélectionnée
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                imageInput.val(attachment.url);
                
                // Afficher une prévisualisation
                imagePreview.html('<img src="' + attachment.url + '" alt="Miniature personnalisée" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 10px;">');
            });
            
            // Ouvrir le sélecteur de média
            mediaUploader.open();
        });
        
        // Copier le shortcode
        $('.ctab-yt-copy-shortcode').on('click', function() {
            const shortcode = $(this).data('clipboard-text');
            
            // Créer un élément temporaire
            const temp = $('<input>');
            $('body').append(temp);
            temp.val(shortcode).select();
            
            // Copier dans le presse-papier
            try {
                document.execCommand('copy');
                temp.remove();
                
                // Retour visuel
                const originalText = $(this).text();
                $(this).text('Copié!');
                
                setTimeout(function() {
                    $('.ctab-yt-copy-shortcode').text(originalText);
                }, 2000);
            } catch (err) {
                console.error('Erreur lors de la copie:', err);
                temp.remove();
            }
        });
        
        // Gestion du modal d'édition
        $('.ctab-yt-edit-video').on('click', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const videoId = $(this).data('video-id');
            const description = $(this).data('description');
            const order = $(this).data('order');
            const thumbnailUrl = $(this).data('thumbnail-url');

            // Remplir le formulaire d'édition
            $('#edit_video_id').val(id);
            $('#edit_title').val(title);
            $('#edit_video_id_field').val(videoId);
            $('#edit_description').val(description);
            $('#edit_display_order').val(order);
            $('#edit_custom_thumbnail').val(thumbnailUrl);

            // Afficher l'image miniature existante
            const imagePreview = $('#edit_custom_thumbnail').parent().next().next('.ctab-yt-custom-thumbnail-preview');
            if (thumbnailUrl) {
                imagePreview.html('<div style="position: relative;"><img src="' + thumbnailUrl + '" alt="Miniature actuelle" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 10px;"><div style="text-align: center; margin-top: 5px; color: #6b7280; font-size: 12px;">Image actuelle (cliquez sur "Choisir une image" pour modifier)</div></div>');
            } else {
                imagePreview.html('');
            }

            // Afficher le modal
            $('#ctab-yt-edit-modal').css('display', 'block');
        });
        
        // Fermer le modal
        $('.ctab-yt-modal-close').on('click', function() {
            $('#ctab-yt-edit-modal').css('display', 'none');
        });
        
        // Fermer le modal en cliquant à l'extérieur
        $(window).on('click', function(e) {
            if ($(e.target).is('.ctab-yt-modal')) {
                $('.ctab-yt-modal').css('display', 'none');
            }
        });
        
        // Confirmation avant suppression
        $('.ctab-yt-delete-video').on('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette vidéo? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
        
        // Validation du formulaire
        $('form').on('submit', function(e) {
            const form = $(this);
            const titleInput = form.find('input[name="title"]');
            const videoIdInput = form.find('input[name="video_id"], input[name="video_id_field"]');
            const thumbnailInput = form.find('input[name="custom_thumbnail"]');
            const descriptionInput = form.find('textarea[name="description"]');

            // Validation du titre
            if (!titleInput.val().trim()) {
                e.preventDefault();
                showNotification('⚠️ Le titre de la vidéo est obligatoire!', 'error');
                titleInput.focus();
                return false;
            }

            // Validation de l'ID YouTube
            const videoIdOrUrl = videoIdInput.val().trim();
            const videoId = extractYoutubeId(videoIdOrUrl);

            if (!videoId) {
                e.preventDefault();
                showNotification('⚠️ Veuillez entrer un ID YouTube valide ou une URL YouTube complète!', 'error');
                videoIdInput.focus();
                return false;
            }

            // Validation de la miniature
            if (!thumbnailInput.val().trim()) {
                e.preventDefault();
                showNotification('⚠️ Vous devez uploader une image miniature!', 'error');
                return false;
            }

            // Validation de la description
            if (!descriptionInput.val().trim()) {
                e.preventDefault();
                showNotification('⚠️ La description de la vidéo est obligatoire!', 'error');
                descriptionInput.focus();
                return false;
            }

            // S'assurer que seul l'ID est envoyé
            videoIdInput.val(videoId);
        });
        
        // Animation au survol des cartes vidéo
        $('.ctab-yt-video-item').on('mouseenter', function() {
            $(this).css('transform', 'translateY(-3px)');
        }).on('mouseleave', function() {
            $(this).css('transform', 'translateY(0)');
        });

        // Empêcher la fermeture du modal lors du clic sur le contenu
        $('.ctab-yt-modal-content').on('click', function(e) {
            e.stopPropagation();
        });

    });
    
})(jQuery);
