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
$mnu_title_var = 'title_' . @Helper::currentLanguage()->code;
$mnu_title_var2 = 'title_' . config('smartend.default_language');
?>
<style>
    /* Premium Green Theme with Modern Design */
    .nav-active-primary .nav>li.active>a {
        background: #9FC23F !important;
        border-left-color: #ffffff !important;
        color: #ffffff !important;
        border-radius: 12px !important;
        margin: 0px !important;
        position: relative;
        overflow: hidden;
    }

    .nav-active-primary .nav>li.active>a .nav-icon i {
        color: #ffffff !important;
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .nav-sub>li.active>a {
        color: #A0C242 !important;
        border-radius: 8px !important;
        margin: 2px 8px !important;
        font-weight: 600 !important;
        position: relative;
    }

    .nav-sub>li.active>a::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: #A0C242;
        border-radius: 50%;
    }

    /* Sabse last li item ko identify karo aur margin do */
    .nav-active-primary .nav>li:last-of-type {
        margin-bottom: 30px !important;
        padding-bottom: 10px !important;
    }

    /* Premium animations */
    .nav-active-primary .nav>li>a {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border-radius: 12px !important;
        margin: 4px 0px !important;
    }

    .nav-sub>li>a {
        transition: all 0.2s ease !important;
        border-radius: 8px !important;
        margin: 2px 8px !important;
    }

    /* Premium icons styling */
    .nav-active-primary .nav>li>a .nav-icon {
        transition: transform 0.3s ease !important;
    }

    .nav-active-primary .nav>li>a:hover .nav-icon {
        color: #6B7280;
    }

    /* Premium submenu styling */
    .nav-sub {
        background: #ffffff !important;
        border-radius: 0 0 12px 12px !important;
        margin: 0px !important;
        backdrop-filter: blur(10px);
    }

    /* Premium scrollbar */
    .nav-active-primary ::-webkit-scrollbar {
        width: 6px;
    }

    .nav-active-primary ::-webkit-scrollbar-track {
        background: rgba(160, 194, 66, 0.1);
        border-radius: 10px;
    }

    .nav-active-primary ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #9FC23F 0%, #8AAE38 100%);
        border-radius: 10px;
    }

    .nav-active-primary ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #8AAE38 0%, #7A9A32 100%);
    }

    td,
    td a {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    td:last-child {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
        max-width: none;
    }

    .app-aside.modal.fade.md.nav-expand.folded .bottom-nav {
        display: none;
    }

    .app-aside.modal.fade.md.nav-expand.folded .no-twice {
        display: none;
    }

    .app-aside.modal.fade.md.nav-expand .collapse-twice {
        display: none;
    }

    .app-aside.modal.fade.md.nav-expand.folded .collapse-twice {
        display: block;
    }

    span.nav-text.dashlogo::before {
        content: '';
        position: absolute;
        left: 1.85rem;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 4px;
        background-color: #6B7280;
        border-radius: 50px;
        transition: width 0.5s ease, background-color 0.5s ease;
    }

    span.nav-text.dashlogo:hover::before {
        background-color: #9FC23F;
        width: 16px;
    }

    /* span.nav-text.dashlogo:hover {
        color: #9FC23F;
    } */

    li.active.cust-act span.nav-text.dashlogo::before {
        background-color: #9FC23F;
        width: 16px;
    }

    /* Automatic dropdown open for active menu */
    .nav-active-primary .nav>li.active .nav-sub {
        display: block !important;
    }

    .nav-active-primary .nav>li.active .nav-caret i {
        transform: rotate(90deg);
    }

    /* Smooth caret rotation */
    .nav-caret i {
        transition: transform 0.3s ease;
    }

    /* Submenu headers */
    .nav-sub-header {
        padding: 8px 16px 4px 16px;
        margin-top: 8px;
        border-bottom: 1px solid #e9ecef;
    }

    .nav-sub-header:first-child {
        margin-top: 0;
    }

    .navside .nav li a:hover,
    .navside .nav li a:focus {
        color: #FFF !important;
    }

    .nav-icon {
        color: #6B7280;
    }

    .nav-active-primary .nav>li>a:hover i {
        color: #fff;
    }
</style>

<div id="aside" class="app-aside modal fade folded md nav-expand">
    <div class="left navside white" layout="column" style="background: #ffffff; border-right: 1px solid #e9ecef;">

        <div data-toggle="modal" data-target="#aside" class="navbar navbar-md no-radius for-mbl-noshow">
            <a class="hidden-folded inline folded-toggle m-t p-t-xs pull-right">
                <svg class="toggle-sidebar d-lg-block d-none" xmlns="http://www.w3.org/2000/svg" width="20"
                    height="20" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M20 3H4C2.89543 3 2 3.89543 2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5C22 3.89543 21.1046 3 20 3Z"
                        stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 3V21" stroke="#ccc" stroke-width="2"></path>
                </svg>
            </a>

            <div class="p-a-md text-center">
                @if (Helper::GeneralSiteSettings('style_logo_' . @Helper::currentLanguage()->code) != '')
                    <img alt="logo" class="no-twice"
                        src="{{ URL::to('uploads/settings/' . Helper::GeneralSiteSettings('style_logo_' . @Helper::currentLanguage()->code)) }}">
                @else
                    <img alt="" src="{{ URL::to('uploads/settings/nologo.png') }}">
                @endif

                <img class="collapse-twice"
                    src="https://wildrivers.com/wp-content/uploads/2023/12/wildrivers-apple-icon.png" alt=""
                    width="23px">
            </div>
        </div>

        <div class="navbar navbar-md no-radius for-dest-show">
            <a class="hidden-folded inline folded-toggle m-t p-t-xs pull-right">
                <svg class="toggle-sidebar d-lg-block d-none" xmlns="http://www.w3.org/2000/svg" width="20"
                    height="20" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M20 3H4C2.89543 3 2 3.89543 2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5C22 3.89543 21.1046 3 20 3Z"
                        stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 3V21" stroke="#ccc" stroke-width="2"></path>
                </svg>
            </a>

            <div class="p-a-md text-center">
                @if (Helper::GeneralSiteSettings('style_logo_' . @Helper::currentLanguage()->code) != '')
                    <img alt="logo" class="no-twice"
                        src="{{ URL::to('uploads/settings/' . Helper::GeneralSiteSettings('style_logo_' . @Helper::currentLanguage()->code)) }}">
                @else
                    <img alt="" src="{{ URL::to('uploads/settings/nologo.png') }}">
                @endif

                <img class="collapse-twice"
                    src="https://wildrivers.com/wp-content/uploads/2023/12/wildrivers-apple-icon.png" alt=""
                    width="23px">
            </div>
        </div>

        <div flex class="hide-scroll">
            <nav class="scroll nav-active-primary cust-pading-nav" style="background: #ffffff;">

                @php
                    $userRole = Auth::user()->permissionsGroup->name ?? '';
                    $dataSections = explode(',', Auth::user()->permissionsGroup->data_sections);

                    // Active states for new structure
                    $currentFolder = 'kabanasetting';
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    $cabanaActive = in_array($current, [
                        'cabana',
                        'cabanaCreate',
                        'cabanaEdit',
                        'cabanaStore',
                        'cabanaUpdate',
                        'cabanaDestroy',
                        'kabanasetting',
                        'kabanaddons',
                        'kabanaorders',
                        'kabanaaddonEdit',
                        'kabanaordersdetail',
                        'cabanaAddonStore',
                    ]);
                    $birthdayActive = in_array($current, [
                        'birthdaypackages',
                        'birthdaypackagesCreate',
                        'birthdaypackagesEdit',
                        'birthdaypackagesStore',
                        'birthdaypackagesUpdate',
                        'birthdayorders',
                        'birthdayordersdetail',
                        'birthdayaddon',
                        'birthdayaddonEdit',
                    ]);
                    $generalTicketActive = in_array($current, [
                        'generaltickets',
                        'generalticketsCreate',
                        'generalticketsEdit',
                        'generalticketsStore',
                        'generalticketsaddon',
                        'generalticketsaddonCreate',
                        'generalticketsaddonEdit',
                        'generalticketscabana',
                        'generalticketscabanaCreate',
                        'generalticketscabanaStore',
                        'generalticketscabanaEdit',
                        'generalticketscabanaUpdate',
                        'generalticketscabanaDestroy',
                        'generalticketsorders',
                        'generalticketsordersdetail',
                        'generalticketpackages',
                        'generalticketpackagesEdit',
                        'generalticketpackagesCreate',
                        'generalticketpackagesStore',
                        'generalticketpackagesUpdate',
                        'generalticketpackagesDestroy',
                    ]);
                    $seasonpassActive = in_array($current, [
                        'seasonpass',
                        'seasonpassEdit',
                        'seasonpassCreate',
                        'seasonpassStore',
                        'seasonpassDestroy',
                        'seasonpassaddon',
                        'seasonpassaddonEdit',
                        'seasonpassaddonStore',
                        'seasonpassaddonCreate',
                        'seasonpassaddonUpdate',
                        'seasonpassorders',
                        'seasonpassordersdetail',
                    ]);
                    $couponActive = in_array($current, [
                        'coupon',
                        'couponCreate',
                        'couponEdit',
                        'couponStore',
                        'couponUpdate',
                        'couponDestroy',
                    ]);
                    $offerCreationActive = in_array($current, [
                        'offercreationpackages',
                        'offercreationpackagesEdit',
                        'offercreationpackagesCreate',
                        'offercreationpackagesStore',
                        'offercreationpackagesUpdate',
                        'offercreationpackagesDestroy',
                        'offeraddon',
                        'offeraddonCreate',
                        'offeraddonStore',
                        'offeraddonEdit',
                        'offeraddonUpdate',
                        'offeraddonDestroy',
                        'offercreationpackagesorders',
                        'offercreationpackagesordersdetail',
                    ]);
                    $transactionActive = in_array($current, ['transactionorders', 'updatetransactionorders']);
                    $LogsActive = in_array($current, [
                        'ordersLogs',
                        'ordersfailedLogs',
                        'paymentLogs',
                        'paymentfailLogs',
                        'ordersLogsShow',
                    ]);
                    $emailTemplateActive = in_array($current, [
                        'emailTemplate',
                        'emailTemplateCreate',
                        'emailTemplateEdit',
                        'emailTemplateStore',
                        'emailTemplateUpdate',
                        'emailTemplateDestroy',
                        'smtpConfigure',
                        'emailLogs',
                    ]);

                    // New combined active states
                    $ticketPassActive = $generalTicketActive || $cabanaActive || $seasonpassActive || $birthdayActive;
                    $settingsActive =
                        $LogsActive ||
                        $emailTemplateActive ||
                        $PathCurrentFolder == 'users' ||
                        $PathCurrentFolder == 'settings';
                @endphp

                <ul class="nav" ui-nav>

                    <!-- Ticket / Pass Management -->
                    @if ($userRole == 'Webmaster' || in_array(18, $dataSections))
                        <li class="{{ $ticketPassActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-ticket-alt" style=""></i>
                                </span>
                                <span class="nav-text">{{ __('Ticket / Pass Management') }}</span>
                            </a>
                            <ul class="nav-sub"
                                style="background: #f8f9fa; display: {{ $ticketPassActive ? 'block' : 'none' }};">

                                <!-- Tickets -->
                                <li class="nav-sub-header">
                                    <span class="nav-text"
                                        style="color: #6c757d; font-size: 12px; font-weight: 600;">{{ __('Tickets') }}</span>
                                </li>
                                <li class="{{ $current === 'generalticketpackages' ? 'active cust-act' : '' }}">
                                    <a href="{{ route('generalticketpackages') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Ticket Types') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $current === 'generalticketsaddon' ? 'active cust-act' : '' }}">
                                    <a href="{{ route('generalticketsaddon') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                    </a>
                                </li>

                                <!-- Cabanas -->
                                <li class="nav-sub-header">
                                    <span class="nav-text"
                                        style="color: #6c757d; font-size: 12px; font-weight: 600;">{{ __('Cabanas') }}</span>
                                </li>
                                <li
                                    class="{{ $current === 'cabana' || Str::startsWith($current, 'cabana') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('cabana') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Cabana Types') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ $current === 'kabanaddons' || Str::startsWith($current, 'kabanaaddonEdit') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('kabanaddons') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                    </a>
                                </li>

                                <!-- Season Passes -->
                                <li class="nav-sub-header">
                                    <span class="nav-text"
                                        style="color: #6c757d; font-size: 12px; font-weight: 600;">{{ __('Season Passes') }}</span>
                                </li>
                                <li class="{{ $current === 'seasonpass' ? 'active cust-act' : '' }}">
                                    <a href="{{ route('seasonpass') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Season Pass Types') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $current === 'seasonpassaddon' ? 'active cust-act' : '' }}">
                                    <a href="{{ route('seasonpassaddon') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                    </a>
                                </li>

                                <!-- Packages / Birthdays -->
                                <li class="nav-sub-header">
                                    <span class="nav-text"
                                        style="color: #6c757d; font-size: 12px; font-weight: 600;">{{ __('Packages / Birthdays') }}</span>
                                </li>
                                <li
                                    class="{{ $current === 'birthdaypackages' || Str::startsWith($current, 'birthdaypackages') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('birthdaypackages') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Package Types') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ $current === 'birthdayaddon' || Str::startsWith($current, 'birthdayaddon') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('birthdayaddon') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Sale Management -->
                    @if ($userRole == 'Webmaster' || in_array(20, $dataSections))
                        <li class="{{ $offerCreationActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-tag" style=""></i>
                                </span>
                                <span class="nav-text">{{ __('Sale Management') }}</span>
                            </a>
                            <ul class="nav-sub"
                                style="background: #f8f9fa; display: {{ $offerCreationActive ? 'block' : 'none' }};">
                                <li class="{{ $current === 'offercreationpackages' ? 'active cust-act' : '' }}">
                                    <a href="{{ route('offercreationpackages') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Sale Type') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ $current === 'offeraddon' || Str::startsWith($current, 'offeraddon') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('offeraddon') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Coupon Management -->
                    @if ($userRole == 'Webmaster' || in_array(21, $dataSections))
                        <li class="{{ $couponActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-ticket-alt" style=""></i>
                                </span>
                                <span class="nav-text">{{ __('Coupon Management') }}</span>
                            </a>
                            <ul class="nav-sub"
                                style="background: #f8f9fa; display: {{ $couponActive ? 'block' : 'none' }};">
                                <li
                                    class="{{ $current === 'coupon' || Str::startsWith($current, 'coupon') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('coupon') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Coupon Types') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Order Details/Transaction -->
                    @if ($userRole == 'Webmaster' || in_array(22, $dataSections))
                        <li class="{{ $transactionActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a href="{{ route('transactionorders') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-file-invoice-dollar" style=""></i>
                                </span>
                                <span class="nav-text">{{ __('Order Details/Transaction') }}</span>
                            </a>
                        </li>
                    @endif

                    <!-- Settings -->
                    @if ($userRole == 'Webmaster')
                        <li class="{{ $settingsActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-cogs" style=""></i>
                                </span>
                                <span class="nav-text">{{ __('Settings') }}</span>
                            </a>
                            <ul class="nav-sub"
                                style="background: #f8f9fa; display: {{ $settingsActive ? 'block' : 'none' }};">
                                <li
                                    class="{{ $current === 'ordersLogs' || Str::startsWith($current, 'ordersLogs') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Order Logs') }}</span>
                                    </a>
                                </li>

                                @if (@Auth::user()->permissionsGroup->roles_status)
                                    <li {{ $PathCurrentFolder == 'users' ? 'class=active cust-act' : '' }}>
                                        <a href="{{ route('users') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('User Permission') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li
                                    class="{{ $current === 'emailTemplate' || Str::startsWith($current, 'emailTemplate') ? 'active cust-act' : '' }}">
                                    <a href="{{ route('emailTemplate') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('Email Management') }}</span>
                                    </a>
                                </li>

                                <li class="{{ $LogsActive ? 'active cust-act' : '' }}">
                                    <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                        <span class="nav-text dashlogo">{{ __('All Logs') }}</span>
                                    </a>
                                </li>

                                @if (Helper::GeneralWebmasterSettings('settings_status') && @Auth::user()->permissionsGroup->settings_status)
                                    <li {{ $PathCurrentFolder == 'settings' ? 'class=active cust-act' : '' }}>
                                        <a href="{{ route('settings') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Website Setting') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
        <br>
        <div class="navbar navbar-md no-radius" style="display: flex;justify-content: center">
            <a class="navbar-brand bottom-nav" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/dashboard/images/Kiwi-Ticketing-logo-1.png') }}" alt="Control"
                    style="width: 200px;min-height: 29px;">
            </a>
        </div>
    </div>
</div>
