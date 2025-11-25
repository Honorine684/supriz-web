<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Nouveau Message Supriz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link href="{{ asset('css/nouveau-message-responsive.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/nouveau-message-fix.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/mobile-fix-final.css') }}?v={{ time() }}" rel="stylesheet">
    <style>
        /* ===================================
           RESET & BASE STYLES
        =================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #006BA6 0%, #005792 50%, #004570 100%);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        img, video {
            max-width: 100%;
            height: auto;
        }

        .decorative-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.1;
        }

        .decorative-icon {
            position: absolute;
            width: 180px;
            height: 180px;
            opacity: 0.15;
        }

        .decorative-icon.wallet {
            bottom: 15%;
            left: 8%;
        }

        .decorative-icon.cake {
            top: 20%;
            right: 12%;
        }

        .main-container {
            position: relative;
            z-index: 1;
        }


        /* ===================================
           MAIN CONTAINER
        =================================== */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 40px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            flex: 1;
            width: 100%;
        }

        /* Form Card */
        .form-card {
            background: rgba(69, 129, 167, 0.35);
            border-radius: 20px;
            padding: 0;
            overflow: visible;
            display: flex;
            flex-direction: column;
            max-height: 100%;
            width: 100%;
            box-sizing: border-box;
        }


        .form-header {
            background: white;
            padding: 20px 24px;
            border-radius: 20px 20px 0 0;
            flex-shrink: 0;
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: #005792;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .form-subtitle {
            font-size: 13px;
            color: #374151;
            font-weight: 400;
        }

        .form-body {
            padding: 20px 24px;
            overflow-y: auto;
            flex: 1;
        }

        /* Categories Wrapper */
        .categories-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        /* Categories Grid */
        .categories-grid {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            padding-bottom: 4px;
        }

        .categories-grid::-webkit-scrollbar {
            display: none; /* Chrome, Safari */
        }

        .category-item {
            background: rgba(96, 135, 163, 0.4);
            border-radius: 10px;
            padding: 10px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            width: 120px;
            min-height: 90px;
            max-height: 90px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .category-item:hover {
            background: rgba(96, 135, 163, 0.6);
        }

        .category-item.active {
            background: rgba(209, 193, 168, 1);
            border-color: rgba(209, 193, 168, 1);
        }

        .category-icon-wrapper {
            width: 40px;
            height: 40px;
            margin: 0 auto 6px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .category-icon {
            width: 32px;
            height: 32px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .category-item.active .category-icon {
            filter: brightness(0) saturate(100%) invert(29%) sepia(75%) saturate(1078%) hue-rotate(168deg) brightness(95%) contrast(101%);
        }

        .category-label {
            font-size: 11px;
            font-weight: 600;
            color: white;
            line-height: 1.2;
            margin: 0;
        }

        .category-item.active .category-label {
            color: #005792;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .scroll-indicator.visible {
            opacity: 1;
        }

        .scroll-indicator span {
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transition: all 0.3s;
        }

        .scroll-indicator span:nth-child(2) {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.4;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.8;
            }
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-bottom: 6px;
        }

        .char-counter {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            text-align: right;
            margin-top: 4px;
        }

        .char-counter.warning {
            color: #fbbf24;
        }

        .char-counter.danger {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(96, 149, 186, 0.3);
            border: 1px solid rgba(96, 149, 186, 0.5);
            border-radius: 10px;
            font-size: 14px;
            color: white;
            transition: all 0.3s;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            outline: none;
            background: rgba(96, 149, 186, 0.4);
            border-color: rgba(96, 149, 186, 0.7);
        }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        /* Upload Zone */
        .upload-zone {
            border: 2px dotted rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
        }

        .upload-zone:hover {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.03);
        }

        .upload-icon {
            width: 32px;
            height: 32px;
            margin: 0 auto 8px;
            opacity: 0.5;
        }

        .upload-text {
            font-size: 13px;
            color: white;
            font-weight: 400;
            margin-bottom: 3px;
        }

        .upload-subtext {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
        }

        .media-preview {
            display: none;
            text-align: center;
            width: 100%;
        }

        .media-preview.active {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .media-preview img,
        .media-preview video {
            width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .btn-remove-media {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-remove-media:hover {
            background: rgba(239, 68, 68, 1);
        }

        .upload-zone.has-media {
            border-style: solid;
            border-color: rgba(229, 201, 161, 0.5);
            background: rgba(255, 255, 255, 0.05);
        }

        .music-section.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Date Picker */
        .date-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0;
        }

        .date-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(189, 206, 218, 0.6);
            padding: 12px 20px;
            border-radius: 20px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 500;
        }

        .date-badge input {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            width: auto;
        }

        .date-badge input:focus {
            outline: none;
        }

        .clock-icon {
            width: 18px;
            height: 18px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Select */
        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='white' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            padding-right: 45px;
        }

        select.form-control option {
            background: #005792;
            color: white;
        }

        /* Music Section */
        .music-section {
            margin-bottom: 14px;
        }

        .music-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 16px 18px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .music-header:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .music-header-left h3 {
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-bottom: 4px;
        }

        .music-header-left p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .music-toggle-icon {
            width: 20px;
            height: 20px;
            color: white;
            transition: transform 0.3s;
        }

        .music-toggle-icon.open {
            transform: rotate(180deg);
        }

        .music-list {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-top: 0;
        }

        .music-list.open {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 12px;
        }


        .music-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            background: rgba(47, 99, 130, 0.6);
            border-radius: 10px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .music-item:hover {
            background: rgba(47, 99, 130, 0.8);
        }

        .music-item.selected {
            background: rgba(209, 193, 168, 0.3);
            border-color: rgba(209, 193, 168, 1);
        }

        .music-icon-wrapper {
            width: 44px;
            height: 44px;
            background: #004570;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .music-icon {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1);
        }

        .music-info {
            flex: 1;
        }

        .music-title {
            font-size: 13px;
            font-weight: 600;
            color: rgba(229, 201, 161, 1);
            margin-bottom: 2px;
        }

        .music-duration {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .music-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-play-music {
            width: 36px;
            height: 36px;
            background: rgba(229, 201, 161, 1);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.3s ease;
            opacity: 0;
            transform: scale(0.5);
        }

        .music-item.selected .btn-play-music {
            display: flex;
            animation: bounceIn 0.4s ease forwards;
        }

        .btn-play-music:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .play-icon {
            width: 16px;
            height: 16px;
            color: #004570;
            margin-left: 2px;
        }

        .btn-delete-music {
            width: 36px;
            height: 36px;
            background: rgba(239, 68, 68, 0.9);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.3s ease;
            opacity: 0;
            transform: scale(0.5);
        }

        .btn-delete-music:hover {
            background: rgba(239, 68, 68, 1);
            transform: scale(1.1);
        }

        .music-item.selected .btn-delete-music {
            display: flex;
            animation: bounceIn 0.4s ease 0.1s forwards;
        }


        .delete-icon {
            width: 18px;
            height: 18px;
            color: white;
        }

        .music-arrow-wrapper {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 1;
            transform: scale(1);
        }

        .music-item.selected .music-arrow-wrapper {
            opacity: 0;
            transform: scale(0.5);
            pointer-events: none;
        }

        .music-arrow {
            width: 18px;
            height: 18px;
            color: #004570;
            flex-shrink: 0;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #00426B;
            color: white;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-submit:hover {
            background-color: #003555;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Preview Card */
        .preview-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            max-height: 100%;
            overflow: hidden;
        }

        .preview-title {
            font-size: 20px;
            font-weight: 700;
            color: #005792;
            margin-bottom: 16px;
            flex-shrink: 0;
        }

        .preview-content {
            background: #f3f4f6;
            border-radius: 14px;
            padding: 20px;
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            overflow-y: auto;
            position: relative;
            transition: background 0.3s ease;
        }

        .preview-content.has-content {
            background-image: url('/images/clientIamges/Container.png');
            background-position: center center;
            background-size: cover;
        }

        .preview-content.has-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 14px;
            pointer-events: none;
        }

        .preview-content > * {
            position: relative;
            z-index: 1;
        }

        .gift-animation {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .gift-animation img {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }

        .gift-animation img.animate {
            animation: bounce 1s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .music-badge {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 10;
            white-space: nowrap;
        }

        .music-badge-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
            filter: brightness(0) saturate(100%) invert(29%) sepia(75%) saturate(1078%) hue-rotate(168deg) brightness(95%) contrast(101%);
        }

        .music-badge-text {
            font-size: 13px;
            font-weight: 600;
            color: #005792;
            line-height: 20px;
            display: inline-block;
            vertical-align: middle;
        }

        .btn-generate-preview {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            width: calc(100% - 40px);
            background: #00426B;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 10;
            transition: all 0.3s;
        }

        .btn-generate-preview:hover {
            background: #003555;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #9ca3af;
            width: 100%;
            height: 100%;
            min-height: 300px;
        }

        .preview-empty-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 16px;
            opacity: 0.25;
            display: block;
        }

        .preview-empty p {
            font-size: 13px;
            line-height: 1.6;
            margin: 0 0 2px 0;
            text-align: center;
        }

        /* Share Link Styles */
        .share-link-wrapper {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .share-link-input {
            flex: 1 !important;
            min-width: 0 !important;
            border: none !important;
            background: #ffffff !important;
            font-size: 14px !important;
            color: #1a1a1a !important;
            outline: none !important;
            padding: 8px 12px !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
            -webkit-text-fill-color: #1a1a1a !important;
            opacity: 1 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .copy-link-btn {
            background: #005792 !important;
            color: white !important;
            padding: 10px 14px !important;
            border: none !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            flex-shrink: 0 !important;
            min-width: 44px !important;
            min-height: 44px !important;
        }

        .copy-link-btn:active {
            background: #003555 !important;
        }

        /* Mobile Preview Button */
        .btn-preview-mobile {
            display: none;
            width: 100%;
            padding: 12px;
            background-color: rgba(229, 201, 161, 1);
            color: #005792;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 0px;
            margin-left: 0px;
            margin-right: 0px;
            transition: all 0.3s;
        }

        .btn-preview-mobile:hover {
            opacity: 0.9;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #005792;
        }

        .btn-close-modal {
            background: none;
            border: none;
            font-size: 28px;
            color: #6b7280;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .main-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .preview-card {
                min-height: 400px;
            }
        }

        @media (max-width: 768px) {
            /* Mobile optimizations - CRITICAL */
            * {
                -webkit-tap-highlight-color: transparent;
                box-sizing: border-box !important;
            }

            html {
                overflow-x: hidden !important;
                overflow-y: scroll !important;
                -webkit-overflow-scrolling: touch;
                max-width: 100vw !important;
                width: 100% !important;
            }

            body {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                overflow-x: hidden !important;
                overflow-y: visible !important;
                height: auto !important;
                min-height: 100vh;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* FORCE: No element can overflow */
            *:not(html):not(body) {
                max-width: 100% !important;
            }

            div, section, main, form {
                max-width: 100% !important;
            }

            /* Navigation */
            .navbar {
                padding: 5px 0 !important;
                background: rgba(255, 255, 255, 0.15) !important;
                backdrop-filter: blur(10px);
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
            }

            .navbar-toggler {
                width: auto !important;
                max-width: none !important;
                min-width: auto !important;
                outline: none !important;
                box-shadow: none !important;
                border: none !important;
            }

            .navbar-toggler:focus {
                outline: none !important;
                box-shadow: none !important;
                border: none !important;
            }

            .main-container {
                padding-top: 110px !important;
                margin-top: 0 !important;
            }

            body {
                padding-top: 0 !important;
            }

            .navbar-collapse {
                background: rgba(0, 163, 206, 0.15);
                backdrop-filter: blur(10px);
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
                color: white !important;
            }

            .navbar-nav .nav-link:hover {
                background: rgba(0, 163, 206, 0.2);
                color: white !important;
            }

            .navbar-collapse .d-flex {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
                gap: 10px !important;
            }

            .navbar-collapse .d-flex .btn {
                width: 100%;
                margin: 0 !important;
                padding: 12px 25px !important;
            }

            .logo img {
                max-width: 100%;
                height: auto;
                max-height: 38px;
            }

            /* Main Container - Remove fixed height and overflow */
            .main-container {
                padding: 110px 15px 20px !important;
                overflow: visible !important;
                gap: 0;
                height: auto !important;
                min-height: auto !important;
                flex: none;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* Form Card - Full height, no scroll inside */
            .form-card {
                max-height: none !important;
                height: auto !important;
                border-radius: 16px;
                overflow: visible !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .form-header {
                padding: 20px 15px 18px;
                border-radius: 16px 16px 0 0;
                max-width: 100%;
            }

            .form-title {
                font-size: 18px;
                line-height: 1.4;
                max-width: 100%;
                word-wrap: break-word;
            }

            .form-subtitle {
                font-size: 13px;
                max-width: 100%;
                word-wrap: break-word;
            }

            .form-body {
                overflow-y: visible !important;
                overflow-x: hidden !important;
                padding: 18px 15px 20px;
                height: auto !important;
                flex: none;
                max-width: 100% !important;
                width: 100% !important;
            }

            form {
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Categories */
            .categories-wrapper {
                margin-bottom: 18px;
                max-width: 100% !important;
                overflow-x: auto;
                overflow-y: hidden;
            }

            .categories-grid {
                gap: 10px;
                padding-bottom: 8px;
                max-width: none;
            }

            .category-item {
                width: 80px !important;
                height: 80px !important;
                min-height: 80px !important;
                max-height: 80px !important;
                padding: 8px 5px;
                border-radius: 10px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .category-icon-wrapper {
                width: 42px;
                height: 42px;
                margin-bottom: 4px;
            }

            .category-icon {
                width: 30px;
                height: 30px;
                max-width: 100%;
            }

            .category-label {
                font-size: 8px;
                word-wrap: break-word;
                line-height: 1.1;
            }

            /* Form Groups */
            .form-group {
                margin-bottom: 16px;
                max-width: 100% !important;
                width: 100% !important;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 8px;
                max-width: 100%;
            }

            .form-control {
                padding: 14px 16px;
                font-size: 15px !important;
                border-radius: 12px;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            textarea.form-control {
                min-height: 100px;
                max-width: 100% !important;
                width: 100% !important;
            }

            input.form-control {
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Upload Zone */
            .upload-zone {
                padding: 35px 15px;
                border-radius: 14px;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .upload-icon {
                width: 36px;
                height: 36px;
                max-width: 100%;
            }

            .upload-text {
                font-size: 14px;
                max-width: 100%;
            }

            .upload-subtext {
                font-size: 12px;
                max-width: 100%;
            }

            .media-preview {
                max-width: 100% !important;
                width: 100% !important;
            }

            .media-preview img,
            .media-preview video {
                max-height: 200px;
                border-radius: 12px;
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Date Picker */
            .date-input-wrapper {
                max-width: 100% !important;
            }

            .date-badge {
                padding: 12px 18px;
                font-size: 14px;
                border-radius: 24px;
                max-width: 100% !important;
            }

            /* Music Section */
            .music-header {
                padding: 16px;
                border-radius: 14px;
            }

            .music-header-left h3 {
                font-size: 13px;
            }

            .music-header-left p {
                font-size: 12px;
            }

            .music-list.open {
                max-height: none !important;
                overflow-y: visible !important;
            }

            .music-item {
                padding: 14px;
                border-radius: 12px;
                gap: 12px;
            }

            .music-icon-wrapper {
                width: 48px;
                height: 48px;
                border-radius: 10px;
            }

            .music-icon {
                width: 26px;
                height: 26px;
            }

            .music-title {
                font-size: 14px;
            }

            .music-duration {
                font-size: 12px;
            }

            .btn-play-music,
            .btn-delete-music {
                width: auto !important;
                height: auto !important;
                background: transparent !important;
                display: none !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 4px !important;
                border-radius: 0 !important;
            }

            .music-item.selected .btn-play-music,
            .music-item.selected .btn-delete-music {
                display: flex !important;
                opacity: 1 !important;
                transform: scale(1) !important;
                animation: none !important;
            }

            .btn-play-music svg,
            .btn-delete-music svg {
                width: 20px !important;
                height: 20px !important;
                display: block !important;
                opacity: 1 !important;
            }

            .play-icon {
                width: 20px !important;
                height: 20px !important;
                color: #E5C9A1 !important;
                fill: #E5C9A1 !important;
                margin-left: 0 !important;
                opacity: 1 !important;
            }

            .delete-icon {
                width: 20px !important;
                height: 20px !important;
                color: #EF4444 !important;
                stroke: #EF4444 !important;
                fill: none !important;
                opacity: 1 !important;
            }

            /* Buttons */
            .btn-submit {
                padding: 16px;
                font-size: 16px;
                border-radius: 12px;
                min-height: 52px;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .btn-preview-mobile {
                display: block !important;
                padding: 14px !important;
                font-size: 15px !important;
                border-radius: 12px !important;
                margin: 0 !important;
                min-height: 50px !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .btn-submit {
                display: none !important;
            }

            .btn-remove-media,
            button[type="button"],
            button[type="submit"] {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            /* Preview Card */
            .preview-card {
                display: none;
            }

            /* Modal */
            .modal-overlay {
                padding: 15px;
            }

            .modal-content {
                border-radius: 18px;
                max-height: 85vh;
            }

            .modal-header {
                padding: 18px 20px;
            }

            .modal-title {
                font-size: 17px;
            }

            .modal-body {
                padding: 18px 20px 20px;
            }

            .preview-content {
                min-height: 250px !important;
                border-radius: 14px;
            }

            /* Music Badge - Mobile */
            .music-badge {
                padding: 6px 10px !important;
                font-size: 10px !important;
                max-width: 95% !important;
                margin: 0 auto 12px !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px !important;
                flex-wrap: nowrap !important;
                position: relative !important;
                top: auto !important;
                left: auto !important;
                transform: none !important;
            }

            .music-badge-icon {
                width: 13px !important;
                height: 13px !important;
                flex-shrink: 0 !important;
            }

            .music-badge-text {
                font-size: 10px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                max-width: 200px !important;
            }
        }

        @media (max-width: 480px) {
            /* Extra small mobile */
            html {
                overflow-y: scroll !important;
            }

            body {
                overflow-y: visible !important;
                height: auto !important;
            }

            .header {
                padding: 12px 15px;
            }

            .logo {
                height: 35px;
            }

            .main-container {
                padding: 110px 12px 15px;
                overflow: visible !important;
            }

            .form-header {
                padding: 18px 16px;
            }

            .form-title {
                font-size: 17px;
            }

            .form-subtitle {
                font-size: 12px;
            }

            /* Music Badge - Small Mobile */
            .music-badge-text {
                max-width: 150px !important;
                font-size: 9px !important;
            }

            .form-body {
                padding: 16px;
            }

            /* ULTRA FORCE: Everything must fit on tiny screens */
            * {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            html, body {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            .main-container {
                max-width: 100vw !important;
            }

            .form-card,
            .form-body {
                max-width: 100% !important;
            }

            .category-item {
                width: 72px !important;
                height: 72px !important;
                min-height: 72px !important;
                max-height: 72px !important;
                padding: 7px 4px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .category-icon-wrapper {
                width: 38px;
                height: 38px;
                margin-bottom: 3px;
            }

            .category-icon {
                width: 28px !important;
                height: 28px !important;
            }

            .category-label {
                font-size: 7px !important;
                line-height: 1.1;
                word-wrap: break-word;
            }
        }

        @media (max-width: 768px) {
            .category-item {
                width: 65px !important;
                padding: 6px 4px !important;
                flex-shrink: 0 !important;
            }

            .category-icon {
                width: 16px !important;
                height: 16px !important;
            }
        }

        @media (hover: none) and (pointer: coarse) {
            .category-item, .music-item, .btn-submit, .btn-preview-mobile, .btn-contact {
                min-height: 44px;
                touch-action: manipulation;
            }

            .form-control {
                padding: 12px !important;
                font-size: 15px !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .music-item {
                padding: 12px;
            }

            .music-icon-wrapper {
                width: 44px;
                height: 44px;
            }

            .btn-play-music,
            .btn-delete-music {
                width: auto !important;
                height: auto !important;
                background: transparent !important;
                display: none !important;
                padding: 3px !important;
            }

            .music-item.selected .btn-play-music,
            .music-item.selected .btn-delete-music {
                display: flex !important;
                opacity: 1 !important;
                transform: scale(1) !important;
            }

            .btn-play-music svg,
            .btn-delete-music svg {
                width: 18px !important;
                height: 18px !important;
            }

            .play-icon {
                width: 18px !important;
                height: 18px !important;
                fill: #E5C9A1 !important;
                opacity: 1 !important;
            }

            .delete-icon {
                width: 18px !important;
                height: 18px !important;
                stroke: #EF4444 !important;
                opacity: 1 !important;
            }

            .music-arrow-wrapper {
                width: 34px;
                height: 34px;
            }
        }

        /* Touch-friendly elements */
        @media (hover: none) and (pointer: coarse) {
            .category-item,
            .music-item,
            .btn-submit,
            .btn-preview-mobile,
            .btn-contact {
                min-height: 44px;
                touch-action: manipulation;
            }

            .form-control {
                font-size: 16px !important;
            }
        }

        /* Loader Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ========================================
           RESPONSIVE - MOBILE FIRST APPROACH
        ======================================== */
        @media (max-width: 768px) {
            /* CRITICAL: Force viewport */
            html {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            body {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                height: auto !important;
            }

            /* Header */
            .header {
                padding: 15px !important;
                grid-template-columns: 1fr !important;
                width: 100% !important;
            }

            .nav,
            .nav-right {
                display: none !important;
            }

            .logo {
                justify-content: center !important;
            }

            .logo img {
                height: 38px !important;
            }

            /* Main Container */
            .main-container {
               width: 100% !important;
                max-width: 100% !important;
                grid-template-columns: 1fr !important;
                gap: 0 !important;
                margin: 0 !important;
            }

            /* Form Card */
            .form-card {
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 16px !important;
            }

            .form-header {
                padding: 20px 15px !important;
                width: 100% !important;
            }

            .form-title {
                font-size: 18px !important;
            }

            .form-subtitle {
                font-size: 13px !important;
            }

            .form-body {
                padding: 18px 15px 20px !important;
                width: 100% !important;
            }

            /* Categories */
            .categories-wrapper {
                width: 100% !important;
                overflow-x: auto !important;
            }

            .categories-grid {
                gap: 10px !important;
            }

            .category-item {
                width: 80px !important;
                height: 80px !important;
                min-height: 80px !important;
                max-height: 80px !important;
                padding: 8px 5px !important;
                flex-shrink: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .category-icon-wrapper {
                width: 42px !important;
                height: 42px !important;
            }

            .category-icon {
                width: 30px !important;
                height: 30px !important;
            }

            .category-label {
                font-size: 8px !important;
                line-height: 1.1 !important;
                word-wrap: break-word !important;
            }

            /* Form Elements */
            .form-group {
                width: 100% !important;
                margin-bottom: 16px !important;
            }

            .form-label {
                font-size: 13px !important;
            }

            .form-control,
            input,
            textarea,
            select {
                width: 100% !important;
                max-width: 100% !important;
                padding: 14px !important;
                font-size: 16px !important;
                border-radius: 12px !important;
                box-sizing: border-box !important;
            }

            textarea.form-control {
                min-height: 100px !important;
            }

            /* Upload Zone */
            .upload-zone {
                width: 100% !important;
                padding: 30px 15px !important;
            }

            .media-preview img,
            .media-preview video {
                max-width: 100% !important;
                max-height: 200px !important;
            }

            /* Date */
            .date-badge {
                padding: 12px 18px !important;
                font-size: 14px !important;
            }

            /* Music */
            .music-section {
                width: 100% !important;
            }

            .music-header {
                padding: 16px !important;
            }

            .music-list.open {
                max-height: none !important;
            }

            .music-item {
                padding: 14px !important;
            }

            /* Buttons */
            .btn-submit,
            .btn-preview-mobile,
            button {
                width: 100% !important;
                max-width: 100% !important;
                padding: 16px !important;
                box-sizing: border-box !important;
            }

            .btn-preview-mobile {
                display: block !important;
            }

            .preview-card {
                display: none !important;
            }
        }

        @media (max-width: 480px) {


            .form-body {
                padding: 16px 12px !important;
            }

            .category-item {
                width: 72px !important;
                min-width: 72px !important;
                height: 72px !important;
                min-height: 72px !important;
                max-height: 72px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Decorative Background -->
    <div class="decorative-bg">
        <img src="{{ asset('images/clientIamges/1.png') }}" alt="" class="decorative-icon wallet">
        <img src="{{ asset('images/clientIamges/Birthday Cake.png') }}" alt="" class="decorative-icon cake">
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg w-100" style="background: transparent; padding: 30px 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="/" style="text-decoration: none;">
                <img src="{{ asset('images/clientIamges/Frame 3771.png') }}" alt="Supriz" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: none;">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="/" style="font-size: 15px; font-weight: 400;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="/#comment" style="font-size: 15px; font-weight: 400;">Comment ça marche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="/#apropos" style="font-size: 15px; font-weight: 400;">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3" href="/#faq" style="font-size: 15px; font-weight: 400;">FAQ</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center" style="gap: 15px;">
                    <a href="/#contact" class="btn" style="background: #E4D1B9; color: #005792; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; white-space: nowrap;">
                        Contactez-nous
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">Envoie un message surprise inoubliable</h1>
                <p class="form-subtitle">Choisissez une occasion et faite sourire un proche !!</p>
            </div>

            <div class="form-body">
                <form id="messageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="sous_categorie_id" id="sous_categorie_id">
                    <input type="hidden" name="musique_id" id="musique_id">
                    <input type="hidden" name="expiration" id="expiration_value" value="24h">

                    <!-- Categories -->
                    <div class="categories-wrapper">
                        <div class="categories-grid">
                            @foreach($categoriesSupriz as $categorie)
                            <div class="category-item" data-category="{{ $categorie->id }}" data-placeholder="{{ $categorie->placeholder_texte ?? 'Ex: Votre message ici' }}" data-image="{{ $categorie->image ?? '' }}">
                                <div class="category-icon-wrapper">
                                    @if($categorie->icone)
                                        <img src="{{ asset('storage/' . $categorie->icone) }}" alt="{{ $categorie->name_sous_categories }}" class="category-icon">
                                    @else
                                        <svg style="width: 40px; height: 40px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="category-label">{{ $categorie->name_sous_categories }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div class="scroll-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Titre du message *</label>
                        <input type="text" class="form-control" name="titre" id="messageTitle" placeholder="Ex: Joyeux anniversaire John Doe" maxlength="60" required>
                        <div class="char-counter" id="titleCounter">0/60</div>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="contenu" id="messageContent" placeholder="Écrivez votre message ici..." maxlength="500"></textarea>
                        <div class="char-counter" id="messageCounter">0/500</div>
                    </div>

                    <!-- Image/Video Upload -->
                    <div class="form-group">
                        <label class="form-label">Image ou vidéo</label>
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('mediaFile').click()">
                            <input type="file" name="media" id="mediaFile" accept="image/*,video/*" style="display: none;">

                            <div id="uploadPlaceholder">
                                <svg class="upload-icon" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="upload-text">Ajouter une image ou vidéo</p>
                                <p class="upload-subtext">Max 10 MB</p>
                            </div>

                            <div id="mediaPreview" class="media-preview">
                                <img id="imagePreview" style="display: none;" alt="Preview">
                                <video id="videoPreview" controls style="display: none;"></video>
                                <button type="button" class="btn-remove-media" onclick="removeMedia(event)">Supprimer</button>
                            </div>
                        </div>
                    </div>

                    <!-- Expiration Date -->
                    <div class="form-group">
                        <div class="date-input-wrapper">
                            <div class="date-badge">
                                <span>Expire dans 24h</span>
                                <input type="hidden" id="expirationDate" value="24h">
                                <svg class="clock-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Music -->
                    <div class="music-section">
                        <div class="music-header" onclick="toggleMusicList()">
                            <div class="music-header-left">
                                <h3>Musiques</h3>
                                <p>Sélectionnez une musique pour amplifier l'émotion à fait passer.</p>
                            </div>
                            <svg class="music-toggle-icon" id="musicToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="music-list" id="musicList">
                            @foreach($musiques as $musique)
                            <div class="music-item" data-music-id="{{ $musique->id }}" data-audio-url="{{ asset('storage/' . $musique->fichier_audio) }}" onclick="selectMusic(this, event)">
                                <div class="music-icon-wrapper">
                                    <img src="{{ asset('images/clientIamges/music.svg') }}" alt="Music" class="music-icon">
                                </div>
                                <div class="music-info">
                                    <div class="music-title">{{ $musique->titre }}</div>
                                    <div class="music-duration">
                                        @if($musique->duree)
                                            @php
                                                $minutes = floor($musique->duree / 60);
                                                $seconds = $musique->duree % 60;
                                                $formatted = sprintf('%02d:%02d', $minutes, $seconds);
                                            @endphp
                                            {{ $formatted }}
                                        @else
                                            00:00
                                        @endif
                                    </div>
                                </div>
                                <div class="music-actions">
                                    <button type="button" class="btn-play-music" onclick="playMusic(event, this)">
                                        <svg class="play-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-delete-music" onclick="deleteMusic(event, this)">
                                        <svg class="delete-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <div class="music-arrow-wrapper">
                                        <svg class="music-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" class="btn-submit" onclick="generateMessage()">Prévisualiser</button>

                    <!-- Mobile Preview Button -->
                    <button type="button" class="btn-preview-mobile" onclick="openPreviewModal()">
                        Prévisualiser mon message
                    </button>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="preview-card">
            <h2 class="preview-title">Prévisualisé mon message surpriz</h2>

            <div class="preview-content" id="previewContent">
                <div class="preview-empty">
                    <img src="{{ asset('images/clientIamges/1.png') }}" alt="" class="preview-empty-icon">
                    <p>Remplissez et soumettez le formulaire,</p>
                    <p>pour voir une prévisualisation de votre</p>
                    <p>message supriz.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Preview Modal -->
    <div class="modal-overlay" id="previewModal" onclick="closePreviewModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Prévisualisation</h3>
                <button class="btn-close-modal" onclick="closePreviewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="preview-content" id="mobilePreviewContent" style="min-height: 300px;">
                    <div class="preview-empty">
                        <img src="{{ asset('images/clientIamges/1.png') }}" alt="" class="preview-empty-icon">
                        <p>Remplissez le formulaire,</p>
                        <p>pour voir une prévisualisation</p>
                        <p>de votre message supriz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const titleInput = document.getElementById('messageTitle');

        // Function to load musiques by category
        function loadMusiquesByCategorie(categorieId) {
            fetch(`/api/musiques/categorie/${categorieId}`)
                .then(response => response.json())
                .then(data => {
                    const musicSection = document.querySelector('.music-section');

                    if (data.has_musiques && data.musiques.length > 0) {
                        // Show music section
                        musicSection.style.display = 'block';

                        // Update music list
                        const musicList = musicSection.querySelector('.music-list');
                        musicList.innerHTML = '';

                        data.musiques.forEach(musique => {
                            const musicItem = `
                                <div class="music-item" data-music-id="${musique.id}" data-audio-url="{{ asset('storage/') }}/${musique.fichier_audio}">
                                    <div class="music-icon-wrapper">
                                        <img src="{{ asset('images/clientIamges/music.svg') }}" alt="Music" class="music-icon">
                                    </div>
                                    <div class="music-info">
                                        <div class="music-title">${musique.titre}</div>
                                        <div class="music-duration">${musique.duree || '00:00'}</div>
                                    </div>
                                    <div class="music-actions">
                                        <button type="button" class="btn-play-music" onclick="playMusicAjax(event, this)">
                                            <svg class="play-icon" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn-delete-music" onclick="deleteSelectedMusic(event, this)">
                                            <svg class="delete-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                        <div class="music-arrow-wrapper">
                                            <svg class="music-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            `;
                            musicList.innerHTML += musicItem;
                        });

                        // Add click handlers for music selection
                        document.querySelectorAll('.music-item').forEach(item => {
                            item.addEventListener('click', function(e) {
                                // Don't select if clicking on buttons
                                if (!e.target.closest('.btn-play-music') && !e.target.closest('.btn-delete-music')) {
                                    document.querySelectorAll('.music-item').forEach(i => i.classList.remove('selected'));
                                    this.classList.add('selected');
                                    document.getElementById('musique_id').value = this.getAttribute('data-music-id');
                                }
                            });
                        });
                    } else {
                        // Hide music section if no musiques
                        musicSection.style.display = 'none';
                        document.getElementById('musique_id').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error loading musiques:', error);
                    document.querySelector('.music-section').style.display = 'none';
                });
        }

        // Check if categories grid is scrollable and show indicator
        function checkScrollIndicator() {
            const categoriesGrid = document.querySelector('.categories-grid');
            const scrollIndicator = document.querySelector('.scroll-indicator');

            if (categoriesGrid && scrollIndicator) {
                const isScrollable = categoriesGrid.scrollWidth > categoriesGrid.clientWidth;
                if (isScrollable) {
                    scrollIndicator.classList.add('visible');
                } else {
                    scrollIndicator.classList.remove('visible');
                }
            }
        }

        // Check on page load and window resize
        window.addEventListener('load', checkScrollIndicator);
        window.addEventListener('resize', checkScrollIndicator);

        // Hide indicator when scrolled to the end
        const categoriesGrid = document.querySelector('.categories-grid');
        if (categoriesGrid) {
            categoriesGrid.addEventListener('scroll', function() {
                const scrollIndicator = document.querySelector('.scroll-indicator');
                const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth - 10;

                if (isAtEnd) {
                    scrollIndicator.classList.remove('visible');
                } else if (this.scrollWidth > this.clientWidth) {
                    scrollIndicator.classList.add('visible');
                }
            });
        }

        // Category selection
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                // Update placeholder
                const placeholder = this.getAttribute('data-placeholder');
                if (placeholder && titleInput) {
                    titleInput.placeholder = placeholder;
                }

                // Update hidden field for category
                const categoryId = this.getAttribute('data-category');
                document.getElementById('sous_categorie_id').value = categoryId;

                // Load musiques for this category
                loadMusiquesByCategorie(categoryId);
            });
        });

        // Active first category by default
        const firstCategory = document.querySelector('.category-item');
        if (firstCategory) {
            firstCategory.classList.add('active');
            const placeholder = firstCategory.getAttribute('data-placeholder');
            if (placeholder && titleInput) {
                titleInput.placeholder = placeholder;
            }
            // Set default category
            const categoryId = firstCategory.getAttribute('data-category');
            document.getElementById('sous_categorie_id').value = categoryId;
            // Load musiques for first category
            loadMusiquesByCategorie(categoryId);
        }

        // Real-time preview
        const contentInput = document.getElementById('messageContent');
        const previewContent = document.getElementById('previewContent');
        const mobilePreviewContent = document.getElementById('mobilePreviewContent');

        function getPreviewHTML(title, content, includeMedia = true) {
            if (title || content) {
                let mediaHTML = '';

                // Add image or video if uploaded
                if (includeMedia) {
                    const imagePreview = document.getElementById('imagePreview');
                    const videoPreview = document.getElementById('videoPreview');

                    if (imagePreview && imagePreview.style.display !== 'none' && imagePreview.src) {
                        mediaHTML = `<img src="${imagePreview.src}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 12px;" alt="Preview">`;
                    } else if (videoPreview && videoPreview.style.display !== 'none' && videoPreview.src) {
                        mediaHTML = `<video controls style="width: 100%; max-height: 200px; border-radius: 10px; margin-bottom: 12px;"><source src="${videoPreview.src}"></video>`;
                    }
                }

                return `
                    <div style="background: white; border-radius: 14px; padding: 20px; width: 100%; max-width: 380px; max-height: 500px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        ${title ? `<h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: ${mediaHTML ? '10px' : '12px'}; text-align: left; word-wrap: break-word;">${title}</h3>` : ''}
                        ${mediaHTML}
                        ${content ? `<p style="font-size: 13px; color: #4b5563; line-height: 1.6; white-space: pre-wrap; text-align: left; word-wrap: break-word;">${content}</p>` : ''}
                    </div>
                `;
            } else {
                return `
                    <div class="preview-empty">
                        <img src="{{ asset('images/clientIamges/1.png') }}" alt="" class="preview-empty-icon">
                        <p>Remplissez et soumettez le formulaire,</p>
                        <p>pour voir une prévisualisation de votre</p>
                        <p>message supriz.</p>
                    </div>
                `;
            }
        }

        function updatePreview() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();

            if (!title && !content) {
                return;
            }

            // Add background
            previewContent.classList.add('has-content');
            mobilePreviewContent.classList.add('has-content');

            // Get selected category image
            const selectedCategory = document.querySelector('.category-item.active');
            const categoryImage = selectedCategory ? selectedCategory.getAttribute('data-image') : '';

            // Determine gift image URL and animation class
            let giftImageUrl = '{{ asset("images/clientIamges/Wrapped_Gift.gif") }}';
            let giftImageClass = '';

            if (categoryImage) {
                giftImageUrl = '{{ asset("storage/") }}/' + categoryImage;
                // Check if it's a GIF
                const isGif = categoryImage.toLowerCase().endsWith('.gif');
                // Add animation class only if it's NOT a GIF
                giftImageClass = isGif ? '' : 'animate';
            }

            // Show gift animation with title - exactly like MacBook3.png
            const giftHTML = `
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column;">
                    <!-- Music badge at top -->
                    ${selectedMusicId ? `
                    <div style="text-align: center; padding: 20px 0;">
                        <div class="music-badge" style="display: inline-block;">
                            <img src="{{ asset('images/clientIamges/music.svg') }}" alt="Music" class="music-badge-icon">
                            <span class="music-badge-text">${document.querySelector('.music-item.selected')?.querySelector('.music-title').textContent || ''}</span>
                        </div>
                    </div>` : ''}

                    <!-- Gift card clickable - centered with border -->
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
                        <div onclick="showMessageContent()" style="background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.3); border-radius: 20px; padding: 50px 80px; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(5px);">
                            <h3 style="color: white; text-align: center; margin-bottom: 40px; font-size: 22px; font-weight: 600;">${title}</h3>
                            <div class="gift-animation" style="display: flex; justify-content: center;">
                                <img src="${giftImageUrl}" alt="Gift" style="width: 280px; height: auto;" class="${giftImageClass}">
                            </div>
                        </div>
                    </div>

                    <!-- Generate button at bottom -->
                    <div style="padding: 0 20px 20px;">
                        <button class="btn-generate-preview" onclick="finalizeMessage()">Générer</button>
                    </div>
                </div>
            `;

            previewContent.innerHTML = giftHTML;
            mobilePreviewContent.innerHTML = giftHTML;

            // Auto-show message content after 10 seconds if user doesn't click
            setTimeout(() => {
                // Check if gift is still displayed (user hasn't clicked yet)
                if (previewContent.innerHTML.includes('gift-animation')) {
                    showMessageContent();
                }
            }, 10000); // 10 seconds delay
        }

        function getOldPreviewHTML() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');

            // Build media element for message card
            let messageMediaHTML = '';
            if (imagePreview && imagePreview.style.display !== 'none' && imagePreview.src) {
                messageMediaHTML = `<img src="${imagePreview.src}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0;" alt="Preview">`;
            }

            // Build preview HTML (for old code compatibility)
            const previewHTML = `
                <div style="background: #E8EDF2; border-radius: 20px; padding: 40px 30px; width: 100%; max-width: 520px;">
                    <!-- Title with Active badge -->
                    <div style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 22px; font-weight: 700; color: #1a1a1a; margin: 0;">${title}</h3>
                        <span style="background: #C7E9D7; color: #047857; font-size: 12px; padding: 6px 16px; border-radius: 20px; font-weight: 600;">Actif</span>
                    </div>

                    <!-- Message Card -->
                    <div style="background: #C8DFE7; border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            ${messageMediaHTML}
                            ${content ? `<p style="font-size: 14px; color: #1a1a1a; line-height: 1.7; margin: 0; white-space: pre-wrap; flex: 1;">${content}</p>` : ''}
                        </div>
                    </div>

                    <!-- Expiration and views -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <p style="font-size: 13px; color: #4b5563; margin: 0;">Expire le 31/12/2024</p>
                        <div style="background: #D1E3EA; padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" fill="none" stroke="#005792" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span style="font-size: 13px; color: #005792; font-weight: 600;">Aperçu</span>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div id="qrcode_preview" style="display: inline-block; padding: 0;">
                            <div style="width: 180px; height: 180px; background: white; border: 8px solid white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="180" height="180" viewBox="0 0 180 180">
                                    <rect width="180" height="180" fill="#000"/>
                                    <rect x="10" y="10" width="160" height="160" fill="#fff"/>
                                    <text x="90" y="95" text-anchor="middle" font-size="14" fill="#999">QR Code</text>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Share link -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 400; color: #4b5563; margin-bottom: 10px;">Lien de partage</label>
                        <div style="background: white; border-radius: 10px; padding: 4px 4px 4px 16px; display: flex; align-items: center; gap: 8px;">
                            <input type="text" value="https://app.example.com/view/ab..." readonly
                                style="flex: 1; border: none; background: transparent; font-size: 14px; color: #1a1a1a; outline: none;">
                            <button style="background: #005792; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div style="display: flex; gap: 15px;">
                        <button style="flex: 1; background: white; border: none; color: #005792; padding: 16px; border-radius: 12px; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Partager
                        </button>
                        <button style="flex: 1; background: white; border: none; color: #005792; padding: 16px; border-radius: 12px; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            QR Code
                        </button>
                    </div>
                </div>
            `;

            previewContent.innerHTML = previewHTML;
            mobilePreviewContent.innerHTML = previewHTML;
        }

        function showMessageContent() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();

            // Get media HTML
            let mediaHTML = '';
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');

            if (imagePreview && imagePreview.style.display !== 'none' && imagePreview.src) {
                mediaHTML = `
                    <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: ${content ? '15px' : '0'};">
                        <img src="${imagePreview.src}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover;" alt="Preview">
                        <div style="background: white; padding: 10px 16px; border-radius: 8px;">
                            <p style="font-size: 14px; color: #1a1a1a; margin: 0; font-weight: 500;">${title}</p>
                        </div>
                    </div>
                `;
            } else if (videoPreview && videoPreview.style.display !== 'none' && videoPreview.src) {
                mediaHTML = `
                    <div style="margin-bottom: ${content ? '15px' : '0'};">
                        <video controls style="width: 100%; max-height: 200px; border-radius: 12px;"><source src="${videoPreview.src}"></video>
                    </div>
                `;
            }

            // Show message preview - exactly like MacBook3.png Image #2
            const messageHTML = `
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column;">
                    <!-- Music badge at top -->
                    ${selectedMusicId ? `
                    <div style="text-align: center; padding: 20px 0;">
                        <div class="music-badge" style="display: inline-block;">
                            <img src="{{ asset('images/clientIamges/music.svg') }}" alt="Music" class="music-badge-icon">
                            <span class="music-badge-text">${document.querySelector('.music-item.selected')?.querySelector('.music-title').textContent || ''}</span>
                        </div>
                    </div>` : ''}

                    <!-- Message card - centered white card -->
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <div style="background: white; border-radius: 20px; padding: 0; width: 100%; max-width: 500px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); overflow: hidden;">
                            <!-- Title -->
                            <div style="padding: 25px 25px 20px 25px;">
                                <h3 style="color: #1a1a1a; font-size: 18px; font-weight: 600; margin: 0;">${title}</h3>
                            </div>

                            <!-- Image full width -->
                            ${mediaHTML ? `
                            <div style="width: 100%;">
                                ${imagePreview && imagePreview.style.display !== 'none' && imagePreview.src ?
                                    `<img src="${imagePreview.src}" style="width: 100%; height: 300px; object-fit: cover; display: block;" alt="Preview">` :
                                    (videoPreview && videoPreview.style.display !== 'none' && videoPreview.src ?
                                        `<video controls style="width: 100%; height: 300px; object-fit: cover; display: block;"><source src="${videoPreview.src}"></video>` : '')
                                }
                            </div>` : ''}

                            <!-- Message content -->
                            ${content ? `
                            <div style="padding: 20px 25px 25px 25px;">
                                <p style="font-size: 14px; color: #1a1a1a; line-height: 1.7; margin: 0; white-space: pre-wrap;">${content}</p>
                            </div>` : ''}
                        </div>
                    </div>

                    <!-- Generate button at bottom -->
                    <div style="padding: 0 20px 20px;">
                        <button class="btn-generate-preview" onclick="finalizeMessage()">Générer</button>
                    </div>
                </div>
            `;

            previewContent.innerHTML = messageHTML;
            mobilePreviewContent.innerHTML = messageHTML;
        }

        function finalizeMessage() {
            const form = document.getElementById('messageForm');
            const formData = new FormData(form);

            // Validation
            const title = document.getElementById('messageTitle').value.trim();
            const categoryId = document.getElementById('sous_categorie_id').value;

            if (!title) {
                alert('Veuillez saisir un titre pour votre message');
                return;
            }

            if (!categoryId) {
                alert('Veuillez sélectionner une catégorie');
                return;
            }

            // Afficher un loader sur desktop ET mobile
            const loaderHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #005792; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 20px; color: white; font-weight: 600;">Génération du message...</p>
                </div>
            `;
            previewContent.innerHTML = loaderHTML;
            mobilePreviewContent.innerHTML = loaderHTML;

            // Soumettre via AJAX
            fetch('{{ route("message.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showShareInterface(data.data);
                } else {
                    alert('Erreur lors de la création du message');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        }

        function showShareInterface(messageData) {
            // Get media HTML
            let mediaHTML = '';
            const imagePreview = document.getElementById('imagePreview');
            const videoPreview = document.getElementById('videoPreview');

            if (imagePreview && imagePreview.style.display !== 'none' && imagePreview.src) {
                mediaHTML = `<img src="${imagePreview.src}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0;" alt="Preview">`;
            }

            // Afficher l'interface de partage avec le design exact
            const shareHTML = `
                <div style="background: #E8EDF2; border-radius: 20px; padding: 40px 30px; width: 100%; max-width: 520px;">
                    <!-- Title with Active badge -->
                    <div style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 22px; font-weight: 700; color: #1a1a1a; margin: 0;">${messageData.titre}</h3>
                        <span style="background: #C7E9D7; color: #047857; font-size: 12px; padding: 6px 16px; border-radius: 20px; font-weight: 600;">Actif</span>
                    </div>

                    <!-- Message Card -->
                    <div style="background: #C8DFE7; border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                        <div style="align-items: flex-start; gap: 12px;">
                            ${mediaHTML}
                            ${messageData.contenu ? `<p style="font-size: 14px; color: #1a1a1a; line-height: 1.7; margin: 0; white-space: pre-wrap; flex: 1;">${messageData.contenu}</p>` : ''}
                        </div>
                    </div>

                    <!-- Expiration and views -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <p style="font-size: 13px; color: #4b5563; margin: 0;">Expire le ${messageData.date_expiration}</p>
                        <div style="background: #D1E3EA; padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" fill="none" stroke="#005792" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span style="font-size: 13px; color: #005792; font-weight: 600;">Aperçu</span>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div class="qrcode-container" style="display: inline-block;"></div>
                    </div>

                    <!-- Share link -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 400; color: #4b5563; margin-bottom: 10px;">Lien de partage</label>
                        <div style="background: #fff; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; min-width: 0; background: #f9fafb; padding: 10px 12px; border-radius: 8px;">
                                <p id="shareLink" style="margin: 0; font-size: 14px; color: #111827; font-family: monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${messageData.url}</p>
                            </div>
                            <button onclick="copyShareLink()" style="background: #005792; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; flex-shrink: 0; min-width: 48px; min-height: 48px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="copySuccess" style="color: #10B981; font-size: 12px; margin-top: 8px; display: none;">✓ Lien copié!</p>
                    </div>

                    <!-- Action buttons -->
                    <div style="display: flex; gap: 15px;">
                        <button onclick="shareMessage('${messageData.url}')"
                            style="flex: 1; background: white; border: none; color: #005792; padding: 16px; border-radius: 12px; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Partager
                        </button>
                        <button onclick="downloadQRCode()"
                            style="flex: 1; background: white; border: none; color: #005792; padding: 16px; border-radius: 12px; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            QR Code
                        </button>
                    </div>
                </div>
            `;

            previewContent.innerHTML = shareHTML;
            mobilePreviewContent.innerHTML = shareHTML;
            previewContent.classList.remove('has-content');
            mobilePreviewContent.classList.remove('has-content');

            // Générer le QR code - attendre un peu que le DOM soit mis à jour
            setTimeout(() => {
                // Generate QR code for both desktop and mobile preview
                const qrcodeElements = document.querySelectorAll(".qrcode-container");
                qrcodeElements.forEach(qrcodeElement => {
                    if (qrcodeElement) {
                        qrcodeElement.innerHTML = ''; // Clear previous QR code if any
                        new QRCode(qrcodeElement, {
                            text: messageData.url,
                            width: 180,
                            height: 180,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }
                });
            }, 100);
        }

        function copyShareLink() {
            const shareLink = document.getElementById('shareLink');
            const textToCopy = shareLink.textContent || shareLink.innerText;

            // Use modern Clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    showCopySuccess();
                }).catch(err => {
                    // Fallback to old method
                    fallbackCopyTextToClipboard(textToCopy);
                });
            } else {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(textToCopy);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
                showCopySuccess();
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Impossible de copier le lien');
            }

            document.body.removeChild(textarea);
        }

        function showCopySuccess() {
            const copySuccessElements = document.querySelectorAll('#copySuccess');
            copySuccessElements.forEach(element => {
                element.style.display = 'block';
            });

            setTimeout(() => {
                copySuccessElements.forEach(element => {
                    element.style.display = 'none';
                });
            }, 3000);
        }

        function shareMessage(url) {
            if (navigator.share) {
                navigator.share({
                    title: 'Message Supriz',
                    text: 'Découvrez mon message surprise!',
                    url: url
                }).catch(err => console.log('Error sharing:', err));
            } else {
                copyShareLink();
            }
        }

        function downloadQRCode() {
            const canvas = document.querySelector('.qrcode-container canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'qrcode-supriz.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        }

        // Remove auto-update on input
        // titleInput.addEventListener('input', updatePreview);
        // contentInput.addEventListener('input', updatePreview);

        // Character counters
        const titleCounter = document.getElementById('titleCounter');
        const messageCounter = document.getElementById('messageCounter');

        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = this.getAttribute('maxlength');
            titleCounter.textContent = `${length}/${maxLength}`;

            // Add warning/danger classes
            titleCounter.classList.remove('warning', 'danger');
            if (length > maxLength * 0.9) {
                titleCounter.classList.add('danger');
            } else if (length > maxLength * 0.7) {
                titleCounter.classList.add('warning');
            }
        });

        contentInput.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = this.getAttribute('maxlength');
            messageCounter.textContent = `${length}/${maxLength}`;

            // Add warning/danger classes
            messageCounter.classList.remove('warning', 'danger');
            if (length > maxLength * 0.9) {
                messageCounter.classList.add('danger');
            } else if (length > maxLength * 0.7) {
                messageCounter.classList.add('warning');
            }
        });

        // Media upload handling
        const mediaFile = document.getElementById('mediaFile');
        const uploadZone = document.getElementById('uploadZone');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const mediaPreview = document.getElementById('mediaPreview');
        const imagePreview = document.getElementById('imagePreview');
        const videoPreview = document.getElementById('videoPreview');
        const musicSection = document.querySelector('.music-section');

        mediaFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleMediaFile(file);
            }
        });

        function handleMediaFile(file) {
            const fileType = file.type;
            const fileURL = URL.createObjectURL(file);

            uploadPlaceholder.style.display = 'none';
            mediaPreview.classList.add('active');
            uploadZone.classList.add('has-media');

            if (fileType.startsWith('image/')) {
                imagePreview.src = fileURL;
                imagePreview.style.display = 'block';
                videoPreview.style.display = 'none';
                // Allow music for images
                musicSection.classList.remove('disabled');
            } else if (fileType.startsWith('video/')) {
                videoPreview.src = fileURL;
                videoPreview.style.display = 'block';
                imagePreview.style.display = 'none';
                // Disable music for videos
                musicSection.classList.add('disabled');
                // Deselect any selected music
                document.querySelectorAll('.music-item').forEach(item => {
                    item.classList.remove('selected');
                });
                selectedMusicId = null;
                document.getElementById('musique_id').value = '';
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio = null;
                }
            }
        }

        function removeMedia(event) {
            event.stopPropagation();

            mediaFile.value = '';
            imagePreview.src = '';
            videoPreview.src = '';
            imagePreview.style.display = 'none';
            videoPreview.style.display = 'none';
            mediaPreview.classList.remove('active');
            uploadPlaceholder.style.display = 'block';
            uploadZone.classList.remove('has-media');

            // Re-enable music section
            musicSection.classList.remove('disabled');
        }

        // Modal functions
        function openPreviewModal() {
            updatePreview();
            document.getElementById('previewModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePreviewModal(event) {
            if (!event || event.target === event.currentTarget) {
                document.getElementById('previewModal').classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Music section toggle
        function toggleMusicList() {
            const musicList = document.getElementById('musicList');
            const toggleIcon = document.getElementById('musicToggleIcon');

            musicList.classList.toggle('open');
            toggleIcon.classList.toggle('open');
        }

        // Music selection
        let selectedMusicId = null;
        let currentAudio = null;

        function selectMusic(element, event) {
            event.stopPropagation();

            // Remove previous selection
            document.querySelectorAll('.music-item').forEach(item => {
                item.classList.remove('selected');
            });

            // Add selection to clicked item
            element.classList.add('selected');
            selectedMusicId = element.getAttribute('data-music-id');

            // Update hidden field for music
            document.getElementById('musique_id').value = selectedMusicId;
        }

        function playMusic(event, button) {
            event.preventDefault();
            event.stopPropagation();

            // Stop any currently playing audio
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
            }

            // Get audio URL from parent music item
            const musicItem = button.closest('.music-item');
            const audioUrl = musicItem.getAttribute('data-audio-url');

            if (audioUrl) {
                currentAudio = new Audio(audioUrl);

                // Change icon to pause
                const playIcon = button.querySelector('.play-icon');
                playIcon.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';

                currentAudio.play();

                // Reset icon when audio ends
                currentAudio.addEventListener('ended', function() {
                    playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
                });

                // Add pause functionality
                button.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (currentAudio.paused) {
                        currentAudio.play();
                        playIcon.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';
                    } else {
                        currentAudio.pause();
                        playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
                    }
                };
            }
        }

        function deleteMusic(event, button) {
            event.preventDefault();
            event.stopPropagation();

            const musicItem = button.closest('.music-item');
            const musicId = musicItem.getAttribute('data-music-id');

            if (confirm('Voulez-vous retirer cette musique de votre sélection ?')) {
                // Remove selection
                musicItem.classList.remove('selected');
                selectedMusicId = null;
                document.getElementById('musique_id').value = '';

                // Stop audio if playing
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio = null;
                }
            }
        }

        // Function for AJAX loaded music items
        function playMusicAjax(event, button) {
            event.preventDefault();
            event.stopPropagation();

            // Stop any currently playing audio
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                // Reset all play icons to play
                document.querySelectorAll('.btn-play-music .play-icon').forEach(icon => {
                    icon.innerHTML = '<path d="M8 5v14l11-7z"/>';
                });
            }

            // Get audio URL from parent music item
            const musicItem = button.closest('.music-item');
            const audioUrl = musicItem.getAttribute('data-audio-url');
            const playIcon = button.querySelector('.play-icon');

            if (audioUrl) {
                currentAudio = new Audio(audioUrl);

                // Change icon to pause
                playIcon.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';

                currentAudio.play();

                // Reset icon when audio ends
                currentAudio.addEventListener('ended', function() {
                    playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
                });

                // Add pause functionality
                button.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (currentAudio.paused) {
                        currentAudio.play();
                        playIcon.innerHTML = '<path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>';
                    } else {
                        currentAudio.pause();
                        playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
                    }
                };
            }
        }

        function deleteSelectedMusic(event, button) {
            event.preventDefault();
            event.stopPropagation();

            const musicItem = button.closest('.music-item');

            // Remove selection
            musicItem.classList.remove('selected');
            selectedMusicId = null;
            document.getElementById('musique_id').value = '';

            // Stop audio if playing
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
        }

        // Generate message function
        function generateMessage() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            const selectedCategory = document.querySelector('.category-item.active');

            if (!selectedCategory) {
                alert('Veuillez sélectionner une catégorie');
                return;
            }

            if (!title) {
                alert('Veuillez saisir un titre pour votre message');
                return;
            }

            // Update preview when button is clicked
            updatePreview();
        }

    </script>
</body>
</html>
