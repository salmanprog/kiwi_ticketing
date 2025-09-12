<?php
// Current Full URL
$fullPagePath = Request::url();
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
                        $currentFolder = "kabanasetting"; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                    <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                        <!-- <a href="{{ route('kabanasetting') }}">
                            <span class="nav-icon">
                            <i class="material-icons">&#xe7fb;</i>
                            </span>
                            <span class="nav-text">{{ __('Cabana Setting') }}</span>
                        </a> -->
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe7fb;</i>
                            </span>
                            <span class="nav-text">{{ __('Cabana Managment') }}</span>
                        </a>

                        <ul class="nav-sub">
                            <?php
                                $currentFolder = "kabanasetting"; // Put folder name here
                                $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('kabanasetting') }}">
                                    <span class="nav-text">{{ __('All Cabana') }}</span>
                                </a>
                            </li>
                            <?php
                                $currentFolder = "kabanaddon"; // Put folder name here
                                $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('kabanaddons') }}">
                                    <span class="nav-text">{{ __('Cabana Addon') }}</span>
                                </a>
                            </li>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('kabanaorders') }}">
                                    <span class="nav-text">{{ __('Cabana Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                        <!-- <a href="{{ route('kabanasetting') }}">
                            <span class="nav-icon">
                            <i class="material-icons">&#xe7fb;</i>
                            </span>
                            <span class="nav-text">{{ __('Cabana Setting') }}</span>
                        </a> -->
                        <a>
                            <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                            <i class="material-icons">&#xe7fb;</i>
                            </span>
                            <span class="nav-text">{{ __('Birthday Managment') }}</span>
                        </a>

                        <ul class="nav-sub">
                            <?php
                                $currentFolder = "birthday_packages"; // Put folder name here
                                $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('birthdaypackages') }}">
                                    <span class="nav-text">{{ __('Birthday Packages') }}</span>
                                </a>
                            </li>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="{{ route('birthdayaddon') }}">
                                    <span class="nav-text">{{ __('Birthday Addon') }}</span>
                                </a>
                            </li>
                            <li {{ ($PathCurrentFolder==$currentFolder) ? 'class=active' : '' }} >
                                <a href="#">
                                    <span class="nav-text">{{ __('Birthday Orders') }}</span>
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
