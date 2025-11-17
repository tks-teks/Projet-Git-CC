Objectif principal

Permettre aux patients de rechercher un médicament (par nom commercial ou substance active), consulter sa fiche (indications, posologie, effets secondaires, contre-indications), puis localiser et commander le médicament auprès d’une pharmacie proche (réservation, retrait en magasin ou livraison).

Acteurs

Patient / Utilisateur : recherche, lit les fiches médicaments, commande, paie.

Pharmacien / Pharmacie : gère catalogue, confirme disponibilités, prépare commandes, propose livraison/retrait.

Administrateur : modère fiches, gère comptes pharmacies, supervise analytics.

Système tiers : API géolocalisation, API paiement, base de données médicaments (ou référentiel officiel).

Fonctionnalités principales (MVP)

Recherche de médicament

Recherche par nom commercial, DCI (dénomination commune internationale), ou code CIP/ATC.

Suggestions automatiques et correction orthographique.

Fiche médicament

Principes actifs, forme galénique, posologie, contre-indications, interactions, effets indésirables.

Indication si médicament soumis à ordonnance.

Images (boîte / pilule) et notice PDF (si disponible).

Géolocalisation de pharmacies

Affichage des pharmacies proches (tri par distance, horaire d’ouverture, disponibilité).

Carte interactive + liste.

Disponibilité & réservation

Vérification en temps réel du stock (ou estimation).

Réservation / mise de côté (X minutes) + option retrait en magasin ou livraison.

Paiement

Paiement en ligne (carte, mobile money) + paiement à la livraison possible.

Facture/bon de commande téléchargeable.

Profil patient

Informations de contact, adresses, historique des commandes, ordonnances (upload sécurisé).

Notifications

Statut commande (préparée, prête, en route).

Rappels de prise / renouvellement (optionnel).

Fonctionnalités avancées (roadmap)

Téléconsultation avec un pharmacien/médecin.

Reconnaissance d’ordonnance via photo + extraction automatique.

Suggestions de génériques moins coûteux.

Programme de fidélité, promo et comparaison prix.

Intégration avec dossier médical électronique (DME) via API sécurisée.

Parcours utilisateur (exemple)

L’utilisateur ouvre la PWA (installe-optionnelle).

Page d’accueil : barre de recherche visible + bouton « pharmacies proches ».

Recherche « paracétamol » → résultats + fiches.

Sur la fiche : bouton « Trouver en pharmacie » → carte + liste triée par distance.

Choix pharmacie → vérifier stock → ajouter au panier → choisir retrait ou livraison → payer.

Réception d’une notification « commande prête » → récupération ou suivi livraison.

UX / Écrans clés

Écran d’accueil (recherche + accès rapide aux ordonnances).

Résultats de recherche (liste + filtres).

Fiche médicament (informations structurées + bouton action).

Carte / liste pharmacies (distance, disponibilité, horaires).

Panier & paiement.

Profil / historique / upload ordonnance.

Écran confirmation + suivi commande.

Comportement PWA & offline

Manifest.json (nom, icône, thème, écran d’accueil) pour installation.

Service Worker :

Caching stratégique : cache-first pour assets UI, network-first pour données critiques (disponibilité, prix).

Stratégies de fallback offline : affichage des dernières fiches consultées et message clair si fonctionnalités dépendant du réseau.

Push notifications pour statut commande et rappels (avec permission explicite).

Sécurité & confidentialité

TLS obligatoire (HTTPS).

Authentification sécurisée (JWT ou OAuth2), sessions chiffrées.

Stockage local : ne jamais stocker d’informations sensibles non chiffrées (ex : ordonnances chiffrées).

Conformité RGPD (consentement pour données personnelles, droit d’accès/suppression).

Vérification des pharmacies (KYC) et modération des fiches produits.

Limiter les données stockées côté client et supprimer après délai.

Architecture & intégrations

Frontend : PWA en HTML/CSS/JS — frameworks possibles : React, Vue, Svelte (avec Workbox pour SW).

Backend : API REST/GraphQL (Node.js/Express, Django, Laravel, etc.)

Base de données : PostgreSQL / MySQL pour utilisateurs, commandes ; moteur NoSQL pour caches si besoin.

APIs externes :

Geo / Maps (Google Maps, OpenStreetMap + Nominatim / Mapbox).

Référentiel médicaments (source nationale ou base pharmaceutique officielle).

Fournisseur paiement (Stripe, PayPal, Orange Money / MTN Mobile Money selon pays).

Service SMS / push (Firebase Cloud Messaging, Twilio).

Authentification pharmacie : portail séparé (web ou mobile) pour mise à jour stock en temps réel.

Modèle de données (extrait simplifié)

Users (id, nom, email, tél, adresse, role)

Pharmacies (id, nom, adresse, lat, lng, horaires, contact)

Medications (id, nom, dci, description, ordonnance_bool, image, notice_url)

Stocks (pharmacy_id, medication_id, quantity, last_updated)

Orders (id, user_id, pharmacy_id, status, total, payment_method, created_at)

Prescriptions (id, user_id, file_url, verified_bool)

UX / Accessibilité

Texte lisible, contraste élevé, support pour lecteurs d’écran (ARIA).

Navigation clavier / tap-friendly.

Interface simple : prioriser recherches rapides et boutons d’action clairs.

Performance & qualité

Chargement rapide (<2s sur mobile 3G) : lazy-loading, compression, images responsives (webp).

Tests end-to-end (Cypress), tests unitaires, tests de charge sur API critiques.

Monitoring (Sentry, analytics anonymisés).

Monétisation & modèle économique (options)

Commission sur commandes traitées.

Abonnement premium pour pharmacies (meilleure visibilité, intégration ERP).

Publicité ciblée santé (avec consentement explicite).

MVP recommandé (durée estimative de développement)

Recherche + fiches médicaments.

Find-by-location + listing pharmacies.

Vérification stock basique (API ou interface pharmacie).

Commande simple + paiement.

Installation PWA + notifications basiques.
