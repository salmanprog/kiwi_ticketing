@extends('dashboard.layouts.master')
@section('title', __('Email View'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <style>
        .email-view-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .modern-header {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            color: white;
            padding: 1.5rem 2rem;
            margin-bottom: 0;
        }

        .modern-header h3 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .breadcrumb-modern {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-modern a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        .breadcrumb-modern a:hover {
            color: white;
            text-decoration: underline;
        }

        .email-content-container {
            padding: 2rem;
            background: #f8f9fa;
            min-height: 400px;
        }

        .email-content-wrapper {
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .email-meta {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .meta-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .meta-value {
            font-size: 1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .email-body {
            padding: 2rem;
            line-height: 1.6;
            color: #2c3e50;
        }

        .email-body img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 1rem 0;
        }

        .email-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .email-body table, 
        .email-body th, 
        .email-body td {
            border: 1px solid #dee2e6;
        }

        .email-body th, 
        .email-body td {
            padding: 0.75rem;
            text-align: left;
        }

        .email-body th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .email-body h1, 
        .email-body h2, 
        .email-body h3, 
        .email-body h4, 
        .email-body h5, 
        .email-body h6 {
            color: #2c3e50;
            margin: 1.5rem 0 1rem 0;
        }

        .email-body p {
            margin-bottom: 1rem;
        }

        .email-body ul, 
        .email-body ol {
            margin: 1rem 0;
            padding-left: 2rem;
        }

        .email-body blockquote {
            border-left: 4px solid #A0C242;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #6c757d;
        }

        .action-buttons {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(160, 194, 66, 0.3);
            color: white;
        }

        .btn-modern-default {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-modern-default:hover {
            border-color: #A0C242;
            color: #A0C242;
            transform: translateY(-1px);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-sent {
            background: #d4edda;
            color: #155724;
            border: 1px solid rgba(21, 87, 36, 0.2);
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid rgba(114, 28, 36, 0.2);
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid rgba(133, 100, 4, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .email-meta {
                flex-direction: column;
                gap: 1rem;
            }
            
            .meta-item {
                flex: 1;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-modern-primary,
            .btn-modern-default {
                width: 100%;
                justify-content: center;
            }
            
            .email-body {
                padding: 1rem;
            }
            
            .email-content-container {
                padding: 1rem;
            }
        }

        /* Print Styles */
        @media print {
            .modern-header {
                background: #2c3e50 !important;
                color: white !important;
            }
            
            .action-buttons {
                display: none;
            }
            
            .email-content-container {
                background: white !important;
                padding: 0 !important;
            }
        }
    </style>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush

@section('content')
    <div class="padding">
        <div class="email-view-container">
            <!-- Header Section -->
            <div class="modern-header">
                <h3><i class="fas fa-envelope-open"></i> {{ __('Email View') }}</h3>
                <!-- <nav class="breadcrumb-modern">
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="{{ route('emailsLogs') }}">{{ __('Email Logs') }}</a> /
                    <a>{{__('view-email')}}</a> 
                </nav> -->
            </div>

            <!-- Email Content Section -->
            <div class="email-content-container">
                <div class="email-content-wrapper">
                    <!-- Email Meta Information -->
                    <div class="email-meta">
                        <div class="meta-item">
                            <span class="meta-label">Subject</span>
                            <span class="meta-value">{{ $email_content->subject ?? 'No Subject' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">To</span>
                            <span class="meta-value">{{ $email_content->email ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Identifier</span>
                            <span class="meta-value">{{ $email_content->identifier ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge {{ $email_content->status == 'sent' ? 'status-sent' : ($email_content->status == 'failed' ? 'status-failed' : 'status-pending') }}">
                                <i class="fas {{ $email_content->status == 'sent' ? 'fa-check' : ($email_content->status == 'failed' ? 'fa-times' : 'fa-clock') }}"></i>
                                {{ $email_content->status ?? 'unknown' }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Sent At</span>
                            <span class="meta-value">{{ $email_content->created_at ? $email_content->created_at->format('M d, Y H:i A') : 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Email Body Content -->
                    <div class="email-body">
                        @if($email_content->content)
                            {!! $email_content->content !!}
                        @else
                            <div style="text-align: center; padding: 3rem; color: #6c757d;">
                                <i class="fas fa-envelope-open-text" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <h3>No Email Content Available</h3>
                                <p>The email content could not be loaded or is empty.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="javascript:history.back()" class="btn-modern-default">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                </a>
                <!-- <button onclick="window.print()" class="btn-modern-default">
                    <i class="fas fa-print"></i> {{ __('Print') }}
                </button> -->
                <!-- <a href="{{ route('emailsLogs') }}" class="btn-modern-primary">
                    <i class="fas fa-list"></i> {{ __('View All Emails') }}
                </a> -->
            </div>
        </div>
    </div>
@endsection

@push("after-scripts")
    <script>
        $(document).ready(function () {
            // Enhance email content styling
            const emailBody = document.querySelector('.email-body');
            if (emailBody) {
                // Add responsive table wrapper
                const tables = emailBody.querySelectorAll('table');
                tables.forEach(table => {
                    const wrapper = document.createElement('div');
                    wrapper.style.overflowX = 'auto';
                    wrapper.style.margin = '1rem 0';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                });

                // Add styling to links
                const links = emailBody.querySelectorAll('a');
                links.forEach(link => {
                    link.style.color = '#A0C242';
                    link.style.textDecoration = 'none';
                    link.addEventListener('mouseenter', function() {
                        this.style.textDecoration = 'underline';
                    });
                    link.addEventListener('mouseleave', function() {
                        this.style.textDecoration = 'none';
                    });
                });

                // Add styling to images
                const images = emailBody.querySelectorAll('img');
                images.forEach(img => {
                    if (!img.style.maxWidth) {
                        img.style.maxWidth = '100%';
                    }
                    if (!img.style.height) {
                        img.style.height = 'auto';
                    }
                });
            }

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Escape key to go back
                if (e.key === 'Escape') {
                    history.back();
                }
                // Ctrl+P or Cmd+P for print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
            });

            // Add loading state for better UX
            const emailContent = document.querySelector('.email-body');
            if (emailContent && emailContent.innerHTML.trim() === '') {
                emailContent.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #6c757d;">
                        <div class="loading-spinner" style="width: 40px; height: 40px; border: 3px solid #f1f3f4; border-top: 3px solid #A0C242; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                        <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
                        <div>Loading email content...</div>
                    </div>
                `;
            }
        });
    </script>
@endpush