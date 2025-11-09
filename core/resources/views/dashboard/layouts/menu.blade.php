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
        /* box-shadow: 0 4px 15px rgba(159, 194, 63, 0.3) !important; */
        position: relative;
        overflow: hidden;
        /* transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; */
    }

    /* .nav-active-primary .nav>li.active>a::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: #ffffff;
        border-radius: 3px;
    } */

    .nav-active-primary .nav>li.active>a .nav-icon i {
        color: #ffffff !important;
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .nav-sub>li.active>a {
        color: #A0C242 !important;
        /* border-left: 3px solid #A0C242 !important; */
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

    span.nav-text.dashlogo:hover {
        color: #9FC23F;
    }

    li.active.cust-act span.nav-text.dashlogo::before {
        background-color: #9FC23F;
        width: 16px;
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

            {{-- <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"
                style="position: relative;z-index: 999999;">
                <i class="material-icons md-30 opacity-8">&#xe5d2;</i>
            </a> --}}

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

            {{-- <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"
                style="position: relative;z-index: 999999;">
                <i class="material-icons md-30 opacity-8">&#xe5d2;</i>
            </a> --}}

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
                @endphp
                <ul class="nav" ui-nav>
                    {{-- <li class="nav-header hidden-folded">
                        <small class="text-muted" style="color: #6c757d !important;">{{ __('backend.main') }}</small>
                    </li> --}}
                    {{-- <li {{ Route::currentRouteName() == 'adminHome' ? 'class=active menue-hie' : '' }}
                        style="border-left: 3px solid transparent;">
                        <a href="{{ route('adminHome') }}" style="color: #495057;">
                            <span class="nav-icon" style="padding-top: 4px;">
                                <i class="grid-icon" style="color: #6B7280;"></i>
                            </span>
                            <span class="nav-text">{{ __('backend.dashboard') }}</span>
                        </a>
                    </li> --}}

                    {{-- <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted"
                            style="color: #6c757d !important;">{{ __('Tickets Management') }}</small>
                    </li> --}}

                    <?php
                    $currentFolder = 'kabanasetting';
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    $cabanaActive = in_array($current, ['cabana', 'cabanaCreate', 'cabanaEdit', 'cabanaStore', 'cabanaUpdate', 'cabanaDestroy', 'kabanasetting', 'kabanaddons', 'kabanaorders', 'kabanaaddonEdit', 'kabanaordersdetail', 'cabanaAddonStore']);
                    $birthdayActive = in_array($current, ['birthdaypackages', 'birthdaypackagesCreate', 'birthdaypackagesEdit', 'birthdaypackagesStore', 'birthdaypackagesUpdate', 'birthdayorders', 'birthdayordersdetail', 'birthdayaddon', 'birthdayaddonEdit']);
                    $generalTicketActive = in_array($current, ['generaltickets', 'generalticketsCreate', 'generalticketsEdit', 'generalticketsStore', 'generalticketsaddon', 'generalticketsaddonCreate', 'generalticketsaddonEdit', 'generalticketscabana', 'generalticketscabanaCreate', 'generalticketscabanaStore', 'generalticketscabanaEdit', 'generalticketscabanaUpdate', 'generalticketscabanaDestroy', 'generalticketsorders', 'generalticketsordersdetail', 'generalticketpackages', 'generalticketpackagesEdit', 'generalticketpackagesCreate', 'generalticketpackagesStore', 'generalticketpackagesUpdate', 'generalticketpackagesDestroy']);
                    $seasonpassActive = in_array($current, ['seasonpass', 'seasonpassEdit', 'seasonpassCreate', 'seasonpassStore', 'seasonpassDestroy', 'seasonpassaddon', 'seasonpassaddonEdit', 'seasonpassaddonStore', 'seasonpassaddonCreate', 'seasonpassaddonUpdate', 'seasonpassorders', 'seasonpassordersdetail']);
                    $couponActive = in_array($current, ['coupon', 'couponCreate', 'couponEdit', 'couponStore', 'couponUpdate', 'couponDestroy']);
                    $offerCreationActive = in_array($current, ['offercreationpackages', 'offercreationpackagesEdit', 'offercreationpackagesCreate', 'offercreationpackagesStore', 'offercreationpackagesUpdate', 'offercreationpackagesDestroy', 'offeraddon', 'offeraddonCreate', 'offeraddonStore', 'offeraddonEdit', 'offeraddonUpdate', 'offeraddonDestroy', 'offercreationpackagesorders', 'offercreationpackagesordersdetail']);
                    $transactionActive = in_array($current, ['transactionorders', 'updatetransactionorders']);
                    $LogsActive = in_array($current, ['ordersLogs', 'ordersfailedLogs', 'paymentLogs', 'paymentfailLogs', 'ordersLogsShow']);
                    $emailTemplateActive = in_array($current, ['emailTemplate', 'emailTemplateCreate', 'emailTemplateEdit', 'emailTemplateStore', 'emailTemplateUpdate', 'emailTemplateDestroy', 'smtpConfigure', 'emailLogs']);
                    ?>

                    <!-- Ticket / Pass Management Dropdown -->
                    @if (
                        $userRole == 'Webmaster' ||
                            in_array(16, $dataSections) ||
                            in_array(17, $dataSections) ||
                            in_array(18, $dataSections) ||
                            in_array(19, $dataSections))
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted"
                                style="color: #6c757d !important;">{{ __('Ticket / Pass Management') }}</small>
                        </li>

                        <li class="{{ in_array($current, ['tickets', 'cabanas', 'seasonpass', 'birthdaypackages']) ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-ticket-alt" style="color: #6B7280;"></i>
                                </span>
                                <span class="nav-text">{{ __('Ticket / Pass Management') }}</span>
                            </a>
                            <ul class="nav-sub" style="background: #f8f9fa;">
                                <!-- Tickets -->
                                <li
                                    class="{{ in_array($current, ['generaltickets', 'generalticketpackages']) ? 'active cust-act' : '' }}">
                                    <a style="color: #495057;">
                                        <span class="nav-caret no-mrgn">
                                            <i class="cust-arrow-icon"></i>
                                        </span>
                                        <span class="nav-text dashlogo">{{ __('Tickets') }}</span>
                                    </a>
                                    <ul class="nav-sub" style="background: #f8f9fa; margin-left: 15px;">
                                        @if ($userRole == 'Webmaster' || in_array(30, $dataSections))
                                            <li
                                                class="{{ $current === 'generalticketpackages' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('generalticketpackages') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Ticket Types') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(31, $dataSections))
                                            <li
                                                class="{{ $current === 'generalticketsaddon' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('generalticketsaddon') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(32, $dataSections))
                                            <li
                                                class="{{ $current === 'generalticketsorders' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('generalticketsorders') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Orders') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                <!-- Cabanas -->
                                <li class="{{ $cabanaActive ? 'active cust-act' : '' }}">
                                    <a style="color: #495057;">
                                        <span class="nav-caret no-mrgn">
                                            <i class="cust-arrow-icon"></i>
                                        </span>
                                        <span class="nav-text dashlogo">{{ __('Cabanas') }}</span>
                                    </a>
                                    <ul class="nav-sub" style="background: #f8f9fa; margin-left: 15px;">
                                        @if ($userRole == 'Webmaster' || in_array(24, $dataSections))
                                            <li class="{{ $current === 'cabana' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('cabana') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Cabana Types') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(25, $dataSections))
                                            <li class="{{ $current === 'kabanaddons' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('kabanaddons') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(26, $dataSections))
                                            <li class="{{ $current === 'kabanaorders' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('kabanaorders') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Orders') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                <!-- Season Passes -->
                                <li class="{{ $seasonpassActive ? 'active cust-act' : '' }}">
                                    <a style="color: #495057;">
                                        <span class="nav-caret no-mrgn">
                                            <i class="cust-arrow-icon"></i>
                                        </span>
                                        <span class="nav-text dashlogo">{{ __('Season Passes') }}</span>
                                    </a>
                                    <ul class="nav-sub" style="background: #f8f9fa; margin-left: 15px;">
                                        @if ($userRole == 'Webmaster' || in_array(33, $dataSections))
                                            <li class="{{ $current === 'seasonpass' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('seasonpass') }}" style="color: #495057;">
                                                    <span
                                                        class="nav-text dashlogo">{{ __('Season Pass Types') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(34, $dataSections))
                                            <li class="{{ $current === 'seasonpassaddon' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('seasonpassaddon') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(35, $dataSections))
                                            <li
                                                class="{{ $current === 'seasonpassorders' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('seasonpassorders') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Orders') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                <!-- Packages / Birthdays -->
                                <li class="{{ $birthdayActive ? 'active cust-act' : '' }}">
                                    <a style="color: #495057;">
                                        <span class="nav-caret no-mrgn">
                                            <i class="cust-arrow-icon"></i>
                                        </span>
                                        <span class="nav-text dashlogo">{{ __('Packages / Birthdays') }}</span>
                                    </a>
                                    <ul class="nav-sub" style="background: #f8f9fa; margin-left: 15px;">
                                        @if ($userRole == 'Webmaster' || in_array(27, $dataSections))
                                            <li
                                                class="{{ $current === 'birthdaypackages' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('birthdaypackages') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Package Types') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(28, $dataSections))
                                            <li class="{{ $current === 'birthdayaddon' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('birthdayaddon') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($userRole == 'Webmaster' || in_array(29, $dataSections))
                                            <li class="{{ $current === 'birthdayorders' ? 'active cust-act' : '' }}">
                                                <a href="{{ route('birthdayorders') }}" style="color: #495057;">
                                                    <span class="nav-text dashlogo">{{ __('Orders') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Sale Management Dropdown -->
                    @if ($userRole == 'Webmaster' || in_array(20, $dataSections))
                        <li class="{{ $offerCreationActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-shopping-cart" style="color: #6B7280;"></i>
                                </span>
                                <span class="nav-text">{{ __('Sale Management') }}</span>
                            </a>
                            <ul class="nav-sub" style="background: #f8f9fa;">
                                @if ($userRole == 'Webmaster' || in_array(36, $dataSections))
                                    <li class="{{ $current === 'offercreationpackages' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('offercreationpackages') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Sale Type') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($userRole == 'Webmaster' || in_array(37, $dataSections))
                                    <li class="{{ $current === 'offeraddon' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('offeraddon') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Addon Control') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($userRole == 'Webmaster' || in_array(38, $dataSections))
                                    <li
                                        class="{{ $current === 'offercreationpackagesorders' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('offercreationpackagesorders') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Orders') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- Coupon Management Dropdown -->
                    @if ($userRole == 'Webmaster' || in_array(21, $dataSections))
                        <li class="{{ $couponActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-tags" style="color: #6B7280;"></i>
                                </span>
                                <span class="nav-text">{{ __('Coupon Management') }}</span>
                            </a>
                            <ul class="nav-sub" style="background: #f8f9fa;">
                                @if ($userRole == 'Webmaster' || in_array(39, $dataSections))
                                    <li class="{{ $current === 'coupon' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('coupon') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Coupon Types') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- Order Details/Transaction -->
                    @if ($userRole == 'Webmaster' || in_array(22, $dataSections))
                        <li class="{{ $transactionActive ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a href="{{ route('transactionorders') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-file-invoice-dollar" style="color: #6B7280;"></i>
                                </span>
                                <span class="nav-text">{{ __('Order Details/Transaction') }}</span>
                            </a>
                        </li>
                    @endif

                    <!-- Settings Dropdown -->
                    @if (
                        $userRole == 'Webmaster' ||
                            in_array(22, $dataSections) ||
                            @Auth::user()->permissionsGroup->roles_status ||
                            (Helper::GeneralWebmasterSettings('settings_status') && @Auth::user()->permissionsGroup->settings_status))
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted" style="color: #6c757d !important;">{{ __('Settings') }}</small>
                        </li>

                        <li class="{{ in_array($current, ['ordersLogs', 'users', 'settings', 'emailTemplate']) ? 'active menue-hie' : '' }}"
                            style="border-left: 3px solid transparent;">
                            <a style="color: #495057;">
                                <span class="nav-caret no-mrgn">
                                    <i class="cust-arrow-icon"></i>
                                </span>
                                <span class="nav-icon">
                                    <i class="fas fa-cogs" style="color: #6B7280;"></i>
                                </span>
                                <span class="nav-text">{{ __('Settings') }}</span>
                            </a>
                            <ul class="nav-sub" style="background: #f8f9fa;">
                                <!-- Order Logs -->
                                @if ($userRole == 'Webmaster' || in_array(40, $dataSections))
                                    <li class="{{ $LogsActive ? 'active cust-act' : '' }}">
                                        <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Order Logs') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- User Permission -->
                                @if (@Auth::user()->permissionsGroup->roles_status)
                                    <li class="{{ $PathCurrentFolder == 'users' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('users') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('User Permission') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- Email Management -->
                                @if ($userRole == 'Webmaster')
                                    <li class="{{ $emailTemplateActive ? 'active cust-act' : '' }}">
                                        <a href="{{ route('emailTemplate') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Email Management') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- All Logs -->
                                @if (
                                    $userRole == 'Webmaster' ||
                                        in_array(40, $dataSections) ||
                                        in_array(41, $dataSections) ||
                                        in_array(42, $dataSections) ||
                                        in_array(43, $dataSections))
                                    <li class="{{ $LogsActive ? 'active cust-act' : '' }}">
                                        <a style="color: #495057;">
                                            <span class="nav-caret no-mrgn">
                                                <i class="cust-arrow-icon"></i>
                                            </span>
                                            <span class="nav-text dashlogo">{{ __('All Logs') }}</span>
                                        </a>
                                        <ul class="nav-sub" style="background: #f8f9fa; margin-left: 15px;">
                                            @if ($userRole == 'Webmaster' || in_array(40, $dataSections))
                                                <li class="{{ $current === 'ordersLogs' ? 'active cust-act' : '' }}">
                                                    <a href="{{ route('ordersLogs') }}" style="color: #495057;">
                                                        <span
                                                            class="nav-text dashlogo">{{ __('Order Success Logs') }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($userRole == 'Webmaster' || in_array(41, $dataSections))
                                                <li
                                                    class="{{ $current === 'ordersfailedLogs' ? 'active cust-act' : '' }}">
                                                    <a href="{{ route('ordersfailedLogs') }}"
                                                        style="color: #495057;">
                                                        <span
                                                            class="nav-text dashlogo">{{ __('Order Failed Logs') }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($userRole == 'Webmaster' || in_array(42, $dataSections))
                                                <li class="{{ $current === 'paymentLogs' ? 'active cust-act' : '' }}">
                                                    <a href="{{ route('paymentLogs') }}" style="color: #495057;">
                                                        <span
                                                            class="nav-text dashlogo">{{ __('Payment Success Logs') }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($userRole == 'Webmaster' || in_array(43, $dataSections))
                                                <li
                                                    class="{{ $current === 'paymentfailLogs' ? 'active cust-act' : '' }}">
                                                    <a href="{{ route('paymentfailLogs') }}" style="color: #495057;">
                                                        <span
                                                            class="nav-text dashlogo">{{ __('Payment Failed Logs') }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                <!-- Website Setting -->
                                @if (Helper::GeneralWebmasterSettings('settings_status') && @Auth::user()->permissionsGroup->settings_status)
                                    <li class="{{ $PathCurrentFolder == 'settings' ? 'active cust-act' : '' }}">
                                        <a href="{{ route('settings') }}" style="color: #495057;">
                                            <span class="nav-text dashlogo">{{ __('Website Setting') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- Webmaster Tools
                    @if (@Auth::user()->permissionsGroup->webmaster_status || @Auth::user()->permissionsGroup->modules_status)
<li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted" style="color: #6c757d !important;">{{ __('backend.webmasterTools') }}</small>
                        </li>
@endif -->

                    <!-- Modules Management -->
                    <!-- @if (@Auth::user()->permissionsGroup->modules_status)
<?php
$currentFolder = 'modules'; // Put folder name here
$PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
?>
                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active menue-hie' : '' }} style="border-left: 3px solid transparent;">
                            <a href="{{ route('WebmasterSections') }}" style="color: #495057;">
                                <span class="nav-icon">
                                    <i class="fas fa-th-large" style="color: #A0C242;"></i>
                                </span>
                                <span class="nav-text">{{ __('backend.siteSectionsSettings') }}</span>
                            </a>
                        </li>
@endif -->

                    <!-- Webmaster Settings
                    @if (@Auth::user()->permissionsGroup->webmaster_status)
<?php
$currentFolder = 'webmaster'; // Put folder name here
$PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
?>
                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active menue-hie' : '' }} style="border-left: 3px solid transparent;">
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
        <div class="navbar navbar-md no-radius" style="display: flex;justify-content: center">

            <a class="navbar-brand bottom-nav" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/dashboard/images/Kiwi-Ticketing-logo-1.png') }}" alt="Control"
                    style="width: 200px;min-height: 29px;">
                {{-- <span class="hidden-folded text-white inline"  style="margin-left:15px">{{ __('Kiwi Ticketing') }}</span> --}}
            </a>
        </div>
    </div>
</div>
