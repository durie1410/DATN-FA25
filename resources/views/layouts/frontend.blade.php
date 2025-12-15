<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thư Viện Online')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/homepage.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/waka-homepage.css') }}" rel="stylesheet">
    @stack('styles')
    
    <!-- Ultra Modern Homepage CSS -->
    <style>
        /* Ultra Modern Hero Section */
        .hero-ultra-modern {
            background: #000000;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-background-ultra {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        /* Ultra Gradient Orbs */
        .gradient-orb-ultra {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            animation: floatUltra 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            bottom: 20%;
            left: 30%;
            animation-delay: 4s;
        }

        .orb-4 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            top: 30%;
            right: 50%;
            animation-delay: 6s;
        }

        @keyframes floatUltra {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
            }
            25% { 
                transform: translateY(-30px) rotate(90deg) scale(1.1); 
            }
            50% { 
                transform: translateY(-60px) rotate(180deg) scale(0.9); 
            }
            75% { 
                transform: translateY(-30px) rotate(270deg) scale(1.05); 
            }
        }

        /* Particle System */
        .particles-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: particleFloat 6s linear infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 6s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; animation-duration: 8s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; animation-duration: 7s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; animation-duration: 9s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; animation-duration: 6s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; animation-duration: 8s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 6s; animation-duration: 7s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 7s; animation-duration: 9s; }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* Floating Geometric Shapes */
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: shapeFloat 10s ease-in-out infinite;
        }

        .shape-1 {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            border-radius: 20px;
            top: 20%;
            left: 80%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #43e97b, #38f9d7);
            border-radius: 50%;
            top: 60%;
            left: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #f093fb, #f5576c);
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            top: 40%;
            right: 20%;
            animation-delay: 4s;
        }

        .shape-4 {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #ffecd2, #fcb69f);
            transform: rotate(45deg);
            top: 80%;
            right: 40%;
            animation-delay: 6s;
        }

        .shape-5 {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            border-radius: 10px;
            top: 10%;
            right: 60%;
            animation-delay: 8s;
        }

        @keyframes shapeFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* Ultra Hero Content */
        .hero-content-ultra {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero-badge-ultra {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 12px 24px;
            margin-bottom: 30px;
            font-size: 1rem;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .badge-icon {
            margin-right: 10px;
            font-size: 1.2rem;
            animation: pulse 2s infinite;
        }

        .badge-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: badgeShimmer 3s infinite;
        }

        @keyframes badgeShimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        /* Ultra Hero Title */
        .hero-title-ultra {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 30px;
            text-align: left;
        }

        .title-line-1, .title-line-2, .title-line-3 {
            display: block;
            margin-bottom: 10px;
        }

        .text-gradient-ultra {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }

        .text-3d {
            color: white;
            text-shadow: 
                0 1px 0 #ccc,
                0 2px 0 #c9c9c9,
                0 3px 0 #bbb,
                0 4px 0 #b9b9b9,
                0 5px 0 #aaa,
                0 6px 1px rgba(0,0,0,.1),
                0 0 5px rgba(0,0,0,.1),
                0 1px 3px rgba(0,0,0,.3),
                0 3px 5px rgba(0,0,0,.2),
                0 5px 10px rgba(0,0,0,.25),
                0 10px 10px rgba(0,0,0,.2),
                0 20px 20px rgba(0,0,0,.15);
            animation: text3D 4s ease-in-out infinite;
        }

        .text-neon {
            color: #00f2fe;
            text-shadow: 
                0 0 5px #00f2fe,
                0 0 10px #00f2fe,
                0 0 15px #00f2fe,
                0 0 20px #00f2fe,
                0 0 35px #00f2fe,
                0 0 40px #00f2fe;
            animation: neonPulse 2s ease-in-out infinite alternate;
        }

        .text-animated {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textWave 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes text3D {
            0%, 100% { transform: perspective(400px) rotateX(0deg); }
            50% { transform: perspective(400px) rotateX(10deg); }
        }

        @keyframes neonPulse {
            0% { text-shadow: 0 0 5px #00f2fe, 0 0 10px #00f2fe, 0 0 15px #00f2fe; }
            100% { text-shadow: 0 0 10px #00f2fe, 0 0 20px #00f2fe, 0 0 30px #00f2fe; }
        }

        @keyframes textWave {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        /* Ultra Hero Description */
        .hero-description-ultra {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .description-highlight {
            color: #4facfe;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
        }

        .description-glow {
            color: #43e97b;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(67, 233, 123, 0.5);
        }

        /* Ultra Hero Stats */
        .hero-stats-ultra {
            display: flex;
            gap: 40px;
            margin-bottom: 50px;
        }

        .stat-item-ultra {
            text-align: center;
            position: relative;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .stat-item-ultra:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .stat-icon-ultra {
            position: relative;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
            color: white;
        }

        .icon-glow {
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            opacity: 0.3;
            animation: iconPulse 2s ease-in-out infinite;
        }

        .stat-number-ultra {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label-ultra {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 0 0 20px 20px;
            animation: progressFill 3s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.6; }
        }

        @keyframes progressFill {
            0% { width: 0%; }
            50% { width: 100%; }
            100% { width: 0%; }
        }

        /* Ultra Hero Actions */
        .hero-actions-ultra {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-ultra {
            position: relative;
            padding: 18px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: none;
            cursor: pointer;
        }

        .btn-primary-ultra {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 8px 32px rgba(79, 172, 254, 0.3);
        }

        .btn-secondary-ultra {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: 0 8px 32px rgba(255, 255, 255, 0.1);
        }

        .btn-accent-ultra {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            box-shadow: 0 8px 32px rgba(67, 233, 123, 0.3);
        }

        .btn-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-ultra:hover .btn-glow {
            left: 100%;
        }

        .btn-ultra:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .btn-ripple {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-ultra:active .btn-ripple {
            width: 300px;
            height: 300px;
        }

        /* Ultra Hero Visual */
        .hero-visual-ultra {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .books-3d-container {
            position: relative;
            width: 500px;
            height: 500px;
            perspective: 1000px;
        }

        .book-3d {
            position: absolute;
            width: 80px;
            height: 120px;
            transform-style: preserve-3d;
            animation: bookFloat3D 6s ease-in-out infinite;
        }

        .book-1 { top: 20%; left: 20%; animation-delay: 0s; }
        .book-2 { top: 40%; right: 20%; animation-delay: 1.5s; }
        .book-3 { bottom: 30%; left: 40%; animation-delay: 3s; }
        .book-4 { top: 60%; right: 40%; animation-delay: 4.5s; }

        .book-cover {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateZ(5px);
        }

        .book-pages {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            border-radius: 8px;
            transform: translateZ(-5px);
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .book-shadow {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 20px;
            background: radial-gradient(ellipse, rgba(0, 0, 0, 0.3), transparent);
            border-radius: 50%;
        }

        @keyframes bookFloat3D {
            0%, 100% { transform: rotateY(0deg) rotateX(0deg) translateY(0px); }
            25% { transform: rotateY(90deg) rotateX(10deg) translateY(-20px); }
            50% { transform: rotateY(180deg) rotateX(0deg) translateY(-40px); }
            75% { transform: rotateY(270deg) rotateX(-10deg) translateY(-20px); }
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            animation: floatingIcon 4s ease-in-out infinite;
        }

        .icon-1 { top: 15%; left: 10%; animation-delay: 0s; }
        .icon-2 { top: 25%; right: 15%; animation-delay: 1s; }
        .icon-3 { bottom: 25%; left: 15%; animation-delay: 2s; }
        .icon-4 { bottom: 15%; right: 10%; animation-delay: 3s; }

        @keyframes floatingIcon {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title-ultra { font-size: 2.5rem; }
            .hero-stats-ultra { flex-direction: column; gap: 20px; }
            .hero-actions-ultra { flex-direction: column; gap: 15px; }
            .books-3d-container { width: 300px; height: 300px; }
            .book-3d { width: 60px; height: 90px; }
        }
    </style>
    
    <!-- Fallback CSS for Modern Homepage -->
    <style>
        /* Modern Homepage Fallback CSS */
        .hero-modern {
            background: #000000;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }
        
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }
        
        .orb-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .orb-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }
        
        .orb-3 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            bottom: 20%;
            left: 30%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 8px 20px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .highlight-text {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-description {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 500;
        }
        
        .hero-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
            color: white;
        }
        
        .btn-secondary-modern {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-secondary-modern:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
        }
        
        /* Search Section - Enhanced Modern Design */
        .search-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            padding: 50px 40px;
            margin: 60px 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .search-modern::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.05), transparent);
            animation: shimmer 3s infinite;
            pointer-events: none;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .search-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
        }
        
        .search-subtitle {
            color: #6c757d;
            margin-bottom: 40px;
            font-size: 1.1rem;
            text-align: center;
            font-weight: 400;
        }
        
        .search-form {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: end;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .search-input-group {
            flex: 1;
            min-width: 400px;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 20px 60px 20px 30px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 8px 30px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        .search-input::placeholder {
            color: #a0aec0;
            font-weight: 400;
        }
        
        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.2rem;
            pointer-events: none;
        }
        
        .voice-icon {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            color: #4facfe;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .voice-icon:hover {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }
        
        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 20px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }
        
        .search-btn:active {
            transform: translateY(-1px);
        }
        
        .search-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .search-btn:hover::before {
            left: 100%;
        }
        
        .category-dropdown {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding: 20px 30px;
            font-size: 1rem;
            font-weight: 500;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
        }
        
        .category-dropdown:hover {
            border-color: #667eea;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.1);
        }
        
        .category-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .category-tag {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .category-tag:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }
        
        /* Featured Section - Enhanced */
        .featured-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            padding: 50px 40px;
            margin: 40px 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .featured-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2d3748;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .featured-title i {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.8rem;
        }
        
        .featured-description {
            color: #6c757d;
            margin-bottom: 40px;
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .feature-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2d3748;
        }
        
        .feature-card-description {
            color: #6c757d;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        /* Books Showcase - Enhanced Modern Design */
        .books-showcase {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            padding: 50px 40px;
            margin: 40px 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .books-showcase::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(79, 172, 254, 0.05), transparent);
            animation: shimmer-reverse 4s infinite;
            pointer-events: none;
        }
        
        @keyframes shimmer-reverse {
            0% { transform: translateX(100%) translateY(100%) rotate(-45deg); }
            100% { transform: translateX(-100%) translateY(-100%) rotate(-45deg); }
        }
        
        .books-showcase-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2d3748;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .books-showcase-title i {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.8rem;
        }
        
        .books-showcase-subtitle {
            color: #6c757d;
            margin-bottom: 40px;
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .book-card-modern {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .book-card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .book-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .book-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            border-radius: 20px 20px 0 0;
        }
        
        .book-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .book-card-modern:hover .book-image-container img {
            transform: scale(1.05);
        }
        
        .book-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
                opacity: 0;
            transition: all 0.3s ease;
            }
        
        .book-card-modern:hover .book-overlay {
                opacity: 1;
        }
        
        .quick-view-btn, .borrow-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            color: #667eea;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .quick-view-btn:hover, .borrow-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .borrow-btn.available {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .borrow-btn.borrowed {
            background: rgba(108, 117, 125, 0.8);
            color: white;
            cursor: not-allowed;
        }
        
        .borrow-btn.unavailable {
            background: rgba(220, 53, 69, 0.8);
            color: white;
            cursor: not-allowed;
        }
        
        .borrow-btn.login-required {
            background: rgba(255, 193, 7, 0.9);
            color: #212529;
        }
        
        .book-info-modern {
            padding: 25px;
        }
        
        .book-title-modern {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2d3748;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-author-modern {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .book-meta-modern {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .book-category-modern, .book-availability {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .book-category-modern i {
            color: #4facfe;
        }
        
        .book-availability i {
            color: #28a745;
        }
        
        .view-all-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
            margin-top: 30px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .view-all-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }
        
        .view-all-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .view-all-btn:hover::before {
            left: 100%;
        }
        
        /* Categories Section - Enhanced Modern Design */
        .categories-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            padding: 50px 40px;
            margin: 40px 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .categories-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(67, 233, 123, 0.05), transparent);
            animation: shimmer-categories 5s infinite;
            pointer-events: none;
        }
        
        @keyframes shimmer-categories {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .categories-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2d3748;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .categories-title i {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.8rem;
        }
        
        .categories-subtitle {
            color: #6c757d;
            margin-bottom: 40px;
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }
        
        .category-card-modern {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        
        .category-card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .category-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .category-icon-modern {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }
        
        .category-card-modern:hover .category-icon-modern {
            transform: scale(1.1) rotate(5deg);
        }
        
        .category-icon-modern i {
            font-size: 2rem;
            color: white;
        }
        
        .category-name-modern {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2d3748;
            text-align: center;
        }
        
        .category-stats-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .book-count-modern {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid rgba(67, 233, 123, 0.2);
        }
        
        .explore-category-btn {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(67, 233, 123, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .explore-category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(67, 233, 123, 0.4);
        }
        
        .explore-category-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .explore-category-btn:hover::before {
            left: 100%;
        }
        
        .category-description {
            color: #6c757d;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .search-modern, .featured-section, .books-showcase, .categories-section {
                padding: 30px 20px;
                margin: 30px 0;
            }
            
            .search-title, .books-showcase-title, .categories-title {
                font-size: 2rem;
            }
            
            .search-form {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-input-group {
                min-width: 100%;
            }
            
            .search-input {
                padding: 15px 50px 15px 20px;
                font-size: 1rem;
            }
            
            .search-btn {
                padding: 15px 30px;
                font-size: 1rem;
            }
            
            .feature-cards, .books-grid, .categories-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .category-tags {
                gap: 10px;
            }
            
            .category-tag {
                padding: 8px 15px;
                font-size: 0.8rem;
            }
            
            .book-image-container {
                height: 180px;
            }
            
            .book-info-modern {
                padding: 20px;
            }
            
            .category-card-modern {
                padding: 25px;
            }
            
            .category-icon-modern {
                width: 70px;
                height: 70px;
            }
            
            .category-icon-modern i {
                font-size: 1.8rem;
            }
        }
    </style>
    <style>
        /* Modern Navigation */
        .modern-navbar {
            background: #000000;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        
        .modern-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .modern-navbar .nav-link {
            font-weight: 500;
            padding: 0.75rem 1rem !important;
            border-radius: 12px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9) !important;
            position: relative;
        }
        
        .modern-navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            color: white !important;
        }
        
        .modern-navbar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }
        
        .modern-navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ffd700, #ffed4e);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 1px;
        }
        
        .modern-navbar .nav-link:hover::after,
        .modern-navbar .nav-link.active::after {
            width: 80%;
        }
        
        .modern-navbar .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }
        
        .modern-navbar .dropdown-item {
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.3s ease;
        }
        
        .modern-navbar .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <!-- Navigation - Commented out -->
    <!--
    <nav class="navbar navbar-expand-lg navbar-dark modern-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book"></i> Thư Viện Online
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.public') }}">
                            <i class="fas fa-book"></i> Danh sách sách
                        </a>
                    </li>
                    {{-- Link trang sách có thể mua đã bị xóa
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchasable-books.*') ? 'active' : '' }}" href="{{ route('purchasable-books.index') }}">
                            <i class="fas fa-shopping-cart"></i> Mua sách
                        </a>
                    </li>
                    --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="fas fa-tags"></i> Thể loại
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register.reader.form') }}">
                                <i class="fas fa-book-reader"></i> Đăng ký độc giả
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    -->

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer removed for custom page-specific footers -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Ultra Modern Homepage JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Ultra Modern Homepage Loading...');
            
            // Initialize ultra modern homepage features
            initializeUltraModernHomepage();
            
            // Ultra Counter animation with easing
            function animateUltraCounters() {
                const counters = document.querySelectorAll('.stat-number-ultra');
                
                counters.forEach((counter, index) => {
                    const target = parseInt(counter.getAttribute('data-count'));
                    if (isNaN(target)) return;
                    
                    // Add delay for staggered animation
                    setTimeout(() => {
                        const duration = 2500;
                        const startTime = Date.now();
                        
                        const animate = () => {
                            const elapsed = Date.now() - startTime;
                            const progress = Math.min(elapsed / duration, 1);
                            
                            // Easing function (ease-out)
                            const easeOut = 1 - Math.pow(1 - progress, 3);
                            const current = Math.floor(target * easeOut);
                            
                            counter.textContent = current;
                            
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                counter.textContent = target;
                                // Add completion effect
                                counter.style.transform = 'scale(1.1)';
                                setTimeout(() => {
                                    counter.style.transform = 'scale(1)';
                                }, 200);
                            }
                        };
                        
                        animate();
                    }, index * 200);
                });
            }
            
            // Initialize particle system
            function initializeParticles() {
                const particlesContainer = document.querySelector('.particles-container');
                if (!particlesContainer) return;
                
                // Create additional particles dynamically
                for (let i = 0; i < 12; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 6 + 's';
                    particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                    particlesContainer.appendChild(particle);
                }
            }
            
            // Initialize floating elements interaction
            function initializeFloatingElements() {
                const floatingIcons = document.querySelectorAll('.floating-icon');
                
                floatingIcons.forEach(icon => {
                    icon.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-40px) rotate(360deg) scale(1.2)';
                        this.style.opacity = '1';
                    });
                    
                    icon.addEventListener('mouseleave', function() {
                        this.style.transform = '';
                        this.style.opacity = '0.7';
                    });
                });
            }
            
            // Initialize 3D book interactions
            function initialize3DBooks() {
                const books3D = document.querySelectorAll('.book-3d');
                
                books3D.forEach(book => {
                    book.addEventListener('mouseenter', function() {
                        this.style.animationPlayState = 'paused';
                        this.style.transform = 'rotateY(0deg) rotateX(0deg) translateY(-20px) scale(1.1)';
                    });
                    
                    book.addEventListener('mouseleave', function() {
                        this.style.animationPlayState = 'running';
                        this.style.transform = '';
                    });
                });
            }
            
            // Initialize ultra button effects
            function initializeUltraButtons() {
                const ultraButtons = document.querySelectorAll('.btn-ultra');
                
                ultraButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        // Create ripple effect
                        const ripple = this.querySelector('.btn-ripple');
                        if (ripple) {
                            ripple.style.width = '300px';
                            ripple.style.height = '300px';
                            setTimeout(() => {
                                ripple.style.width = '0';
                                ripple.style.height = '0';
                            }, 600);
                        }
                        
                        // Add click animation
                        this.style.transform = 'translateY(-2px) scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    });
                });
            }
            
            // Initialize all ultra modern features
            setTimeout(() => {
                animateUltraCounters();
                initializeParticles();
                initializeFloatingElements();
                initialize3DBooks();
                initializeUltraButtons();
            }, 500);
            
            // Smooth scroll for buttons
            function scrollToSearch() {
                const searchSection = document.querySelector('#search-section');
                if (searchSection) {
                    searchSection.scrollIntoView({ behavior: 'smooth' });
                }
            }
            
            // Add click handlers
            const searchButtons = document.querySelectorAll('[onclick="scrollToSearch()"]');
            searchButtons.forEach(btn => {
                btn.addEventListener('click', scrollToSearch);
            });
            
            // Search by category function
            window.searchByCategory = function(categoryId) {
                const form = document.querySelector('.search-form');
                const categorySelect = form.querySelector('select[name="category_id"]');
                categorySelect.value = categoryId;
                form.submit();
            };
            
            // View book details functions
            window.viewBookDetails = function(bookId) {
                window.location.href = '/books/' + bookId;
            };
            
            window.viewPurchaseBookDetails = function(bookId) {
                window.location.href = '/purchasable-books/' + bookId;
            };
            
            console.log('Ultra Modern Homepage Loaded Successfully!');
        });
        
        // Initialize ultra modern homepage function
        function initializeUltraModernHomepage() {
            console.log('Initializing Ultra Modern Homepage...');
            
            // Add loading animation
            const heroSection = document.querySelector('.hero-ultra-modern');
            if (heroSection) {
                heroSection.style.opacity = '0';
                heroSection.style.transform = 'translateY(50px)';
                
                setTimeout(() => {
                    heroSection.style.transition = 'all 1s ease-out';
                    heroSection.style.opacity = '1';
                    heroSection.style.transform = 'translateY(0)';
                }, 100);
            }
        }
    </script>
    
    @yield('scripts')
    
    <style>
        /* Ripple effect */
        .btn-primary-modern, .btn-secondary-modern {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
    
    <script>
        // Scroll to top when page loads
        $(document).ready(function() {
            // Scroll to top immediately when page loads
            window.scrollTo(0, 0);
            
            // Also scroll to top after any form submission or page refresh
            $(window).on('beforeunload', function() {
                window.scrollTo(0, 0);
            });
            
            // Scroll to top when form is submitted
            $('form').on('submit', function() {
                setTimeout(function() {
                    window.scrollTo(0, 0);
                }, 100);
            });
            
            // Scroll to top when clicking any link that might reload the page
            $('a[href]').on('click', function() {
                setTimeout(function() {
                    window.scrollTo(0, 0);
                }, 100);
            });
        });
        
        // Additional scroll to top for page refresh
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
        });
        
        // Scroll to top when page is shown (for back/forward navigation)
        window.addEventListener('pageshow', function() {
            window.scrollTo(0, 0);
        });
        
    </script>