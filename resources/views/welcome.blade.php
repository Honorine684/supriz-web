<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supriz - Envoie un message supriz inoubliable</title>

    {{-- SEO Meta Tags --}}
    @include('partials.seo', [
        'title' => 'Supriz - Envoie un message supriz inoubliable',
        'description' => 'Créez et partagez des messages surprises personnalisés avec vos proches. Anniversaire, mariage, voeux - une expérience unique et mémorable.',
        'keywords' => 'supriz, message surprise, cadeau personnalisé, anniversaire, mariage, voeux, nouvel an, noel, message personnalisé, carte virtuelle, surprise, cadeau original, Bénin, Afrique, message vidéo, message audio'
    ])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Mobile optimizations */
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        html {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #005880 0%, #00789C 100%);
        }

        /* Navigation */
        .navbar {
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #FFD700 !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 80%;
            height: 2px;
            background: #FFD700;
            transition: transform 0.3s ease;
        }

        .nav-link:hover::after {
            transform: translateX(-50%) scaleX(1);
        }

        /* Point pour le lien actif */
        .nav-link.active::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #FFD700;
            border-radius: 50%;
        }

        /* Boutons de la navbar */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        /* Bouton 3D Premium - Style moderne et professionnel */
        .hero-btn {
            position: relative;
            background: linear-gradient(145deg, #FFD700 0%, #FFC700 50%, #FFB700 100%) !important;
            color: #004570 !important;
            border: none !important;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.4) inset,
                0 -1px 0 rgba(0, 0, 0, 0.1) inset,
                0 6px 0 #CC9500,
                0 8px 0 rgba(0, 0, 0, 0.15),
                0 12px 30px rgba(204, 149, 0, 0.4) !important;
            transform: translateY(0);
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.3);
            letter-spacing: 0.5px;
            overflow: hidden;
        }

        .hero-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .hero-btn:hover::before {
            left: 100%;
        }

        .hero-btn:hover {
            transform: translateY(-3px) !important;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.5) inset,
                0 -1px 0 rgba(0, 0, 0, 0.1) inset,
                0 9px 0 #CC9500,
                0 11px 0 rgba(0, 0, 0, 0.15),
                0 16px 40px rgba(204, 149, 0, 0.5) !important;
            background: linear-gradient(145deg, #FFE14D 0%, #FFD700 50%, #FFC700 100%) !important;
        }

        .hero-btn:active {
            transform: translateY(3px) !important;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.3) inset,
                0 -1px 0 rgba(0, 0, 0, 0.1) inset,
                0 3px 0 #CC9500,
                0 5px 0 rgba(0, 0, 0, 0.15),
                0 6px 20px rgba(204, 149, 0, 0.3) !important;
        }

        body {
            padding-top: 0;
            margin: 0;
        }

        /* Touch-friendly buttons */
        .btn,
        button,
        a[role="button"] {
            min-height: 44px;
            min-width: 44px;
            touch-action: manipulation;
        }

        /* Prevent zoom on inputs (iOS) */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        select {
            font-size: 16px !important;
        }

        /* Sections styling */
        .section-wrapper {
            padding: 80px 0;
        }

        /* Occasion cards hover effect */
        .occasion-card-new {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .occasion-card-new:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .occasion-card-new img {
            transition: transform 0.3s ease;
        }

        .occasion-card-new:hover img {
            transform: scale(1.05);
        }

        /* Animation infinie */
        @keyframes scrollInfinite {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .infinite-scroll:hover {
            animation-play-state: paused;
        }

        /* Marquee animation for categories banner */
        @keyframes marqueeScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-25%);
            }
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        /* Animation marquee pour témoignages */
        @keyframes testimonialsScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .testimonials-marquee-track:hover {
            animation-play-state: paused;
        }

        .marquee-item:hover {
            background: rgba(255,255,255,0.3) !important;
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        /* How it works cards */
        .how-it-works-card {
            transition: transform 0.3s ease;
        }

        .how-it-works-card:hover {
            transform: translateY(-5px);
        }

        /* Hero adjustments */
        .hero-section {
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            position: relative;
            animation: gradientShift 15s ease infinite;
            background-size: 200% 200%;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* About Section - Rotating Animation */
        @keyframes rotateInfinite {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .about-illustration-new img {
            animation: rotateInfinite 20s linear infinite;
        }

        /* ========== ANIMATIONS FLUIDES ========== */

        /* Fade-in au scroll pour les sections */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Classes d'animation */
        .animate-on-scroll {
            opacity: 0;
            transition: all 0.6s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        /* Effet smooth sur les cartes */
        .occasion-card-new,
        .category-card,
        .faq-item-new {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .occasion-card-new:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Parallax léger sur le hero */
        .hero-parallax {
            transition: transform 0.3s ease-out;
        }

        /* Effet ripple au clic */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple-effect:active::after {
            width: 300px;
            height: 300px;
        }

        /* Animation de délai pour les éléments */
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        /* Hero person animation */
        .hero-person-animation {
            z-index: 1;
        }

        /* Hero container */
        .hero-container {
            position: relative;
            z-index: 2;
            padding-top: 120px;
            padding-bottom: 40px;
        }

        .hero-row {
            min-height: calc(100vh - 160px);
        }

        /* Hero image desktop */
        .hero-image-desktop {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 41.666667%;
            pointer-events: none;
            z-index: 1;
            line-height: 0;
        }

        /* Hero image mobile - hidden by default */
        .hero-image-mobile {
            display: none;
        }

        .hero-person-animation-mobile {
            position: relative;
            width: 100%;
            max-width: 300px;
            margin: 4vh auto !important;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .hero-section .container {
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                height: 100vh;
                margin-top: 32vh;
            }

            .hero-section {
                min-height: 0 !important;
                height: auto !important;
            }

            .hero-row {
                min-height: 0 !important;
                height: auto !important;
            }

            .hero-image-mobile {
                display: block !important;
            }

            .hero-person-animation-mobile {
                margin: 6vh auto 0 !important;
            }

            .hero-content {
                text-align: center;
                padding: 20px 15px;
            }

            .hero-title {
                font-size: 32px !important;
            }

            .hero-description {
                font-size: 16px !important;
            }
        }

        @media (min-width: 769px) and (max-width: 991px) {
            .hero-section {
                min-height: 0 !important;
                height: auto !important;
            }

            .hero-container {
                padding-top: 100px !important;
                padding-bottom: 40px !important;
            }

            .hero-row {
                min-height: 0 !important;
                height: auto !important;
            }

            .hero-image-mobile {
                display: block !important;
            }

            .hero-content {
                text-align: center;
                padding: 20px 15px;
            }

            .hero-title {
                font-size: 32px !important;
            }

            .hero-description {
                font-size: 16px !important;
            }
        }

        @media (min-width: 992px) {
            .hero-image-mobile {
                display: none !important;
            }
        }

        . {
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        /* Floating icons animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-15px) rotate(3deg);
            }
            50% {
                transform: translateY(-30px) rotate(-3deg);
            }
            75% {
                transform: translateY(-15px) rotate(2deg);
            }
        }

        .floating-icon {
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
        }

        .confetti-icon {
            animation-delay: 0s;
        }

        .social-icon {
            animation-delay: 1s;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .float-element {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }

        .float-element.gift {
            top: 20%;
            right: 10%;
            width: 60px;
            animation-delay: 0s;
        }

        .float-element.balloon {
            top: 60%;
            right: 5%;
            width: 50px;
            animation-delay: 2s;
        }

        .float-element.confetti {
            bottom: 20%;
            left: 5%;
            width: 70px;
            animation-delay: 1s;
        }

        .float-element.star {
            top: 40%;
            left: 10%;
            width: 40px;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) rotate(5deg);
            }
            75% {
                transform: translateY(10px) rotate(-5deg);
            }
        }


        /* About Section with Circular Layout */
        .about-illustration {
            position: relative;
            width: 600px;
            height: 600px;
            margin: 60px auto;
        }

        .central-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .feature-circles {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .feature-circle {
            position: absolute;
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 200px;
        }

        .feature-circle:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 120, 156, 0.2);
        }

        .feature-circle.top-left {
            top: 50px;
            left: 30px;
        }

        .feature-circle.top-right {
            top: 50px;
            right: 30px;
        }

        .feature-circle.bottom-left {
            bottom: 50px;
            left: 30px;
        }

        .feature-circle.bottom-right {
            bottom: 50px;
            right: 30px;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .feature-icon i {
            font-size: 24px;
            color: white;
        }

        .feature-content h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .feature-content p {
            font-size: 13px;
            color: var(--text-light);
            line-height: 1.4;
            margin: 0;
        }

        /* Connecting lines (optional) */
        .feature-circle::before {
            content: '';
            position: absolute;
            width: 2px;
            height: 100px;
            background: linear-gradient(to bottom, transparent, #E5E7EB, transparent);
            z-index: -1;
        }

        .feature-circle.top-left::before {
            bottom: -80px;
            right: -40px;
            transform: rotate(45deg);
        }

        .feature-circle.top-right::before {
            bottom: -80px;
            left: -40px;
            transform: rotate(-45deg);
        }

        .feature-circle.bottom-left::before {
            top: -80px;
            right: -40px;
            transform: rotate(-45deg);
        }

        .feature-circle.bottom-right::before {
            top: -80px;
            left: -40px;
            transform: rotate(45deg);
        }

        @media (max-width: 768px) {
            .about-illustration {
                width: 100%;
                height: auto;
                margin: 40px auto;
            }

            .central-circle img {
                width: 200px !important;
                height: 200px !important;
            }

            .feature-circles {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                position: static;
                margin-top: 40px;
            }

            .feature-circle {
                position: static !important;
                width: 100%;
            }

            .feature-circle::before {
                display: none;
            }
        }

        /* Loading screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 80px;
            height: 80px;
            position: relative;
        }

        .loader::before,
        .loader::after {
            content: '';
            position: absolute;
            border: 4px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loader::before {
            width: 80px;
            height: 80px;
            top: 0;
            left: 0;
        }

        .loader::after {
            width: 60px;
            height: 60px;
            top: 10px;
            left: 10px;
            animation-direction: reverse;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Carousel Styles */
        .categories-carousel-wrapper {
            position: relative;
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 0 60px;
        }

        .categories-carousel {
            overflow: hidden;
            border-radius: 20px;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            gap: 20px;
        }

        .carousel-track .category-card {
            min-width: 220px;
            flex: 0 0 220px;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 20px rgba(0, 120, 156, 0.3);
            z-index: 10;
        }

        .carousel-prev {
            left: 0;
        }

        .carousel-next {
            right: 0;
        }

        .carousel-nav:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 12px 30px rgba(0, 120, 156, 0.4);
            background: linear-gradient(135deg, #00A3CE 0%, #00789C 100%);
        }

        .carousel-nav:active {
            transform: translateY(-50%) scale(0.95);
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
        }

        .carousel-dots .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #D1D5DB;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dots .dot.active {
            width: 35px;
            border-radius: 6px;
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
        }

        .carousel-dots .dot:hover {
            background: #9CA3AF;
            transform: scale(1.2);
        }

        .carousel-dots .dot.active:hover {
            transform: scale(1.1);
        }

        /* Auto-scroll animation */
        @keyframes autoScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .auto-scroll .carousel-track {
            animation: autoScroll 30s linear infinite;
        }

        .categories-carousel:hover .carousel-track {
            animation-play-state: paused;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 48px !important;
            }

            .hero-description {
                font-size: 17px !important;
            }
        }

        @media (max-width: 992px) {
            /* Navigation */
            .navbar {
                padding: 15px 0 !important;
                background: rgba(255, 255, 255, 0.15) !important;
                backdrop-filter: blur(10px);
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.15);
                margin-top: 15px;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            }

            .navbar-nav {
                align-items: center;
                gap: 8px;
            }

            .navbar-nav .nav-link {
                padding: 12px 20px !important;
                text-align: center;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .navbar-nav .nav-link:hover {
                background: rgba(255,255,255,0.1);
            }

            .navbar .d-flex {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
                gap: 10px !important;
            }

            .navbar .d-flex .btn {
                width: 100%;
                margin: 0 !important;
                padding: 12px 25px !important;
            }

            /* Hero Section - Tablet */
            .hero-section {
                padding: 100px 0 60px 0 !important;
                min-height: 90vh !important;
            }

            .hero-row {
                min-height: auto !important;
            }

            .hero-row > div:first-child {
                padding: 40px 30px !important;
            }

            .hero-row > div:last-child {
                height: 400px !important;
                max-height: 400px !important;
            }

            .hero-title {
                font-size: 38px !important;
                text-align: left;
                margin-bottom: 20px !important;
            }

            .hero-description {
                font-size: 17px !important;
                text-align: left;
                padding: 0;
                margin-bottom: 28px !important;
            }

            .hero-content {
                text-align: left;
                padding: 0;
            }

            .hero-btn {
                font-size: 16px !important;
                padding: 14px 36px !important;
            }

            .hero-person-animation .person-1,
            .hero-person-animation .person-2 {
                max-width: 80% !important;
            }

            /* Categories Section */
            .infinite-scroll-wrapper {
                padding: 0 20px;
            }

            .occasion-card-new {
                width: 300px !important;
                height: 180px !important;
            }

            /* Comment ça marche */
            .how-it-works-card {
                margin-bottom: 40px;
            }

            .comment-section {
                padding: 60px 0 !important;
            }

            .how-card {
                margin-bottom: 30px !important;
                margin-top: 40px !important;
            }

            .how-icon {
                width: 45px !important;
                height: 45px !important;
            }

            .how-icon img {
                width: 24px !important;
                height: 24px !important;
            }

            .how-title {
                font-size: 18px !important;
            }

            .how-text {
                font-size: 14px !important;
            }

            /* About Section */
            .about-illustration-new {
                max-width: 400px !important;
            }

            .about-text-content {
                padding-left: 20px !important;
            }

            .about-text-content h2 {
                font-size: 38px !important;
            }

            .about-text-content p {
                font-size: 16px !important;
            }
        }

        @media (max-width: 768px) {
            /* Fix scroll on mobile - CRITICAL */
            html {
                overflow-x: hidden !important;
                overflow-y: scroll !important;
                height: 100%;
                -webkit-overflow-scrolling: touch;
                max-width: 100vw !important;
                width: 100% !important;
            }

            body {
                overflow-x: hidden !important;
                overflow-y: visible !important;
                height: auto !important;
                min-height: 100vh;
                position: relative !important;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* FORCE: No element can overflow horizontally */
            *:not(html):not(body) {
                max-width: 100% !important;
            }

            /* FORCE: Box sizing for all */
            *,
            *::before,
            *::after {
                box-sizing: border-box !important;
            }

            /* Ensure sections can scroll */
            section {
                position: relative;
                max-width: 100vw;
                overflow-x: hidden;
            }

            /* Container responsiveness */
            .container,
            .container-fluid {
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Row responsiveness */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100%;
            }

            /* Prevent any element from overflowing */
            div, section, main, article, aside, header, footer, nav {
                max-width: 100% !important;
            }

            img, video, iframe, svg {
                max-width: 100% !important;
                height: auto !important;
            }

            /* Fix flex containers */
            [style*="display: flex"],
            [class*="d-flex"],
            .d-flex,
            .flex {
                flex-wrap: wrap !important;
            }

            /* Fix grid containers */
            [style*="display: grid"],
            [class*="grid"] {
                grid-template-columns: 1fr !important;
            }

            /* Hero Section */
            .hero-section {
                min-height: 100vh !important;
                background-size: cover !important;
                background-position: center !important;
                padding-top: 80px !important;
                display: flex;
                align-items: center;
                position: relative !important;
            }

            .hero-row {
                min-height: auto !important;
            }

            /* Hero - Mobile Layout: Image top, Text left, Button below */
            .hero-section {
                padding: 0 !important;
                min-height: 100vh !important;
                display: flex !important;
                align-items: center !important;
            }

            .hero-section .container {
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .hero-row {
                flex-direction: column !important;
                min-height: auto !important;
                margin: 0 !important;
                gap: 0 !important;
            }

            .hero-row > div:first-child {
                order: 1 !important;
                padding: 30px 20px 30px 20px !important;
                width: 100% !important;
            }

            .hero-row > div:last-child {
                order: 2 !important;
                position: relative !important;
                width: 100% !important;
                height: 40vh !important;
                min-height: 280px !important;
                max-height: 380px !important;
                padding: 0 !important;
                margin: -50px 0 0 0 !important;
                overflow: visible !important;
                display: block !important;
            }

            .hero-person-animation {
                height: 100% !important;
                width: 100% !important;
                position: relative !important;
                display: flex !important;
                align-items: flex-end !important;
                justify-content: center !important;
            }

            .hero-person-animation img {
                max-width: 100% !important;
                height: auto !important;
                max-height: 100% !important;
                object-fit: contain !important;
                display: block !important;
            }

            .hero-person-animation .person-1,
            .hero-person-animation .person-2 {
                position: absolute !important;
                bottom: 0 !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                max-width: 85% !important;
                height: auto !important;
                max-height: 100% !important;
                width: auto !important;
            }

            /* Show floating icons on mobile - smaller */
            .floating-icon {
                display: block !important;
            }

            .confetti-icon {
                width: 50px !important;
                top: 5% !important;
                left: 10% !important;
            }

            .social-icon {
                width: 45px !important;
                top: 10% !important;
                right: 10% !important;
            }

            .hero-content {
                padding: 0 !important;
                text-align: left !important;
                max-width: 100% !important;
            }

            .hero-title {
                font-size: 32px !important;
                font-weight: 700 !important;
                margin-bottom: 16px !important;
                line-height: 1.2 !important;
                text-align: left !important;
            }

            .hero-title br {
                display: inline !important;
            }

            .hero-description {
                font-size: 16px !important;
                margin-bottom: 24px !important;
                line-height: 1.6 !important;
                text-align: left !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .hero-btn {
                font-size: 15px !important;
                padding: 14px 28px !important;
                width: auto !important;
                max-width: 100% !important;
                display: inline-block !important;
                margin: 0 !important;
                text-align: center !important;
                white-space: normal !important;
                line-height: 1.3 !important;
            }

            /* Section Spacing */
            .section-wrapper {
                padding: 30px 20px !important;
            }

            #categories {
                padding: 8px 0 !important;
                margin-top: 0 !important;
            }

            #comment {
                padding: 30px 0 !important;
            }

            /* Section Titles */
            h2 {
                font-size: 28px !important;
                margin-bottom: 15px !important;
            }

            /* Categories Marquee Mobile */
            .marquee-container {
                overflow: hidden !important;
            }

            .marquee-container > div:first-child,
            .marquee-container > div:nth-child(2) {
                width: 30px !important;
            }

            .marquee-item {
                padding: 6px 14px !important;
                gap: 6px !important;
            }

            .marquee-item span {
                font-size: 12px !important;
            }

            .marquee-item i,
            .marquee-item img {
                font-size: 14px !important;
                width: 14px !important;
                height: 14px !important;
            }

            .marquee-track {
                gap: 10px !important;
                animation: marqueeScroll 25s linear infinite !important;
            }

            /* Categories Cards (legacy) */
            .occasion-card-new {
                width: 150px !important;
                height: 95px !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }

            .occasion-card-wrapper {
                flex-shrink: 0 !important;
                flex: 0 0 auto !important;
            }

            .occasion-card-new h3 {
                font-size: 11px !important;
                padding: 0 5px;
                bottom: 6px !important;
            }

            .occasion-card-new div[style*="width: 60px"] {
                width: 35px !important;
                height: 35px !important;
            }

            .occasion-card-new img[style*="width: 40px"] {
                width: 20px !important;
                height: 20px !important;
            }

            .categories-carousel {
                padding: 0 10px !important;
                overflow-x: hidden !important;
            }

            .carousel-track {
                gap: 12px !important;
            }

            /* Comment ça marche Cards - Tablet */
            .comment-section {
                padding: 80px 15px !important;
            }

            .how-card {
                margin-bottom: 35px !important;
                max-width: 90%;
                margin-left: auto;
                margin-right: auto;
            }

            /* FAQ */
            #faq {
                padding: 60px 20px !important;
            }

            #faq h2 {
                font-size: 32px !important;
                margin-bottom: 40px !important;
            }

            .faq-item-new {
                padding: 20px 0 !important;
            }

            .faq-question-new h3 {
                font-size: 16px !important;
            }

            .faq-answer-new p {
                font-size: 14px !important;
                padding-right: 0 !important;
            }

            .faq-icon {
                width: 20px !important;
                height: 20px !important;
                margin-left: 10px !important;
            }

            /* Contact Section */
            #contact {
                padding: 60px 15px !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            #contact h2 {
                font-size: 34px !important;
                margin-bottom: 20px !important;
                padding: 0 10px;
            }

            #contact > .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            #contact > .container > p {
                font-size: 15px !important;
                margin-bottom: 40px !important;
                padding: 0 10px;
                line-height: 1.6;
            }

            #contact .row {
                margin: 0 !important;
                max-width: 100% !important;
            }

            #contact .row > div {
                padding: 0 10px !important;
                margin-bottom: 20px;
                max-width: 100%;
            }

            #contact .row > div > div {
                padding: 30px 20px !important;
                border-radius: 15px !important;
                max-width: 100%;
            }

            #contact h3 {
                font-size: 22px !important;
                margin-bottom: 20px !important;
            }

            #contact input,
            #contact textarea {
                padding: 14px !important;
                font-size: 15px !important;
                border-radius: 8px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box;
            }

            #contact textarea {
                min-height: 120px !important;
            }

            #contact button {
                padding: 14px 25px !important;
                font-size: 16px !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 8px !important;
                box-sizing: border-box;
            }

            #contact .mb-3 {
                margin-bottom: 15px !important;
            }

            #contact form {
                max-width: 100%;
                overflow-x: hidden;
            }

            /* About Section - Mobile */
            #apropos {
                padding: 60px 15px !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            #apropos .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            #apropos .row {
                margin: 0 !important;
            }

            /* Comment Section */
            #comment {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            .comment-section {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            /* FAQ Section */
            #faq {
                max-width: 100vw !important;
                overflow-x: hidden !important;
                padding: 60px 15px !important;
            }

            #faq .container {
                max-width: 100% !important;
                padding: 0 10px !important;
            }

            .about-illustration-new {
                max-width: 300px !important;
                margin-bottom: 30px !important;
            }

            .about-text-content {
                padding-left: 0 !important;
                text-align: left;
            }

            .about-text-content h2 {
                font-size: 32px !important;
                text-align: left;
                margin-bottom: 20px !important;
            }

            .about-text-content p {
                font-size: 15px !important;
                line-height: 1.7 !important;
            }

            /* Navbar */
            .navbar {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            .navbar .container {
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Footer */
            footer {
                padding: 50px 15px 25px !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            footer .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            footer .row {
                row-gap: 30px !important;
                margin: 0 !important;
            }

            footer .row > div {
                text-align: left;
                margin-bottom: 25px;
                padding: 0 10px !important;
            }

            footer .row > div p {
                max-width: 100% !important;
                line-height: 1.6;
                text-align: left;
            }

            footer h4 {
                font-size: 18px !important;
                margin-bottom: 18px !important;
                text-align: left;
            }

            footer ul {
                text-align: left;
                padding: 0 10px;
            }

            footer ul li {
                margin-bottom: 10px;
            }

            footer div[style*="display: flex"] {
                justify-content: flex-start !important;
                flex-wrap: wrap;
            }

            footer .navbar-brand img {
                height: 38px !important;
            }
        }

        @media (max-width: 480px) {
            /* Ensure scrolling works and no overflow */
            html {
                overflow-y: scroll !important;
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }

            body {
                overflow-y: visible !important;
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }

            /* FORCE: Strict width control on small screens */
            * {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .container,
            .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            /* Navigation Logo */
            .navbar-brand img {
                height: 30px !important;
            }

            .navbar {
                padding: 8px 0 !important;
            }


            .hero-row > div:last-child {
                height: 40vh !important;
                min-height: 280px !important;
                max-height: 380px !important;
            }

            .hero-row > div:first-child {
                padding: 25px 15px 35px 15px !important;
            }

            .hero-content {
                padding: 0 !important;
                text-align: left !important;
            }

            .hero-title {
                font-size: 28px !important;
                line-height: 1.3 !important;
                margin-bottom: 14px !important;
                text-align: left !important;
            }

            .hero-description {
                font-size: 15px !important;
                line-height: 1.5 !important;
                margin-bottom: 20px !important;
                text-align: left !important;
                padding: 0 !important;
            }

            .hero-btn {
                font-size: 15px !important;
                padding: 13px 28px !important;
                width: 100% !important;
                max-width: 240px !important;
                display: inline-block !important;
                margin: 0 !important;
            }

            .hero-person-animation .person-1,
            .hero-person-animation .person-2 {
                max-width: 85% !important;
            }

            /* Categories */
            .infinite-scroll {
                flex-wrap: nowrap !important;
                gap: 12px !important;
            }

            .occasion-card-wrapper {
                flex-shrink: 0 !important;
                flex: 0 0 auto !important;
            }

            .occasion-card-new {
                width: 140px !important;
                height: 105px !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }

            .occasion-card-new div[style*="width: 60px"] {
                width: 50px !important;
                height: 50px !important;
            }

            .occasion-card-new img[style*="width: 40px"] {
                width: 32px !important;
                height: 32px !important;
            }

            .occasion-card-new h3 {
                font-size: 17px !important;
                bottom: 15px !important;
                padding: 0 8px;
            }

            .carousel-track {
                gap: 10px !important;
            }

            /* Comment ça marche */
            h2 {
                font-size: 28px !important;
                margin-bottom: 25px !important;
            }

            .comment-section {
                padding: 50px 15px !important;
            }

            .how-card {
                min-height: auto !important;
                height: 140px !important;
                padding: 0 15px 0 15px !important;
                margin-top: 25px !important;
                margin-bottom: 0 !important;
                margin-left: auto !important;
                margin-right: auto !important;
                border-radius: 12px !important;
                max-width: 95%;
            }

            #comment .row > * {
                margin-top: 0 !important;
            }

            .how-icon {
                width: 40px !important;
                height: 40px !important;
                top: -20px !important;
            }

            .how-icon img {
                width: 22px !important;
                height: 22px !important;
            }

            .how-title {
                font-size: 14px !important;
                margin: 8px 0 6px 0 !important;
            }

            .how-text {
                font-size: 12px !important;
                line-height: 1.4 !important;
                line-height: 1.6 !important;
            }

            .comment-btn {
                width: 90%;
                max-width: 280px;
                font-size: 15px !important;
                padding: 13px 28px !important;
                margin: 20px auto 0 !important;
                display: block;
            }

            /* Contact Form */
            .submit-button {
                width: 100%;
                font-size: 14px;
                padding: 12px 20px;
            }

            /* About Section - Small Mobile */
            .about-illustration-new {
                max-width: 250px !important;
            }

            .about-text-content h2 {
                font-size: 26px !important;
            }

            .about-text-content p {
                font-size: 14px !important;
            }

            /* FAQ - Small Mobile */
            #faq h2 {
                font-size: 26px !important;
            }

            .faq-question-new h3 {
                font-size: 15px !important;
            }

            .faq-answer-new p {
                font-size: 13px !important;
            }

            /* Contact - Small Mobile */
            #contact h2 {
                font-size: 26px !important;
            }

            #contact > .container > p {
                font-size: 13px !important;
            }

            #contact .row > div > div {
                padding: 25px 15px !important;
            }

            #contact h3 {
                font-size: 20px !important;
            }

            /* Footer - Small Mobile */
            footer {
                padding: 30px 15px 15px !important;
            }

            footer h4 {
                font-size: 16px !important;
                margin-bottom: 15px !important;
            }

            footer p,
            footer a {
                font-size: 13px !important;
            }

            footer img {
                height: 32px !important;
            }

            footer a[style*="width: 35px"] {
                width: 32px !important;
                height: 32px !important;
            }

            footer i {
                font-size: 16px !important;
            }
        }

        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .hero-section {
                min-height: 100vh !important;
            }

            .hero-title {
                font-size: 28px !important;
            }

            .hero-description {
                font-size: 17px !important;
            }

            .hero-content {
                padding: 20px;
            }
        }

        /* Fix for inline styles on responsive */
        @media (max-width: 768px) {
            /* Force all elements to fit screen */
            * {
                box-sizing: border-box !important;
            }

            /* Fix large font sizes */
            [style*="font-size: 48px"],
            [style*="font-size: 64px"],
            [style*="font-size: 56px"] {
                font-size: 32px !important;
            }

            /* Fix large paddings */
            [style*="padding: 100px"],
            [style*="padding: 80px"] {
                padding: 60px 15px !important;
            }

            /* Fix min-height 100vh on sections that might cause issues */
            section[style*="min-height: 100vh"] {
                height: 100vh !important;
            }

            /* Fix any fixed widths in pixels */
            [style*="width: 600px"],
            [style*="width: 500px"],
            [style*="width: 400px"] {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Ensure no negative margins */
            [style*="margin-left: -"],
            [style*="margin-right: -"] {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Container padding override */
            .container,
            .container-fluid,
            [class*="container"] {
                padding-left: 15px !important;
                padding-right: 15px !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    @include('partials.gtm-body')

    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
    </div>

    <!-- Categories Marquee Banner -->
    <section id="categories" style="background: linear-gradient(135deg, #1B7B6A 0%, #145C50 100%); padding: 10px 0; overflow: hidden; position: relative; z-index: 1001;">
        <div class="marquee-container" style="position: relative; width: 100%; overflow: hidden;">
            <!-- Gradient fade edges -->
            <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 60px; background: linear-gradient(to right, #1B7B6A, transparent); z-index: 2;"></div>
            <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 60px; background: linear-gradient(to left, #145C50, transparent); z-index: 2;"></div>

            <div class="marquee-track" style="display: flex; gap: 20px; animation: marqueeScroll 40s linear infinite; width: max-content;">
                @php
                    $defaultCategoriesMarquee = [
                        ['name' => 'Anniversaire', 'icon' => 'bi-gift'],
                        ['name' => 'Mariage', 'icon' => 'bi-heart'],
                        ['name' => 'Naissance', 'icon' => 'bi-balloon'],
                        ['name' => 'Nouvel an', 'icon' => 'bi-stars'],
                        ['name' => 'Encouragement', 'icon' => 'bi-hand-thumbs-up'],
                        ['name' => 'Remerciement', 'icon' => 'bi-emoji-smile'],
                        ['name' => 'Fete des meres', 'icon' => 'bi-flower1'],
                        ['name' => 'Saint-Valentin', 'icon' => 'bi-suit-heart'],
                    ];
                @endphp

                @for($i = 0; $i < 4; $i++)
                    @if(isset($categoriesSupriz) && count($categoriesSupriz) > 0)
                        @foreach($categoriesSupriz as $categorie)
                        <a href="{{ route('nouveau.message') }}?categorie={{ $categorie->id }}" class="marquee-item" style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 8px 18px; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.2); flex-shrink: 0;">
                            @if($categorie->icone)
                                <img src="{{ asset('storage/' . $categorie->icone) }}" alt="" style="width: 18px; height: 18px; filter: brightness(0) invert(1);">
                            @else
                                <i class="bi bi-gift" style="font-size: 16px; color: white;"></i>
                            @endif
                            <span style="color: white; font-size: 13px; font-weight: 500; white-space: nowrap;">{{ $categorie->name_sous_categories }}</span>
                        </a>
                        @endforeach
                    @else
                        @foreach($defaultCategoriesMarquee as $cat)
                        <a href="{{ route('nouveau.message') }}" class="marquee-item" style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 8px 18px; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.2); flex-shrink: 0;">
                            <i class="{{ $cat['icon'] }}" style="font-size: 16px; color: white;"></i>
                            <span style="color: white; font-size: 13px; font-weight: 500; white-space: nowrap;">{{ $cat['name'] }}</span>
                        </a>
                        @endforeach
                    @endif
                @endfor
            </div>
        </div>
    </section>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg position-absolute w-100" style="background: transparent; padding: 30px 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="/" style="text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="Supriz" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: none;">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="#accueil" style="font-size: 15px; font-weight: 400;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="#comment" style="font-size: 15px; font-weight: 400;">Comment ça marche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="#apropos" style="font-size: 15px; font-weight: 400;">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="#faq" style="font-size: 15px; font-weight: 400;">FAQ</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center" style="gap: 15px;">
                    <a href="{{ route('nouveau.message') }}" class="btn" style="background: #005792; color: white; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; white-space: nowrap;">
                        Surprenez un proche !!
                    </a>
                    <a href="#contact" class="btn" style="background: #E4D1B9; color: #005792; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; white-space: nowrap;">
                        Contactez-nous
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="hero-section position-relative" style="background-image: url('{{ asset('images/landing/Bg.svg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh;">
        <div class="container hero-container">
            <div class="row align-items-center hero-row">
                <div class="col-lg-7 col-md-12">
                    <div class="hero-content">
                        <h1 class="hero-title mb-4" style="color: white; font-weight: 700; line-height: 1.1;">
                            Envoie un message <br>supriz inoubliable
                        </h1>
                        <p class="hero-description mb-5" style="color: white; line-height: 1.5; font-weight: 300;">
                            Pour anniversaires, mariages, fêtes et toutes les occasions. Exprimez vos vœux, sentiments et intentions d'une manière beaucoup plus originale grâce à un lien unique et mémorable.
                        </p>
                        <a href="{{ route('nouveau.message') }}" class="btn hero-btn mb-4" style="padding: 12px 40px; border-radius: 25px; font-size: 17px; text-decoration: none; display: inline-block; white-space: nowrap;">
                            Crée un message
                        </a>
                    </div>

                    <!-- Image on mobile - below button -->
                    <div class="hero-image-mobile">
                        <div class="hero-person-animation-mobile">
                            <img src="{{ asset('images/landing/Property 1=Default.svg') }}" alt="Person 1" class="person-image-mobile person-1-mobile" style="position: relative; width: 1200px !important; height: auto; display: block;">
                            <img src="{{ asset('images/landing/Property 1=Variant2.svg') }}" alt="Person 2" class="person-image-mobile person-2-mobile" style="position: absolute; top: 0; left: 0; width: 1200px !important; height: auto; display: none;">
                            <img src="{{ asset('images/landing/Confetti Papers.svg') }}" alt="Confetti" class="floating-icon-mobile confetti-icon-mobile" style="position: absolute; width: 50px; height: auto; top: 5%; left: 5%; animation: float 3s ease-in-out infinite; z-index: 10;">
                            <img src="{{ asset('images/landing/Social Slang.svg') }}" alt="Social" class="floating-icon-mobile social-icon-mobile" style="position: absolute; width: 45px; height: auto; top: 15%; right: 10%; animation: float 4s ease-in-out infinite 0.5s; z-index: 10;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Image positioned absolutely relative to section - Desktop only -->
        <div class="d-none d-lg-block hero-image-desktop">
            <div class="hero-person-animation" style="position: relative; width: 100%; line-height: 0;">
                <img src="{{ asset('images/landing/Property 1=Default.svg') }}" alt="Person 1" class="person-image person-1" style="position: absolute; bottom: 0; left: 0; width: 100%; height: auto; display: block; opacity: 1; transition: opacity 1s ease-in-out;">
                <img src="{{ asset('images/landing/Property 1=Variant2.svg') }}" alt="Person 2" class="person-image person-2" style="position: absolute; bottom: 0; left: 0; width: 100%; height: auto; display: block; opacity: 0; transition: opacity 1s ease-in-out;">
                <img src="{{ asset('images/landing/Confetti Papers.svg') }}" alt="Confetti" class="floating-icon confetti-icon" style="position: absolute; width: 80px; height: auto; bottom: 75%; left: 0; animation: float 3s ease-in-out infinite; z-index: 10; pointer-events: auto;">
                <img src="{{ asset('images/landing/Social Slang.svg') }}" alt="Social" class="floating-icon social-icon" style="position: absolute; width: 70px; height: auto; bottom: 60%; right: 5%; animation: float 4s ease-in-out infinite 0.5s; z-index: 10; pointer-events: auto;">
            </div>
        </div>
    </section>

    <!-- 1. Compteurs animés (Social Proof Stats) -->
    <section id="stats" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 50px 0;">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['messages_count'] }}" style="font-size: 42px; font-weight: 700; color: #1B7B6A; display: block;">0</span>
                        <span class="stat-plus" style="font-size: 28px; font-weight: 700; color: #1B7B6A;">+</span>
                        <p style="color: #fff; font-size: 14px; margin-top: 8px; opacity: 0.9;">Messages envoyés</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['satisfaction_rate'] }}" style="font-size: 42px; font-weight: 700; color: #1B7B6A; display: block;">0</span>
                        <span class="stat-plus" style="font-size: 28px; font-weight: 700; color: #1B7B6A;">%</span>
                        <p style="color: #fff; font-size: 14px; margin-top: 8px; opacity: 0.9;">Satisfaction client</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['categories_count'] }}" style="font-size: 42px; font-weight: 700; color: #1B7B6A; display: block;">0</span>
                        <span class="stat-plus" style="font-size: 28px; font-weight: 700; color: #1B7B6A;">+</span>
                        <p style="color: #fff; font-size: 14px; margin-top: 8px; opacity: 0.9;">Catégories disponibles</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['avis_count'] }}" style="font-size: 42px; font-weight: 700; color: #1B7B6A; display: block;">0</span>
                        <span class="stat-plus" style="font-size: 28px; font-weight: 700; color: #1B7B6A;">+</span>
                        <p style="color: #fff; font-size: 14px; margin-top: 8px; opacity: 0.9;">Avis clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Bandeau de confiance -->
    <section id="trust-badges" style="background: #fff; padding: 25px 0; border-bottom: 1px solid #eee;">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5">
                <div class="trust-badge d-flex align-items-center gap-2" style="opacity: 0.7;">
                    <i class="bi bi-gift-fill" style="font-size: 24px; color: #1B7B6A;"></i>
                    <span style="font-size: 13px; color: #555;">100% Gratuit</span>
                </div>
                <div class="trust-badge d-flex align-items-center gap-2" style="opacity: 0.7;">
                    <i class="bi bi-lightning-charge-fill" style="font-size: 24px; color: #1B7B6A;"></i>
                    <span style="font-size: 13px; color: #555;">Création en 2 minutes</span>
                </div>
                <div class="trust-badge d-flex align-items-center gap-2" style="opacity: 0.7;">
                    <i class="bi bi-shield-check" style="font-size: 24px; color: #1B7B6A;"></i>
                    <span style="font-size: 13px; color: #555;">Données sécurisées</span>
                </div>
                <div class="trust-badge d-flex align-items-center gap-2" style="opacity: 0.7;">
                    <i class="bi bi-headset" style="font-size: 24px; color: #1B7B6A;"></i>
                    <span style="font-size: 13px; color: #555;">Support 7j/7</span>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="comment" class="comment-section" style="background: #F0F4F8; padding: 100px 0;">
        <div class="container">
            <h2 class="text-center mb-md-5" style="font-size: 48px; font-weight: 700; color: #1A1A1A;">Comment ça marche</h2>

            <div class="row justify-content-center g-4 g-lg-5" style="padding-top: 30px;">
                <!-- Step 1 -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-lg-0">
                    <div class="how-card" style="background: #c9e1e9; border: 2px solid #73b4c6; border-radius: 20px; padding: 30px 25px; text-align: center; height: 180px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="how-icon" style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); width: 50px; height: 50px; background: linear-gradient(135deg, #005792, #2C5F2D); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 1;">
                            <img src="{{ asset('images/svg/Favor.svg') }}" alt="" style="width: 28px; height: 28px;">
                        </div>
                        <h4 class="how-title" style="font-size: 18px; font-weight: 600; margin: 0 0 12px 0; color: #1A1A1A;">Choisis une occasion spéciale</h4>
                        <p class="how-text" style="color: #5A5A5A; font-size: 14px; line-height: 1.5; margin: 0;">
                            Anniversaire, Mariages, Noël, Naissances ou un simple mot d'encouragement
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-lg-0">
                    <div class="how-card" style="background: #c9e1e9; border: 2px solid #73b4c6; border-radius: 20px; padding: 30px 25px; text-align: center; height: 180px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="how-icon" style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); width: 50px; height: 50px; background: linear-gradient(135deg, #005792, #2C5F2D); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 1;">
                            <img src="{{ asset('images/svg/add.svg') }}" alt="" style="width: 28px; height: 28px;">
                        </div>
                        <h4 class="how-title" style="font-size: 18px; font-weight: 600; margin: 0 0 12px 0; color: #1A1A1A;">Personnalise ton message Supriz</h4>
                        <p class="how-text" style="color: #5A5A5A; font-size: 14px; line-height: 1.5; margin: 0;">
                            Exprime toi librement en combinant plusieurs éléments: Texte, image ou vidéo et musique
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-lg-0">
                    <div class="how-card" style="background: #c9e1e9; border: 2px solid #73b4c6; border-radius: 20px; padding: 30px 25px; text-align: center; height: 180px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="how-icon" style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); width: 50px; height: 50px; background: linear-gradient(135deg, #005792, #2C5F2D); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 1;">
                            <img src="{{ asset('images/svg/Artboard.svg') }}" alt="" style="width: 28px; height: 28px;">
                        </div>
                        <h4 class="how-title" style="font-size: 18px; font-weight: 600; margin: 0 0 12px 0; color: #1A1A1A;">Envoie ton message</h4>
                        <p class="how-text" style="color: #5A5A5A; font-size: 14px; line-height: 1.5; margin: 0;">
                            Deux options s'offre à toi: Partage ton lien ou télécharge ton QR code à adresser à ton destinataire
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('nouveau.message') }}" class="btn hero-btn" style="padding: 15px 40px; border-radius: 30px; font-size: 16px; text-decoration: none; display: inline-block;">
                    Commencer
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="apropos" class="section-wrapper" style="background: white; padding: 80px 0;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left side - Circular illustration -->
                <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                    <div class="about-illustration-new" style="position: relative; max-width: 500px; margin: 0 auto;">
                        <img src="{{ asset('images/landing/cercle avec des mage.svg') }}" alt="Supriz" style="width: 100%; height: auto;">
                    </div>
                </div>

                <!-- Right side - Text content -->
                <div class="col-lg-6 col-md-12">
                    <div class="about-text-content" style="padding-left: 30px;">
                        <h2 style="font-size: 48px; font-weight: 700; color: #1A1A1A; margin-bottom: 30px;">À propos</h2>

                        <p style="font-size: 18px; line-height: 1.8; color: #333; margin-bottom: 25px;">
                            <strong>Supriz, c'est plus qu'un message. C'est une émotion offerte</strong>
                        </p>

                        <p style="font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 25px;">
                            Chez Supriz, nous croyons que les mots ont le pouvoir de transformer une journée.
                        </p>

                        <p style="font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 0;">
                            Nous avons créé une plateforme simple, élégante et humaine pour que chacun puisse offrir une pensée, un sourire, une émotion au bon moment.
                            Que ce soit pour un anniversaire, une réussite ou un simple "je pense à toi", Supriz rend chaque vœu personnel et mémorable transmis par un lien unique, au moment parfait.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screen Preview Section -->
    <section class="section-wrapper" style="background: white; padding: 60px 0;">
        <div class="container" style="max-width: 1200px;">
            <div class="screen-preview-wrapper" style="text-align: center;">
                <!-- Desktop Screen - visible on large screens -->
                <img src="{{ asset('images/landing/pc_screen.svg') }}"
                     alt="Aperçu desktop"
                     class="screen-desktop"
                     style="display: none; width: 100%; max-width: 1000px; height: auto; margin: 0 auto;">

                <!-- Mobile Screen - visible on mobile -->
                <img src="{{ asset('images/landing/mobile_screen.svg') }}"
                     alt="Aperçu mobile"
                     class="screen-mobile"
                     style="display: block; width: 100%; max-width: 400px; height: auto; margin: 0 auto;">
            </div>
        </div>
    </section>

    <style>
        /* Show desktop screen on large devices */
        @media (min-width: 768px) {
            .screen-desktop {
                display: block !important;
            }
            .screen-mobile {
                display: none !important;
            }
        }

        /* Show mobile screen on small devices */
        @media (max-width: 767px) {
            .screen-desktop {
                display: none !important;
            }
            .screen-mobile {
                display: block !important;
            }
        }
    </style>

    <!-- 3. Témoignages clients -->
    <section id="testimonials" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 80px 0; overflow: hidden;">
        <div class="container">
            <h2 class="text-center mb-2" style="font-size: 42px; font-weight: 700; color: #1A1A1A;">Ce que disent nos utilisateurs</h2>
            <p class="text-center mb-5" style="color: #666; font-size: 16px;">Des milliers de moments inoubliables créés</p>

            @php
                // Avis par défaut
                $defaultAvis = [
                    ['nom' => 'Aminata M.', 'initiales' => 'AM', 'note' => 5, 'message' => "J'ai créé un message Supriz pour l'anniversaire de ma mère. Elle était tellement émue ! C'est vraiment une façon unique de montrer son amour.", 'gradient' => 'linear-gradient(135deg, #1B7B6A, #2da891)'],
                    ['nom' => 'Jean-Pierre K.', 'initiales' => 'JK', 'note' => 5, 'message' => "Pour notre mariage, nos invités ont envoyé des messages Supriz. On a gardé ce lien précieusement, c'est notre plus beau souvenir !", 'gradient' => 'linear-gradient(135deg, #005792, #0077b6)'],
                    ['nom' => 'Sarah F.', 'initiales' => 'SF', 'note' => 4, 'message' => "Simple, rapide et tellement personnel ! J'utilise Supriz pour toutes les occasions maintenant. Mes proches adorent recevoir ces surprises.", 'gradient' => 'linear-gradient(135deg, #e63946, #f4a261)'],
                ];

                // Couleurs pour les avatars des vrais avis
                $avatarGradients = [
                    'linear-gradient(135deg, #1B7B6A, #2da891)',
                    'linear-gradient(135deg, #005792, #0077b6)',
                    'linear-gradient(135deg, #e63946, #f4a261)',
                    'linear-gradient(135deg, #6c757d, #adb5bd)',
                    'linear-gradient(135deg, #9333ea, #c084fc)',
                    'linear-gradient(135deg, #ea580c, #fb923c)',
                ];

                // Combiner avis réels et par défaut
                $allAvis = collect($defaultAvis);
                if(isset($avisClients) && $avisClients->count() > 0) {
                    $avisClientsArray = $avisClients->map(function($avis, $index) use ($avatarGradients) {
                        return [
                            'nom' => $avis->nom,
                            'initiales' => $avis->initiales ?? strtoupper(substr($avis->nom, 0, 2)),
                            'note' => $avis->note,
                            'message' => $avis->message ?: 'Super expérience avec Supriz !',
                            'gradient' => $avatarGradients[$index % count($avatarGradients)],
                        ];
                    })->toArray();
                    $allAvis = collect(array_merge($avisClientsArray, $defaultAvis));
                }

                $totalAvis = $allAvis->count();
                $useMarquee = $totalAvis > 3;
            @endphp

            @if($useMarquee)
                <!-- Mode Marquee pour plus de 3 avis -->
                <div class="testimonials-marquee-wrapper" style="position: relative; width: 100vw; left: 50%; transform: translateX(-50%); overflow: hidden;">
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 100px; background: linear-gradient(to right, #f8f9fa, transparent); z-index: 2;"></div>
                    <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 100px; background: linear-gradient(to left, #e9ecef, transparent); z-index: 2;"></div>

                    <div class="testimonials-marquee-track" style="display: flex; gap: 25px; animation: testimonialsScroll {{ $totalAvis * 8 }}s linear infinite; width: max-content; padding: 10px 0;">
                        @for($i = 0; $i < 2; $i++)
                            @foreach($allAvis as $avis)
                                <div class="testimonial-card" style="background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); position: relative; min-width: 320px; max-width: 320px; flex-shrink: 0;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 45px; height: 45px; border-radius: 50%; background: {{ $avis['gradient'] }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px;">{{ $avis['initiales'] }}</div>
                                        <div class="ms-3">
                                            <h5 style="margin: 0; font-size: 15px; font-weight: 600;">{{ $avis['nom'] }}</h5>
                                            <div style="color: #ffc107; font-size: 14px;">
                                                @for($j = 1; $j <= 5; $j++)
                                                    @if($j <= $avis['note'])
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p style="color: #555; font-size: 13px; line-height: 1.6; margin: 0; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">"{{ $avis['message'] }}"</p>
                                    <div style="position: absolute; top: 15px; right: 15px; opacity: 0.1;">
                                        <i class="bi bi-quote" style="font-size: 30px; color: #1B7B6A;"></i>
                                    </div>
                                </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @else
                <!-- Mode Grille pour 3 avis ou moins -->
                <div class="row g-4">
                    @foreach($allAvis->take(3) as $avis)
                        <div class="col-md-4">
                            <div class="testimonial-card" style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); height: 100%; position: relative;">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: {{ $avis['gradient'] }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 18px;">{{ $avis['initiales'] }}</div>
                                    <div class="ms-3">
                                        <h5 style="margin: 0; font-size: 16px; font-weight: 600;">{{ $avis['nom'] }}</h5>
                                        <div style="color: #ffc107;">
                                            @for($j = 1; $j <= 5; $j++)
                                                @if($j <= $avis['note'])
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p style="color: #555; font-size: 14px; line-height: 1.7; margin: 0;">"{{ $avis['message'] }}"</p>
                                <div style="position: absolute; top: 20px; right: 20px; opacity: 0.1;">
                                    <i class="bi bi-quote" style="font-size: 40px; color: #1B7B6A;"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Section "Ils nous font confiance" -->
    <section id="social-proof" style="background: #1a1a2e; padding: 60px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 style="color: white; font-size: 32px; font-weight: 700; margin-bottom: 15px;">Rejoins notre communauté</h3>
                    <p style="color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 25px;">Des milliers de personnes ont déjà choisi Supriz pour marquer leurs moments spéciaux.</p>

                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 40px; height: 40px; background: rgba(27, 123, 106, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-people-fill" style="color: #1B7B6A; font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="color: white; font-weight: 600; font-size: 18px;">+1500</span>
                                <p style="color: rgba(255,255,255,0.6); font-size: 12px; margin: 0;">Utilisateurs actifs</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 40px; height: 40px; background: rgba(27, 123, 106, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-globe-americas" style="color: #1B7B6A; font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="color: white; font-weight: 600; font-size: 18px;">+50</span>
                                <p style="color: rgba(255,255,255,0.6); font-size: 12px; margin: 0;">Pays</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-end">
                        <!-- Avatars superposés -->
                        <div class="avatars-stack d-flex align-items-center">
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #1B7B6A, #2da891); border: 3px solid #1a1a2e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; margin-right: -15px; z-index: 5;">AM</div>
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #005792, #0077b6); border: 3px solid #1a1a2e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; margin-right: -15px; z-index: 4;">JK</div>
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #e63946, #f4a261); border: 3px solid #1a1a2e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; margin-right: -15px; z-index: 3;">SF</div>
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #6c757d, #adb5bd); border: 3px solid #1a1a2e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; margin-right: -15px; z-index: 2;">MK</div>
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: #1B7B6A; border: 3px solid #1a1a2e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 500; font-size: 12px; z-index: 1;">+1.5k</div>
                            <span style="color: rgba(255,255,255,0.8); margin-left: 15px; font-size: 14px;">nous ont rejoint ce mois</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="section-wrapper" style="background: white; padding: 100px 0;">
        <div class="container" style="max-width: 900px;">
            <h2 class="text-center" style="font-size: 48px; font-weight: 700; color: #1A1A1A; margin-bottom: 60px;">FAQ</h2>

            <div class="faq-container-new">
                @if(isset($faqs) && count($faqs) > 0)
                    @foreach($faqs as $index => $faq)
                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">{{ $faq->asq }}</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">{{ $faq->response }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- FAQ par défaut -->
                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">Qu'est-ce que Supriz ?</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">Supriz est une plateforme qui permet de créer et envoyer des messages supriz personnalisés pour toutes les occasions spéciales.</p>
                        </div>
                    </div>

                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">Est-ce que Supriz est gratuit ?</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">Oui ! Supriz est entièrement gratuit. Tu peux créer et partager autant de messages supriz que tu le souhaites sans aucun frais.</p>
                        </div>
                    </div>

                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">Mes souvenirs sont-ils privés ?</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">Absolument ! Vos messages et médias sont totalement privés et sécurisés. Seules les personnes avec le lien peuvent y accéder.</p>
                        </div>
                    </div>

                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">Où sont stockés mes fichiers ?</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">Tous vos fichiers sont stockés de manière sécurisée sur nos serveurs avec un cryptage de bout en bout.</p>
                        </div>
                    </div>

                    <div class="faq-item-new" style="border-bottom: 1px solid #E5E5E5; padding: 25px 0;">
                        <div class="faq-question-new" onclick="toggleFaq(this)" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;">
                            <h3 style="font-size: 18px; font-weight: 400; color: #005792; margin: 0;">Que contient la version Premium ?</h3>
                            <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="transition: transform 0.3s ease; flex-shrink: 0; margin-left: 20px;">
                                <path d="M6 9L12 15L18 9" stroke="#005792" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="faq-answer-new" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 15px 0 0 0; padding-right: 40px;">La version Premium offre des fonctionnalités avancées comme des templates exclusifs, plus d'espace de stockage, et des options de personnalisation supplémentaires.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" style="background: white; padding: 100px 0;">
        <div class="container">
            <h2 class="text-center" style="font-size: 48px; font-weight: 700; color: #1A1A1A; margin-bottom: 20px;">Contactez-nous</h2>
            <p class="text-center" style="font-size: 16px; color: #666; margin-bottom: 60px;">Notre équipe d'experts est à votre disposition pour répondre à toutes vos préoccupations</p>

            <div class="row align-items-start" style="gap: 40px 0;">
                <!-- Formulaire de contact -->
                <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                    <div style="background: #E8E8E8; padding: 40px; border-radius: 15px;">
                        @if(session('success'))
                            <div class="alert alert-success" style="border-radius: 8px; margin-bottom: 20px; padding: 15px; background-color: #d1fae5; border: 1px solid #059669; color: #065f46;">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger" style="border-radius: 8px; margin-bottom: 20px; padding: 15px; background-color: #fee2e2; border: 1px solid #dc2626; color: #991b1b;">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nom" required value="{{ old('name') }}" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; font-size: 15px;">
                                @error('name')
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Sujet" required value="{{ old('subject') }}" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; font-size: 15px;">
                                @error('subject')
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required value="{{ old('email') }}" style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; font-size: 15px;">
                                @error('email')
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Message" rows="5" required style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; font-size: 15px; resize: none;">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn w-100" style="background: #005792; color: white; border: none; padding: 15px; border-radius: 8px; font-weight: 500; font-size: 16px; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease;">
                                Envoyez votre requête
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="transform: rotate(-45deg); flex-shrink: 0;">
                                    <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="col-lg-6 col-md-12">
                    <div style="border: 2px dashed #005792; border-radius: 15px; padding: 40px; background: #F8FBFD; min-height: 100%;">
                        <h3 style="font-size: 24px; font-weight: 600; color: #1A1A1A; margin-bottom: 30px;">Nos coordonnées</h3>

                        <div class="mb-4">
                            <p style="font-weight: 600; color: #333; margin-bottom: 5px; font-size: 15px;">Email</p>
                            <p style="color: #666; margin: 0; font-size: 15px;">contact@suprizs.com</p>
                        </div>

                        <div class="mb-4">
                            <p style="font-weight: 600; color: #333; margin-bottom: 5px; font-size: 15px;">Téléphone</p>
                            <p style="color: #666; margin: 0; font-size: 15px;">+229 01 61 55 66 80</p>
                        </div>

                        <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #ddd;">
                            <p style="color: #666; margin: 0; font-size: 14px; line-height: 1.6;">Une question ? Un besoin d'assistance ? Une suggestion ?<br>Notre équipe est à votre écoute.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #E8EDF2; padding: 60px 0 20px;">
        <div class="container">
            <div class="row">
                <!-- Logo et description -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Supriz" style="height: 40px;">
                    </div>
                    <p style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 20px; max-width: 300px;">
                        Pour anniversaires, mariages, fêtes et toutes les occasions. Exprimez vos vœux, sentiments et intentions d'une manière beaucoup plus originale grâce à un lien unique et mémorable.
                    </p>
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <a href="https://www.facebook.com/share/1MFNkDtNgi" target="_blank" rel="noopener noreferrer" aria-label="Facebook" style="width: 35px; height: 35px; background: linear-gradient(135deg, #005792, #2C5F2D); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                            <i class="bi bi-facebook" style="color: white; font-size: 18px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Informations sur l'entreprise -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h4 style="color: #1A1A1A; font-size: 18px; font-weight: 600; margin-bottom: 20px;">Informations sur l'entreprise</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 12px;">
                            <a href="#accueil" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">Accueil</a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#comment" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">Comment ça marche</a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#apropos" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">À propos</a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="#faq" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">FAQ</a>
                        </li>
                    </ul>
                </div>

                <!-- Mentions légales -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h4 style="color: #1A1A1A; font-size: 18px; font-weight: 600; margin-bottom: 20px;">Mentions légales</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 12px;">
                            <a href="{{ route('politique.confidentialite') }}" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">Politique de confidentialité</a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="{{ route('conditions.utilisation') }}" style="color: #666; font-size: 14px; text-decoration: none; transition: color 0.3s ease;">Conditions d'utilisation</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #D1D5DB; text-align: center;">
                <p style="color: #666; font-size: 14px; margin: 0;">
                    © 2025 Supriz. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // FAQ Toggle with smooth animation
        function toggleFaq(element) {
            const faqItem = element.parentElement;
            const answer = faqItem.querySelector('.faq-answer-new');
            const icon = element.querySelector('.faq-icon');
            const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

            // Close all other FAQ items
            document.querySelectorAll('.faq-item-new').forEach(item => {
                if (item !== faqItem) {
                    const otherAnswer = item.querySelector('.faq-answer-new');
                    const otherIcon = item.querySelector('.faq-icon');
                    otherAnswer.style.maxHeight = '0';
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            });

            // Toggle current FAQ item
            if (isOpen) {
                answer.style.maxHeight = '0';
                icon.style.transform = 'rotate(0deg)';
            } else {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Advanced scroll animations
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                        entry.target.style.opacity = '1';

                        // Different animations based on element type
                        if (entry.target.classList.contains('category-card')) {
                            entry.target.style.transform = 'translateY(0) scale(1)';
                        } else if (entry.target.classList.contains('step-card')) {
                            entry.target.style.transform = 'translateY(0) rotateX(0)';
                        } else {
                            entry.target.style.transform = 'translateY(0)';
                        }
                    }, index * 50); // Stagger animation
                }
            });
        }, observerOptions);

        // Observe elements with initial styles
        document.addEventListener('DOMContentLoaded', function() {
            // Categories animation
            document.querySelectorAll('.category-card').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px) scale(0.9)';
                el.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                el.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(el);
            });

            // Steps animation
            document.querySelectorAll('.step-card').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px) rotateX(-10deg)';
                el.style.transformOrigin = 'center bottom';
                el.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                el.style.transitionDelay = `${index * 0.15}s`;
                observer.observe(el);
            });

            // FAQ animation
            document.querySelectorAll('.faq-item').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.4s ease';
                el.style.transitionDelay = `${index * 0.05}s`;
                observer.observe(el);
            });

            // Hero content animation
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.animation = 'slideInLeft 1s ease forwards';
            }

            const heroImage = document.querySelector('.hero-image');
            if (heroImage) {
                heroImage.style.animation = 'slideInRight 1s ease 0.3s forwards';
                heroImage.style.opacity = '0';
                setTimeout(() => {
                    heroImage.style.opacity = '1';
                }, 300);
            }
        });

        // Parallax effect for floating elements
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.float-element');

            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        });

        // Mouse move effect for hero section
        document.querySelector('.hero-section')?.addEventListener('mousemove', function(e) {
            const floatingElements = this.querySelectorAll('.float-element');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            floatingElements.forEach((element, index) => {
                const speed = (index + 1) * 10;
                const xPos = (x - 0.5) * speed;
                const yPos = (y - 0.5) * speed;
                element.style.transform = `translate(${xPos}px, ${yPos}px)`;
            });
        });

        // Carousel functionality
        let currentSlide = 0;
        const track = document.getElementById('categoriesTrack');
        const cards = document.querySelectorAll('.carousel-track .category-card');
        const totalCards = cards.length / 2; // Because we duplicate for infinite scroll
        const cardWidth = 240; // 220px width + 20px gap
        let autoScrollInterval;

        function updateCarousel() {
            if (track) {
                const offset = currentSlide * cardWidth;
                track.style.transform = `translateX(-${offset}px)`;
                updateDots();
            }
        }

        function moveCarousel(direction) {
            currentSlide += direction;

            // Handle infinite scroll
            if (currentSlide < 0) {
                currentSlide = totalCards - 1;
            } else if (currentSlide >= totalCards) {
                currentSlide = 0;
            }

            updateCarousel();
            resetAutoScroll();
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateCarousel();
            resetAutoScroll();
        }

        function updateDots() {
            const dots = document.querySelectorAll('.carousel-dots .dot');
            dots.forEach((dot, index) => {
                if (index === currentSlide % 5) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function startAutoScroll() {
            autoScrollInterval = setInterval(() => {
                moveCarousel(1);
            }, 4000); // Change slide every 4 seconds
        }

        function resetAutoScroll() {
            clearInterval(autoScrollInterval);
            startAutoScroll();
        }

        // Touch support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        const carousel = document.querySelector('.categories-carousel');
        if (carousel) {
            carousel.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            carousel.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                moveCarousel(1); // Swipe left
            }
            if (touchEndX > touchStartX + 50) {
                moveCarousel(-1); // Swipe right
            }
        }

        // Start auto-scroll on page load
        document.addEventListener('DOMContentLoaded', function() {
            startAutoScroll();

            // Pause on hover
            const carouselWrapper = document.querySelector('.categories-carousel-wrapper');
            if (carouselWrapper) {
                carouselWrapper.addEventListener('mouseenter', () => {
                    clearInterval(autoScrollInterval);
                });

                carouselWrapper.addEventListener('mouseleave', () => {
                    startAutoScroll();
                });
            }
        });

        // Add loading class removal - Version simplifiée et robuste
        function hideLoadingScreen() {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen && !loadingScreen.classList.contains('hidden')) {
                console.log('Hiding loading screen...');
                loadingScreen.classList.add('hidden');
                document.body.classList.add('loaded');
                // Supprimer complètement après l'animation
                setTimeout(() => {
                    if (loadingScreen && loadingScreen.parentNode) {
                        loadingScreen.parentNode.removeChild(loadingScreen);
                        console.log('Loading screen removed');
                    }
                }, 600);
            }
        }

        // Timeout de sécurité immédiat - masquer après 1.5 secondes max
        setTimeout(hideLoadingScreen, 1500);

        // Masquer au chargement DOM
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(hideLoadingScreen, 300);
        });

        // Masquer au chargement complet
        window.addEventListener('load', function() {
            hideLoadingScreen();
        });

        // Hero person animation - alternate between two images
        function animateHeroPerson() {
            const person1Desktop = document.querySelector('.person-1');
            const person2Desktop = document.querySelector('.person-2');
            const person1Mobile = document.querySelector('.person-1-mobile');
            const person2Mobile = document.querySelector('.person-2-mobile');

            let showFirst = true;

            setInterval(() => {
                // Desktop animation
                if (person1Desktop && person2Desktop) {
                    if (showFirst) {
                        person1Desktop.style.opacity = '0';
                        person2Desktop.style.opacity = '1';
                    } else {
                        person1Desktop.style.opacity = '1';
                        person2Desktop.style.opacity = '0';
                    }
                }

                // Mobile animation
                if (person1Mobile && person2Mobile) {
                    if (showFirst) {
                        person1Mobile.style.display = 'none';
                        person2Mobile.style.display = 'block';
                    } else {
                        person1Mobile.style.display = 'block';
                        person2Mobile.style.display = 'none';
                    }
                }

                showFirst = !showFirst;
            }, 3000); // Change every 3 seconds
        }

        // Start animation when page loads
        document.addEventListener('DOMContentLoaded', animateHeroPerson);

        // Active navigation link detection
        const sections = document.querySelectorAll('section[id], div[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        function setActiveLink() {
            let current = '';
            const scrollPosition = window.scrollY + 100;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    current = sectionId;
                }
            });

            // Si on est tout en haut, activer "accueil"
            if (window.scrollY < 100) {
                current = 'accueil';
            }

            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === '#' + current) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', setActiveLink);
        window.addEventListener('load', setActiveLink);

        // ========== ANIMATIONS AU SCROLL ==========
        const sectionObserverOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, sectionObserverOptions);

        // Observer les sections
        document.addEventListener('DOMContentLoaded', () => {
            // Ajouter la classe animate-on-scroll aux sections
            const sections = document.querySelectorAll('#categories, #comment, #apropos, #faq, #contact');
            sections.forEach(section => {
                section.classList.add('animate-on-scroll');
                sectionObserver.observe(section);
            });

            // Ajouter les animations aux cartes d'événements avec délai
            const cards = document.querySelectorAll('.occasion-card-new');
            cards.forEach((card, index) => {
                card.classList.add('animate-on-scroll');
                if (index < 5) {
                    card.classList.add(`delay-${index + 1}`);
                }
                sectionObserver.observe(card);
            });

            // Ajouter les animations aux étapes "Comment ça marche"
            const steps = document.querySelectorAll('.how-card');
            steps.forEach((step, index) => {
                step.classList.add('animate-on-scroll');
                step.classList.add(`delay-${(index % 3) + 1}`);
                sectionObserver.observe(step);
            });

            // Ajouter les animations aux FAQ
            const faqItems = document.querySelectorAll('.faq-item-new');
            faqItems.forEach((item, index) => {
                item.classList.add('animate-on-scroll');
                if (index < 5) {
                    item.classList.add(`delay-${(index % 3) + 1}`);
                }
                sectionObserver.observe(item);
            });
        });

        // ========== PARALLAX LÉGER SUR LE HERO ==========
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const scrolled = window.pageYOffset;
                    const heroImage = document.querySelector('.hero-person-animation');
                    if (heroImage && scrolled < window.innerHeight) {
                        heroImage.style.transform = `translateY(${scrolled * 0.3}px)`;
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });

        // ========== EFFET RIPPLE SUR LES BOUTONS ==========
        document.querySelectorAll('.hero-btn, .btn').forEach(button => {
            button.classList.add('ripple-effect');
        });

        // ========== ANIMATION COMPTEURS ==========
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const increment = target / speed;
                let current = 0;

                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current).toLocaleString();
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };

                updateCount();
            });
        }

        // Observer pour déclencher l'animation quand visible
        const statsSection = document.getElementById('stats');
        if (statsSection) {
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            statsObserver.observe(statsSection);
        }

        // ========== NOTIFICATIONS FOMO ==========
        const fomoNotifications = [
            { name: 'Marie', location: 'Paris', action: 'vient de créer un message anniversaire', time: 'Il y a 2 min' },
            { name: 'Kouadio', location: 'Abidjan', action: 'a envoyé un message de mariage', time: 'Il y a 5 min' },
            { name: 'Fatou', location: 'Dakar', action: 'vient de créer un message Supriz', time: 'Il y a 8 min' },
            { name: 'Jean', location: 'Douala', action: 'a partagé un message de voeux', time: 'Il y a 12 min' },
            { name: 'Awa', location: 'Bamako', action: 'vient d\'envoyer un message', time: 'Il y a 15 min' },
            { name: 'Patrick', location: 'Kinshasa', action: 'a créé un message de naissance', time: 'Il y a 18 min' },
        ];

        let fomoIndex = 0;
        const fomoPopup = document.getElementById('fomo-popup');

        function showFomoNotification() {
            if (!fomoPopup) return;

            const notification = fomoNotifications[fomoIndex];
            document.getElementById('fomo-name').textContent = notification.name;
            document.getElementById('fomo-location').textContent = notification.location;
            document.getElementById('fomo-action').textContent = notification.action;
            document.getElementById('fomo-time').textContent = notification.time;

            fomoPopup.classList.add('show');

            setTimeout(() => {
                fomoPopup.classList.remove('show');
            }, 4000);

            fomoIndex = (fomoIndex + 1) % fomoNotifications.length;
        }

        // Afficher la première notification après 5 secondes, puis toutes les 15 secondes
        setTimeout(() => {
            showFomoNotification();
            setInterval(showFomoNotification, 15000);
        }, 5000);

        // Fermer la notification au clic
        document.getElementById('fomo-close')?.addEventListener('click', () => {
            fomoPopup.classList.remove('show');
        });
    </script>

    <!-- 5. Popup FOMO Notification -->
    <div id="fomo-popup" class="fomo-notification" style="position: fixed; bottom: 20px; left: 20px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); padding: 15px 20px; z-index: 9999; transform: translateX(-120%); transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); max-width: 320px;">
        <button id="fomo-close" style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 18px; color: #999; cursor: pointer; line-height: 1;">&times;</button>
        <div class="d-flex align-items-start gap-3">
            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #1B7B6A, #2da891); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-gift" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <p style="margin: 0; font-size: 14px; color: #333; line-height: 1.4;">
                    <strong id="fomo-name">Marie</strong> de <span id="fomo-location">Paris</span> <span id="fomo-action">vient de créer un message</span>
                </p>
                <span id="fomo-time" style="font-size: 12px; color: #999;">Il y a 2 min</span>
            </div>
        </div>
    </div>

    <style>
        .fomo-notification.show {
            transform: translateX(0) !important;
        }

        @media (max-width: 768px) {
            .fomo-notification {
                bottom: 10px !important;
                left: 10px !important;
                right: 10px !important;
                max-width: none !important;
            }
        }

        /* Hover effect pour les testimonials */
        .testimonial-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.12) !important;
        }

        /* Animation pour les stats */
        .stat-item {
            transition: transform 0.3s ease;
        }
        .stat-item:hover {
            transform: scale(1.05);
        }

        /* Trust badges hover */
        .trust-badge {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .trust-badge:hover {
            opacity: 1 !important;
            transform: translateY(-2px);
        }
    </style>
</body>
</html>

