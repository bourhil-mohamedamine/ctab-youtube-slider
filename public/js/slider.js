/**
 * Script du slider de vidéos YouTube CTAB
 */
(function($) {
    'use strict';
    
    // Configuration du slider
    const sliderConfig = {
        autoplay: true,
        autoplaySpeed: 6000,
        transition: 600,
        slidesPerView: 3,
    };
    
    // Variable globale pour le player YouTube
    let youtubePlayer = null;
    let playerReady = false;
    
    // Détecter si la page est en mode RTL
    const isRTL = document.documentElement.dir === 'rtl' || 
                  document.body.dir === 'rtl' || 
                  $('html').attr('dir') === 'rtl';
    
    // Détecter la langue actuelle
    function getCurrentLanguage() {
        const currentUrl = window.location.href;
        if (currentUrl.includes('/ar/')) {
            return 'ar';
        } else if (currentUrl.includes('/en/')) {
            return 'en';
        } else {
            return 'fr';
        }
    }
    
    // Charger l'API YouTube IFrame
    function loadYouTubeAPI() {
        if (window.YT && window.YT.Player) {
            return Promise.resolve();
        }
        
        return new Promise((resolve) => {
            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            
            window.onYouTubeIframeAPIReady = function() {
                resolve();
            };
        });
    }
    
    // Adapter les slides par vue selon la taille d'écran
    function getSlidesPerView() {
        const width = $(window).width();
        if (width < 768) {
            return 1;
        } else if (width < 1200) {
            return 2;
        } else {
            return sliderConfig.slidesPerView;
        }
    }
    
    // Initialisation du slider
    function initSlider() {
        const track = $('.ctab-yt-slider-track');
        const slides = $('.ctab-yt-slide');
        const dotsContainer = $('.ctab-yt-dots');
        const modal = $('#ctab-yt-modal');
        let currentIndex = 0;
        let autoplayInterval;
        
        if (slides.length === 0) {
            return;
        }
        
        // Créer les indicateurs de pagination
        function createPaginationDots() {
            dotsContainer.empty();
            
            const slidesPerView = getSlidesPerView();
            const totalPages = Math.ceil(slides.length / slidesPerView);
            
            for (let i = 0; i < totalPages; i++) {
                const dot = $('<div class="ctab-yt-dot"></div>');
                if (i === 0) dot.addClass('active');
                
                dot.on('click', () => {
                    goToPage(i);
                    resetAutoplay();
                });
                
                dotsContainer.append(dot);
            }
        }
        
        // Aller à une page spécifique
        function goToPage(pageIndex) {
            const slidesPerView = getSlidesPerView();
            const slideIndex = pageIndex * slidesPerView;
            
            if (slideIndex >= 0 && slideIndex < slides.length) {
                currentIndex = slideIndex;
                updateSlider();
            }
        }
        
        // Mettre à jour le slider
        function updateSlider() {
            const slidesPerView = getSlidesPerView();
            const slideWidth = 100 / slidesPerView;
            const percentage = (currentIndex / slides.length) * (slides.length * slideWidth);
            
            // En mode RTL, inverser la direction
            if (isRTL) {
                track.css('transform', `translateX(${percentage}%)`);
            } else {
                track.css('transform', `translateX(-${percentage}%)`);
            }
            
            // Mettre à jour les dots
            const activePage = Math.floor(currentIndex / slidesPerView);
            $('.ctab-yt-dot').removeClass('active');
            $('.ctab-yt-dot').eq(activePage).addClass('active');
        }
        
        // Passer au groupe suivant
        function goToNextGroup() {
            const slidesPerView = getSlidesPerView();
            const nextGroupIndex = currentIndex + slidesPerView;
            
            if (nextGroupIndex >= slides.length) {
                currentIndex = 0;
            } else {
                currentIndex = nextGroupIndex;
            }
            
            updateSlider();
        }
        
        // Passer au groupe précédent
        function goToPrevGroup() {
            const slidesPerView = getSlidesPerView();
            const prevGroupIndex = currentIndex - slidesPerView;
            
            if (prevGroupIndex < 0) {
                const totalGroups = Math.ceil(slides.length / slidesPerView);
                currentIndex = (totalGroups - 1) * slidesPerView;
            } else {
                currentIndex = prevGroupIndex;
            }
            
            updateSlider();
        }
        
        // Initialiser les dots
        createPaginationDots();
        
        // Boutons de navigation avec support RTL
        if (isRTL) {
            $('.ctab-yt-slider-prev').on('click', () => {
                goToNextGroup();
                resetAutoplay();
            });
            
            $('.ctab-yt-slider-next').on('click', () => {
                goToPrevGroup();
                resetAutoplay();
            });
        } else {
            $('.ctab-yt-slider-prev').on('click', () => {
                goToPrevGroup();
                resetAutoplay();
            });
            
            $('.ctab-yt-slider-next').on('click', () => {
                goToNextGroup();
                resetAutoplay();
            });
        }
        
        // Réinitialiser l'autoplay
        function resetAutoplay() {
            if (sliderConfig.autoplay) {
                clearInterval(autoplayInterval);
                startAutoplay();
            }
        }
        
        // Démarrer l'autoplay
        function startAutoplay() {
            if (sliderConfig.autoplay) {
                autoplayInterval = setInterval(() => {
                    if (isRTL) {
                        goToPrevGroup();
                    } else {
                        goToNextGroup();
                    }
                }, sliderConfig.autoplaySpeed);
            }
        }
        
        // Démarrer l'autoplay
        startAutoplay();
        
        // Interaction avec le slider
        const slider = $('.ctab-yt-slider-wrapper');
        
        slider.on('mouseenter', () => {
            clearInterval(autoplayInterval);
        });
        
        slider.on('mouseleave', () => {
            resetAutoplay();
        });
        
        // Support tactile
        let touchStartX = 0;
        let touchEndX = 0;
        
        slider[0].addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        slider[0].addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            resetAutoplay();
        }, { passive: true });
        
        function handleSwipe() {
            const difference = touchStartX - touchEndX;
            
            if (isRTL) {
                if (difference > 50) {
                    goToPrevGroup();
                } else if (difference < -50) {
                    goToNextGroup();
                }
            } else {
                if (difference > 50) {
                    goToNextGroup();
                } else if (difference < -50) {
                    goToPrevGroup();
                }
            }
        }
        
        // Ouvrir la vidéo dans le modal
        $('.ctab-yt-video-card').on('click', function() {
            const videoId = $(this).closest('.ctab-yt-slide').data('video-id');
            openVideoModal(videoId);
            clearInterval(autoplayInterval);
        });
        
        // Fermer le modal
        $('.ctab-yt-modal-close').on('click', closeVideoModal);
        
        modal.on('click', function(e) {
            if ($(e.target).is('.ctab-yt-modal')) {
                closeVideoModal();
            }
        });
        
        // Fermer avec Échap
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.is(':visible')) {
                closeVideoModal();
            }
        });
        
        // Redimensionnement
        let resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                createPaginationDots();
                updateSlider();
            }, 250);
        });
    }
    
    // Ouvrir le modal vidéo
    function openVideoModal(videoId) {
        const modal = $('#ctab-yt-modal');
        const playerContainer = $('#ctab-yt-player');
        
        modal.fadeIn(300);
        $('body').css('overflow', 'hidden');
        
        // Charger l'API YouTube si nécessaire
        loadYouTubeAPI().then(() => {
            // Détruire le player existant s'il y en a un
            if (youtubePlayer) {
                youtubePlayer.destroy();
            }
            
            // Créer un nouveau player
            youtubePlayer = new YT.Player('ctab-yt-player', {
                videoId: videoId,
                width: '100%',
                height: '100%',
                playerVars: {
                    autoplay: 1,
                    modestbranding: 1,
                    rel: 0,
                    showinfo: 0,
                    iv_load_policy: 3
                },
                events: {
                    onReady: function(event) {
                        event.target.playVideo();
                    }
                }
            });
        });
    }
    
    // Fermer le modal vidéo
    function closeVideoModal() {
        const modal = $('#ctab-yt-modal');
        
        // Arrêter la vidéo
        if (youtubePlayer && youtubePlayer.stopVideo) {
            youtubePlayer.stopVideo();
        }
        
        modal.fadeOut(300, function() {
            // Détruire le player
            if (youtubePlayer) {
                youtubePlayer.destroy();
                youtubePlayer = null;
            }
            
            // Nettoyer le conteneur
            $('#ctab-yt-player').html('');
        });
        
        $('body').css('overflow', '');
        
        // Redémarrer l'autoplay du slider
        if (sliderConfig.autoplay) {
            setTimeout(() => {
                initSlider();
            }, 300);
        }
    }
    
    // Initialiser au chargement de la page
    $(document).ready(function() {
        if ($('.ctab-youtube-slider').length > 0) {
            initSlider();
        }
    });
    
})(jQuery);
