# CTAB YouTube Slider Plugin

Un plugin WordPress professionnel pour afficher un slider élégant de vidéos YouTube avec gestion via l'interface d'administration.

## 📋 Caractéristiques

- ✅ Interface d'administration intuitive et moderne
- ✅ Slider responsive avec support RTL (Arabe)
- ✅ Intégration complète de l'API YouTube IFrame
- ✅ Lecture des vidéos dans un modal élégant
- ✅ Support multilingue (Français, Anglais, Arabe)
- ✅ Animations fluides et design moderne
- ✅ Compatible avec Elementor via shortcode
- ✅ Gestion de l'ordre d'affichage des vidéos
- ✅ Prévisualisation automatique des miniatures
- ✅ Autoplay configurable du slider
- ✅ Navigation tactile pour mobile

## 📦 Installation

1. **Téléchargez le plugin**
   - Téléchargez le dossier `ctab-youtube-slider`

2. **Installation via FTP**
   - Uploadez le dossier `ctab-youtube-slider` dans `/wp-content/plugins/`
   
   OU

3. **Installation via WordPress**
   - Compressez le dossier `ctab-youtube-slider` en ZIP
   - Dans WordPress : Extensions → Ajouter → Téléverser une extension
   - Choisissez le fichier ZIP et cliquez sur "Installer maintenant"

4. **Activation**
   - Allez dans Extensions → Extensions installées
   - Activez "CTAB YouTube Slider"

## 🚀 Utilisation

### 1. Ajouter des vidéos

1. Dans le menu WordPress, cliquez sur "YouTube Slider"
2. Remplissez le formulaire d'ajout :
   - **Titre** : Le titre de votre vidéo
   - **ID ou URL YouTube** : Vous pouvez coller :
     - L'URL complète : `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
     - Ou juste l'ID : `dQw4w9WgXcQ`
   - **Description** : Une brève description
   - **Ordre d'affichage** : Numéro pour l'ordre (plus petit = premier)
3. Cliquez sur "Ajouter la vidéo"

### 2. Comment trouver l'ID YouTube

L'ID YouTube se trouve dans l'URL de la vidéo :
```
https://www.youtube.com/watch?v=dQw4w9WgXcQ
                                  ^^^^^^^^^^^
                                  Ceci est l'ID
```

Vous pouvez aussi coller l'URL complète, le plugin extraira automatiquement l'ID.

### 3. Afficher le slider

#### Dans une page ou un article :
```
[ctab_youtube_slider]
```

#### Avec Elementor :
1. Ajoutez un widget "Shortcode"
2. Collez : `[ctab_youtube_slider]`

#### Dans un fichier de thème :
```php
<?php echo do_shortcode('[ctab_youtube_slider]'); ?>
```

## 🎨 Personnalisation

### Modifier les couleurs

Éditez `/public/css/slider.css` :

```css
/* Changer le gradient de l'en-tête */
.ctab-yt-container {
    background: linear-gradient(135deg, #VOTRE_COULEUR1 0%, #VOTRE_COULEUR2 100%);
}

/* Changer la couleur des boutons */
.ctab-yt-slider-prev:hover,
.ctab-yt-slider-next:hover {
    background: #VOTRE_COULEUR;
}
```

### Modifier le nombre de vidéos affichées

Éditez `/public/js/slider.js` :

```javascript
const sliderConfig = {
    autoplay: true,
    autoplaySpeed: 6000,
    slidesPerView: 3, // Changez ce nombre (2, 3, ou 4)
};
```

### Désactiver l'autoplay

Dans `/public/js/slider.js` :

```javascript
const sliderConfig = {
    autoplay: false, // Changez à false
    autoplaySpeed: 6000,
    slidesPerView: 3,
};
```

## 📁 Structure des fichiers

```
ctab-youtube-slider/
│
├── ctab-youtube-slider.php    # Fichier principal du plugin
│
├── admin/                      # Dossier d'administration
│   ├── admin.php              # Fonctions d'administration
│   ├── css/
│   │   └── admin.css          # Styles de l'interface admin
│   ├── js/
│   │   └── admin.js           # Scripts de l'interface admin
│   └── views/
│       └── admin-page.php     # Template de la page admin
│
└── public/                     # Dossier public (frontend)
    ├── css/
    │   └── slider.css         # Styles du slider public
    └── js/
        └── slider.js          # Scripts du slider public
```

## 🔧 Configuration avancée

### Base de données

Le plugin crée une table : `wp_ctab_youtube_videos`

Structure :
- `id` : ID unique
- `title` : Titre de la vidéo
- `video_id` : ID YouTube
- `thumbnail_url` : URL de la miniature
- `description` : Description
- `display_order` : Ordre d'affichage
- `created_at` : Date de création

### Hooks disponibles

Vous pouvez personnaliser le plugin avec des hooks WordPress :

```php
// Modifier le nombre de vidéos par défaut
add_filter('ctab_youtube_slides_per_view', function($count) {
    return 4; // Votre nombre
});
```

## 🌐 Support multilingue

Le plugin supporte automatiquement :
- Français (FR)
- Anglais (EN)
- Arabe (AR) avec mode RTL

La détection se fait automatiquement via l'URL de la page.

## 📱 Responsive

Le slider s'adapte automatiquement :
- **Desktop (>1200px)** : 3 vidéos
- **Tablette (768-1200px)** : 2 vidéos
- **Mobile (<768px)** : 1 vidéo

## 🐛 Dépannage

### Les vidéos ne s'affichent pas

1. Vérifiez que l'ID YouTube est correct
2. Assurez-vous que la vidéo est publique sur YouTube
3. Vérifiez la console du navigateur pour les erreurs JavaScript

### Le slider ne fonctionne pas

1. Vérifiez que jQuery est chargé
2. Désactivez les autres plugins pour tester les conflits
3. Videz le cache du navigateur et de WordPress

### Les miniatures ne s'affichent pas

Les miniatures YouTube sont chargées directement depuis YouTube. Si elles ne s'affichent pas :
1. Vérifiez la connexion Internet
2. Assurez-vous que YouTube n'est pas bloqué
3. Vérifiez que l'ID de la vidéo est valide

## 🔒 Sécurité

Le plugin utilise :
- Nonces WordPress pour la validation des formulaires
- Sanitization de toutes les entrées utilisateur
- Échappement des sorties
- Vérification des permissions utilisateur

## 📄 Licence

Ce plugin est développé pour le CTAB (Centre Technique de l'Agriculture Biologique).

## 👨‍💻 Support

Pour toute question ou support, contactez l'équipe de développement CTAB.

## 📝 Changelog

### Version 1.0.0
- Version initiale
- Interface d'administration complète
- Slider responsive avec support RTL
- Intégration YouTube IFrame API
- Modal de lecture vidéo
- Support multilingue

## 🎯 Prochaines fonctionnalités

- [ ] Import en masse de vidéos
- [ ] Catégories de vidéos
- [ ] Statistiques de lecture
- [ ] Playlists YouTube
- [ ] Sous-titres personnalisés
