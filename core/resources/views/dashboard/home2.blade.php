@extends('dashboard.layouts.master')
@section('title', Helper::GeneralSiteSettings('site_title_' . @Helper::currentLanguage()->code))
@push('after-styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #A0C242;
            --text-dark: #111;
            --text-light: #6B7280;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-container {
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        .welcome-card {
            background: #fff;
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(160, 194, 66, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(160, 194, 66, 0.05), transparent);
            transform: rotate(45deg);
            animation: shine 6s infinite linear;
        }

        @keyframes shine {
            0% { transform: rotate(45deg) translateX(-100%); }
            100% { transform: rotate(45deg) translateX(100%); }
        }

        .welcome-icon {
            font-size: 80px;
            color: var(--primary-green);
            margin-bottom: 30px;
            display: inline-block;
        }

        .welcome-title {
            font-size: 42px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .welcome-subtitle {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.6;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .user-name {
            color: var(--primary-green);
            background: rgba(160, 194, 66, 0.1);
            padding: 4px 12px;
            border-radius: 8px;
            display: inline-block;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(160, 194, 66, 0.1);
            color: var(--primary-green);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 30px;
            border: 1px solid rgba(160, 194, 66, 0.2);
        }

        .welcome-features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(160, 194, 66, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(160, 194, 66, 0.1);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            background: rgba(160, 194, 66, 0.1);
            box-shadow: 0 5px 15px rgba(160, 194, 66, 0.2);
        }

        .feature-icon {
            color: var(--primary-green);
            font-size: 16px;
        }

        .feature-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            opacity: 0.1;
            color: var(--primary-green);
            font-size: 24px;
            animation: float 8s infinite ease-in-out;
        }

        .floating-element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 60%; left: 85%; animation-delay: 1s; }
        .floating-element:nth-child(3) { top: 80%; left: 15%; animation-delay: 2s; }
        .floating-element:nth-child(4) { top: 30%; left: 80%; animation-delay: 3s; }
        .floating-element:nth-child(5) { top: 70%; left: 70%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .get-started-btn {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3);
        }

        .get-started-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(160, 194, 66, 0.4);
            background: #8AAE38;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 20px;
            }
            
            .welcome-card {
                padding: 40px 25px;
            }
            
            .welcome-title {
                font-size: 32px;
            }
            
            .welcome-subtitle {
                font-size: 16px;
            }
            
            .welcome-features {
                gap: 15px;
            }
            
            .feature-item {
                padding: 10px 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="welcome-container">
        <div class="welcome-card animate__animated animate__fadeIn">
            <!-- Floating Background Elements -->
            <div class="floating-elements">
                <div class="floating-element"><i class="fas fa-chart-line"></i></div>
                <div class="floating-element"><i class="fas fa-shopping-cart"></i></div>
                <div class="floating-element"><i class="fas fa-users"></i></div>
                <div class="floating-element"><i class="fas fa-trophy"></i></div>
                <div class="floating-element"><i class="fas fa-rocket"></i></div>
            </div>
            
            <!-- Main Content -->
            <div class="welcome-icon animate__animated animate__bounceIn">
                <i class="fas fa-chart-line"></i>
            </div>
            
            <h1 class="welcome-title animate__animated animate__fadeInUp">
                Welcome to Your <span style="color: var(--primary-green);">Dashboard</span>
            </h1>
            
            <div class="date-badge animate__animated animate__fadeInUp animate__delay-1s">
                <i class="far fa-calendar me-2"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
            
            <p class="welcome-subtitle animate__animated animate__fadeInUp animate__delay-2s">
                Hello <span class="user-name">{{ Auth::user()->name }}</span>! We're excited to have you back. 
                Your dashboard is ready to help you manage your business efficiently. 
                Everything you need is just a click away.
            </p>
            
            <button class="get-started-btn animate__animated animate__fadeInUp animate__delay-3s" onclick="location.href='{{ route('transactionorders') }}'">
                <i class="fas fa-play-circle me-2"></i>
                Get Started
            </button>
            
            <div class="welcome-features animate__animated animate__fadeInUp animate__delay-4s">
                <div class="feature-item">
                    <i class="fas fa-bolt feature-icon"></i>
                    <span class="feature-text">Fast & Reliable</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <span class="feature-text">Secure</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-bar feature-icon"></i>
                    <span class="feature-text">Analytics</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add some interactive animations
            const welcomeCard = document.querySelector('.welcome-card');
            const getStartedBtn = document.querySelector('.get-started-btn');
            
            // Add hover effect to card
            welcomeCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            welcomeCard.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            
            // Add pulse animation to button on hover
            getStartedBtn.addEventListener('mouseenter', function() {
                this.classList.add('animate__pulse');
            });
            
            getStartedBtn.addEventListener('mouseleave', function() {
                this.classList.remove('animate__pulse');
            });
            
            // Animate feature items on scroll into view
            const featureItems = document.querySelectorAll('.feature-item');
            featureItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.2}s`;
            });
        });
    </script>
@endpush