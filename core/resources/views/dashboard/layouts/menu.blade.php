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

<div id="aside" class="app-aside modal fade folded md nav-expand">
    <div class="left navside dark dk" layout="column">

        <div class="navbar navbar-md no-radius">
            <a class="hidden-folded inline folded-toggle m-t p-t-xs pull-right">
                <i class="material-icons md-24 opacity">&#xe5d2;</i>
            </a>
            <!-- brand -->
            <a class="navbar-brand" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/dashboard/images/logo.png') }}" alt="Control">
                <span class="hidden-folded inline">{{ __('Kiwi Ticketing') }}</span>
            </a>
            <!-- / brand -->
        </div>
        <div flex class="hide-scroll">
            <nav class="scroll nav-active-primary">

                <ul class="nav" ui-nav>
                    <li class="nav-header hidden-folded">
                        <small class="text-muted">{{ __('backend.main') }}</small>
                    </li>
                    <li {{ (Route::currentRouteName()=="adminHome") ? 'class=active' : '' }}>
                        <a href="{{ route('adminHome') }}">
                  <span class="nav-icon">
                    <i class="material-icons">&#xe3fc;</i>
                  </span>
                            <span class="nav-text">{{ __('backend.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted">{{ __('Tickets Managment') }}</small>
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
                        ?>
                    <li class="{{ $cabanaActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe156;</i>
                            </span>
                            <span class="nav-text">{{ __('Cabana Managment') }}</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'cabana' 
                                || Str::startsWith($current, 'cabana') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('cabana') }}">
                                    <span class="nav-text">{{ __('All Cabana') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'kabanaddons' 
                                || Str::startsWith($current, 'kabanaaddonEdit') 
                                || Str::startsWith($current, 'kabanaddons') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('kabanaddons') }}">
                                    <span class="nav-text">{{ __('Cabana Addon') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'kabanaorders' 
                                || Str::startsWith($current, 'kabanaorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('kabanaorders') }}">
                                    <span class="nav-text">{{ __('Cabana Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $birthdayActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe241;</i>
                            </span>
                            <span class="nav-text">{{ __('Birthday Managment') }}</span>
                        </a>

                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'birthdaypackages' || 
                                Str::startsWith($current, 'birthdaypackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdaypackages') }}">
                                    <span class="nav-text">{{ __('Birthday Packages') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'birthdayaddon' || 
                                Str::startsWith($current, 'birthdayaddon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdayaddon') }}">
                                    <span class="nav-text">{{ __('Birthday Addon') }}</span>
                                </a>
                            </li>

                            <li class="{{ 
                                $current === 'birthdayorders' || 
                                Str::startsWith($current, 'birthdayorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('birthdayorders') }}">
                                    <span class="nav-text">{{ __('Birthday Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $generalTicketActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe433;</i>
                            </span>
                            <span class="nav-text">{{ __('Platform Managment') }}</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'generaltickets' 
                                || Str::startsWith($current, 'generaltickets') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketpackages') }}">
                                    <span class="nav-text">{{ __('General Packages') }}</span>
                                </a>
                            </li>
                            <!-- <li class="{{ 
                                $current === 'generaltickets' 
                                || Str::startsWith($current, 'generaltickets') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generaltickets') }}">
                                    <span class="nav-text">{{ __('All General Tickets') }}</span>
                                </a>
                            </li> -->

                           <li class="{{ 
                                $current === 'generalticketsaddon' 
                                || Str::startsWith($current, 'generalticketsaddon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketsaddon') }}">
                                    <span class="nav-text">{{ __('General Package Addon') }}</span>
                                </a>
                            </li>

                             <li class="{{ 
                                $current === 'generalticketsorders' 
                                || Str::startsWith($current, 'generalticketsorders') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('generalticketsorders') }}">
                                    <span class="nav-text">{{ __('General Tickets Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $seasonpassActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe8d9;</i>
                            </span>
                            <span class="nav-text">{{ __('SeasonPass Managment') }}</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpass') }}">
                                    <span class="nav-text">{{ __('Season Pass Sale') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpassaddon') }}">
                                    <span class="nav-text">{{ __('Season Pass Products') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'seasonpass' 
                                || Str::startsWith($current, 'seasonpass') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('seasonpassorders') }}">
                                    <span class="nav-text">{{ __('Season Pass Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $offerCreationActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe53b;</i>
                            </span>
                            <span class="nav-text">{{ __('Offer Managment') }}</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offercreationpackages') }}">
                                    <span class="nav-text">{{ __('Offers') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offeraddon') }}">
                                    <span class="nav-text">{{ __('Offer Addon') }}</span>
                                </a>
                            </li>
                            <li class="{{ 
                                $current === 'offercreationpackages' 
                                || Str::startsWith($current, 'offercreationpackages') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('offercreationpackagesorders') }}">
                                    <span class="nav-text">{{ __('Offers Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted">{{ __('Coupon Managment') }}</small>
                    </li>
                    <li class="{{ $couponActive ? 'active' : '' }}" >
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe1b8;</i>
                            </span>
                            <span class="nav-text">{{ __('Coupons Managment') }}</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="{{ 
                                $current === 'coupon' 
                                || Str::startsWith($current, 'coupon') 
                                ? 'active' : '' 
                            }}">
                                <a href="{{ route('coupon') }}">
                                    <span class="nav-text">{{ __('All Coupons') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if(@Auth::user()->permissionsGroup->roles_status || (Helper::GeneralWebmasterSettings("settings_status") && @Auth::user()->permissionsGroup->settings_status) || @Auth::user()->permissionsGroup->webmaster_status)
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted">{{ __('backend.settings') }}</small>
                        </li>
                    @endif

                    @if(@Auth::user()->permissionsGroup->roles_status)
                        <?php
                        $currentFolder = "users"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <!-- <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                            <a href="{{ route('users') }}">
<span class="nav-icon">
<i class="material-icons">&#xe7fb;</i>
</span>
                                <span class="nav-text">{{ __('backend.usersPermissions') }}</span>
                            </a>
                        </li> -->

                    @endif
                    @if(Helper::GeneralWebmasterSettings("settings_status"))
                        @if(@Auth::user()->permissionsGroup->settings_status)
                            <?php
                            $currentFolder = "settings"; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('settings') }}">
                                <span class="nav-icon">
                                <i class="material-icons">&#xe8b8;</i>
                                </span>
                                    <span class="nav-text">{{ __('backend.generalSiteSettings') }}</span>
                                </a>
                            </li>

                        @endif
                    @endif

                    @if(@Auth::user()->permissionsGroup->webmaster_status || @Auth::user()->permissionsGroup->modules_status)
                        <!-- <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted">{{ __('backend.webmasterTools') }}</small>
                        </li> -->
                    @endif

                    @if(@Auth::user()->permissionsGroup->modules_status)
                        <?php
                        $currentFolder = "modules"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <!-- <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                            <a href="{{ route('WebmasterSections') }}">
<span class="nav-icon">
<i class="material-icons">&#xe30d;</i>
</span>
                                <span class="nav-text">{{ __('backend.siteSectionsSettings') }}</span>
                            </a>
                        </li> -->

                    @endif

                    @if(@Auth::user()->permissionsGroup->webmaster_status)
                        <?php
                        $currentFolder = "webmaster"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <!-- <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                            <a href="{{ route('webmasterSettings') }}">
<span class="nav-icon">
<i class="material-icons">&#xe8b8;</i>
</span>
                                <span class="nav-text">{{ __('backend.generalSettings') }}</span>
                            </a>
                        </li> -->

                    @endif

                </ul>
            </nav>
        </div>
        <br>
    </div>
</div>
