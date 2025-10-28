<?php
// Current Full URL
$fullPagePath = Request::url();
use Illuminate\Support\Str;
    $current = Route::currentRouteName();
    $path = Request::path();
// Char Count of Backend folder Plus 1
$envAdminCharCount = strlen(config('smartend.backend_path')) + 1;
// URL after Root Path EX: admin/home
$urlAfterRoot = substr($fullPagePath, strpos($fullPagePath, config('smartend.backend_path')) + $envAdminCharCount);
$mnu_title_var = "title_" . @Helper::currentLanguage()->code;
$mnu_title_var2 = "title_" . config('smartend.default_language');
?>
<style>
/* Active State Green Theme */
.nav-active-primary .nav > li.active > a {
    background-color: rgba(160, 194, 66, 0.1) !important;
    border-left-color: #A0C242 !important;
    color: #A0C242 !important;
}

.nav-active-primary .nav > li.active > a .nav-icon i {
    color: #A0C242 !important;
}

.nav-sub > li.active > a {
    background-color: rgba(160, 194, 66, 0.1) !important;
    color: #A0C242 !important;
    border-left: 3px solid #A0C242 !important;
}

/* Hover effects */
.nav-active-primary .nav > li > a:hover {
    background-color: rgba(160, 194, 66, 0.05) !important;
    color: #A0C242 !important;
}
</style>
<div id="aside" class="app-aside modal fade folded md nav-expand">
    <div class="left navside white" layout="column" style="background: #ffffff; border-right: 1px solid #e9ecef;">

       <div class="navbar navbar-md no-radius" style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);">
            <a class="hidden-folded inline folded-toggle m-t p-t-xs pull-right">
                <i class="material-icons  md-24 opacity">&#xe5d2;</i>
            </a>
            <!-- brand -->
            <a class="navbar-brand" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/dashboard/images/favicon(kiwi).png') }}" alt="Control">
                <span class="hidden-folded text-white inline">{{ __('Kiwi Ticketing') }}</span>
            </a>
            <!-- / brand -->
        </div>
        
        
        <div flex class="hide-scroll">
            <nav class="scroll nav-active-primary" style="background: #ffffff;">

                @php
                    $userRole = Auth::user()->permissionsGroup->name ?? '';
                    $dataSections = explode(',', Auth::user()->permissionsGroup->data_sections);
                @endphp
                <ul class="nav" ui-nav>
                    <li class="nav-header hidden-folded">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('backend.main') }}</small>
                    </li>
                    <li {{ (Route::currentRouteName()=="adminHome") ? 'class=active' : '' }} style="border-left: 3px solid transparent;">
                        <a href="{{ route('adminHome') }}" style="color: #495057;">
                  <span class="nav-icon">
                    <i class="fas fa-chart-line" style="color: #A0C242;"></i>
                  </span>
                            <span class="nav-text">{{ __('backend.dashboard') }}</span>
                        </a>
                    </li>
                    
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('Tickets Management') }}</small>
                    </li>
                     
                    <?php
                        $currentFolder = "kabanasetting";
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        $cabanaActive = in_array($current, ['cabana','cabanaCreate','cabanaEdit','cabanaStore','cabanaUpdate','cabanaDestroy','kabanasetting', 'kabanaddons', 'kabanaorders', 'kabanaaddonEdit','kabanaordersdetail','cabanaAddonStore']);
                        $birthdayActive = in_array($current, ['birthdaypackages', 'birthdaypackagesCreate', 'birthdaypackagesEdit', 'birthdaypackagesStore','birthdaypackagesUpdate','birthdayorders','birthdayordersdetail','birthdayaddon','birthdayaddonEdit']);
                        $generalTicketActive = in_array($current, ['generaltickets','generalticketsCreate','generalticketsEdit','generalticketsStore','generalticketsaddon','generalticketsaddonCreate','generalticketsaddonEdit','generalticketscabana','generalticketscabanaCreate','generalticketscabanaStore','generalticketscabanaEdit','generalticketscabanaUpdate','generalticketscabanaDestroy','generalticketsorders','generalticketsordersdetail','generalticketpackages','generalticketpackagesEdit','generalticketpackagesCreate','generalticketpackagesStore','generalticketpackagesUpdate','generalticketpackagesDestroy']);
                        $seasonpassActive = in_array($current, ['seasonpass', 'seasonpassEdit', 'seasonpassCreate', 'seasonpassStore','seasonpassDestroy','seasonpassaddon','seasonpassaddonEdit','seasonpassaddonStore','seasonpassaddonCreate','seasonpassaddonUpdate','seasonpassorders','seasonpassordersdetail']);
                        $couponActive = in_array($current, ['coupon', 'couponCreate', 'couponEdit', 'couponStore','couponUpdate','couponDestroy']);
                        $offerCreationActive = in_array($current, ['offercreationpackages','offercreationpackagesEdit','offercreationpackagesCreate','offercreationpackagesStore','offercreationpackagesUpdate','offercreationpackagesDestroy','offeraddon','offeraddonCreate','offeraddonStore','offeraddonEdit','offeraddonUpdate','offeraddonDestroy','offercreationpackagesorders','offercreationpackagesordersdetail']);
                        $transactionActive = in_array($current, ['transactionorders','updatetransactionorders']);
                        $LogsActive = in_array($current, ['ordersLogs','ordersfailedLogs','paymentLogs','paymentfailLogs','ordersLogsShow']);
                        $emailTemplateActive = in_array($current, ['emailTemplate','emailTemplateCreate','emailTemplateEdit','emailTemplateStore','emailTemplateUpdate','emailTemplateDestroy','smtpConfigure','emailLogs']);
                    ?>
                    
                    <!-- Cabana Management -->
                    @if($userRole == 'Webmaster' || in_array(16, $dataSections))
                    <li class="{{ $cabanaActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-umbrella-beach" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Cabana Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'cabana' 
                                || Str::startsWith($current, 'cabana') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('cabana') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('All Cabana') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'kabanaddons' 
                                || Str::startsWith($current, 'kabanaaddonEdit') 
                                || Str::startsWith($current, 'kabanaddons') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('kabanaddons') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Cabana Addon') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'kabanaorders' 
                                || Str::startsWith($current, 'kabanaorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('kabanaorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Cabana Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Package Management -->
                    @if($userRole == 'Webmaster' || in_array(17, $dataSections))
                    <li class="{{ $birthdayActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-gift" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Package Management') }}</span>
                        </a>

                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'birthdaypackages' || 
                                Str::startsWith($current, 'birthdaypackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdaypackages') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('All Packages') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'birthdayaddon' || 
                                Str::startsWith($current, 'birthdayaddon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdayaddon') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Packages Addon') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'birthdayorders' || 
                                Str::startsWith($current, 'birthdayorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdayorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Packages Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Platform Management -->
                    @if($userRole == 'Webmaster' || in_array(18, $dataSections))
                    <li class="{{ $generalTicketActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-ticket-alt" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Platform Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'generaltickets' 
                                || Str::startsWith($current, 'generaltickets') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketpackages') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('General Packages') }}</span>
                                </a>
                            </li>

                           <li class="{{ 
                                $current === 'generalticketsaddon' 
                                || Str::startsWith($current, 'generalticketsaddon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketsaddon') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('General Package Addon') }}</span>
                                </a>
                            </li>

                             <li class="{{ 
                                $current === 'generalticketsorders' 
                                || Str::startsWith($current, 'generalticketsorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketsorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('General Tickets Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- SeasonPass Management -->
                    @if($userRole == 'Webmaster' || in_array(19, $dataSections))
                    <li class="{{ $seasonpassActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-star" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('SeasonPass Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpass') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('SeasonPass Sale') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpassaddon') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('SeasonPass Products') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpassorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('SeasonPass Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Offer Management -->
                    @if($userRole == 'Webmaster' || in_array(20, $dataSections))
                    <li class="{{ $offerCreationActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-tag" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Offer Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offercreationpackages') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Offers') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offeraddon') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Offer Addon') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offercreationpackagesorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Offers Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Coupon Management -->
                    @if($userRole == 'Webmaster' || in_array(21, $dataSections))
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('Coupon Management') }}</small>
                    </li>
                    
                    <li class="{{ $couponActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-ticket-alt" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Coupons Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'coupon' 
                                || Str::startsWith($current, 'coupon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('coupon') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('All Coupons') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Transactions -->
                    @if($userRole == 'Webmaster' || in_array(22, $dataSections))
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('Transactions') }}</small>
                    </li>
                    
                    <li class="{{ $transactionActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-dollar-sign" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Transactions') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'transactionorders' 
                                || Str::startsWith($current, 'transactionorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('transactionorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('All Transactions') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'updatetransactionorders' 
                                || Str::startsWith($current, 'updatetransactionorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('updatetransactionorders') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Update & Upgrade Transactions') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'ordersLogs' 
                                || Str::startsWith($current, 'ordersLogs') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Order Logs') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Logs Management -->
                    @if($userRole == 'Webmaster' || in_array(22, $dataSections))
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('Logs') }}</small>
                    </li>
                    
                    <li class="{{ $LogsActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-clipboard-list" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('All Logs') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'ordersLogs' 
                                || Str::startsWith($current, 'ordersLogs') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Order Success Logs') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'ordersfailedLogs' 
                                || Str::startsWith($current, 'ordersfailedLogs') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('ordersfailedLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Order Failed Logs') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'paymentLogs' 
                                || Str::startsWith($current, 'paymentLogs') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('paymentLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Payment Success Logs') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'paymentfailLogs' 
                                || Str::startsWith($current, 'paymentfailLogs') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('paymentfailLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Payment Failed Logs') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Email Management -->
                    @if($userRole == 'Webmaster')
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('Email Setting') }}</small>
                    </li>
                    
                    <li class="{{ $emailTemplateActive ? 'active' : '' }}" style="border-left: 3px solid transparent;">
                        <a style="color: #495057;">
                            <span class="nav-caret">
                                <i class="fa fa-caret-down" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-icon">
                                <i class="fas fa-envelope" style="color: #A0C242;"></i>
                            </span>
                            <span class="nav-text">{{ __('Email Management') }}</span>
                        </a>
                        <ul class="nav-sub" style="background: #f8f9fa;">
                            <li class="{{ 
                                $current === 'emailTemplate' 
                                || Str::startsWith($current, 'emailTemplate') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('emailTemplate') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Email Templates') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'emailTemplate' 
                                || Str::startsWith($current, 'emailTemplate') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('smtpConfigure') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('SMTP Configuration') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'emailTemplate' 
                                || Str::startsWith($current, 'emailTemplate') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('emailsLogs') }}" style="color: #495057;">
                                    <span class="nav-text">{{ __('Email Logs') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    
                    <!-- Settings Section -->
                    @if(@Auth::user()->permissionsGroup->roles_status || (Helper::GeneralWebmasterSettings("settings_status") && @Auth::user()->permissionsGroup->settings_status) || @Auth::user()->permissionsGroup->webmaster_status)
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted" style="color: #6c757d !important;">{{ __('backend.settings') }}</small>
                        </li>
                    @endif

                    <!-- Users & Permissions -->
                    @if(@Auth::user()->permissionsGroup->roles_status)
                        <?php
                        $currentFolder = "users"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} style="border-left: 3px solid transparent;">
                            <a href="{{ route('users') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-users" style="color: #A0C242;"></i>
                                </span>
                                <span class="nav-text">{{ __('backend.usersPermissions') }}</span>
                            </a>
                        </li>
                    @endif
                    
                    <!-- General Site Settings -->
                    @if(Helper::GeneralWebmasterSettings("settings_status"))
                        @if(@Auth::user()->permissionsGroup->settings_status)
                            <?php
                            $currentFolder = "settings"; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} style="border-left: 3px solid transparent;">
                                <a href="{{ route('settings') }}" style="color: #495057;">
                                <span class="nav-icon">
                                <i class="fas fa-cogs" style="color: #A0C242;"></i>
                                </span>
                                    <span class="nav-text">{{ __('backend.generalSiteSettings') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <!-- Webmaster Tools
                    @if(@Auth::user()->permissionsGroup->webmaster_status || @Auth::user()->permissionsGroup->modules_status)
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted" style="color: #6c757d !important;">{{ __('backend.webmasterTools') }}</small>
                        </li>
                    @endif -->

                    <!-- Modules Management -->
                    <!-- @if(@Auth::user()->permissionsGroup->modules_status)
                        <?php
                        $currentFolder = "modules"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} style="border-left: 3px solid transparent;">
                            <a href="{{ route('WebmasterSections') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-th-large" style="color: #A0C242;"></i>
                                </span>
                                <span class="nav-text">{{ __('backend.siteSectionsSettings') }}</span>
                            </a>
                        </li>
                    @endif -->

                    <!-- Webmaster Settings
                    @if(@Auth::user()->permissionsGroup->webmaster_status)
                        <?php
                        $currentFolder = "webmaster"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} style="border-left: 3px solid transparent;">
                            <a href="{{ route('webmasterSettings') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-sliders-h" style="color: #A0C242;"></i>
                                </span>
                                <span class="nav-text">{{ __('backend.generalSettings') }}</span>
                            </a>
                        </li>
                    @endif -->

                </ul>
            </nav>
        </div>
        <br>
    </div>
</div>