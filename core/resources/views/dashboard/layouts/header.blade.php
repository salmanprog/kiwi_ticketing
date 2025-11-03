<div class="app-header white box-shadow navbar-md" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-bottom: 1px solid  #dee2e6;box-shadow: none">
    <div class="navbar">
        <!-- Brand Logo & Name -->


        <!-- Open side - Navigation on mobile -->
        <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
            <i class="material-icons md-30 opacity-8">&#xe5d2;</i>
        </a>

        <!-- Page title - Bind to $state's title -->
        <div class="navbar-item pull-left h5 text-dark" ng-bind="$state.current.data.title" id="pageTitle"></div>

        <!-- navbar right -->
        <ul class="nav navbar-nav pull-right">
            <?php
            $webmailsAlerts = Helper::webmailsAlerts();
            $eventsAlerts = Helper::eventsAlerts();
            $alerts = count($webmailsAlerts) + count($eventsAlerts);
            ?>
            @if($alerts >0)
                <li class="nav-item dropdown pos-stc-xs">
                    <a class="nav-link text-dark" data-toggle="dropdown" style="position: relative;">
                        <i class="material-icons">&#xe7f5;</i>
                        @if($alerts >0)
                            <span class="label label-sm up warn" style="background: #A0C242; border: 2px solid white; position: absolute; top: -5px; right: -5px;">{{ $alerts }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow" style="border: 1px solid #e9ecef !important; border-radius: 10px;">
                        <div class="box" style="background: white;">
                            <div class="box p-a scrollable maxHeight320">
                                <div class="dropdown-header bg-light" style="border-radius: 10px 10px 0 0;">
                                    <h6 class="mb-0 text-dark"><i class="material-icons mr-2">&#xe7f5;</i>Notifications</h6>
                                </div>
                                <ul class="list-group list-group-gap m-a-0">
                                    @foreach($webmailsAlerts as $webmailsAlert)
                                        <li class="list-group-item lt box-shadow-z0 b">
                                            <span class="clear block">
                                                <small class="text-muted">{{ $webmailsAlert->from_name }}</small><br>
                                                <a href="{{ route("webmailsEdit",["id"=>$webmailsAlert->id]) }}"
                                                   class="text-primary">{{ $webmailsAlert->title }}</a>
                                                <br>
                                                <small class="text-muted">
                                                    {{ date('d M Y  h:i A', strtotime($webmailsAlert->date)) }}
                                                </small>
                                            </span>
                                        </li>
                                    @endforeach
                                    @foreach($eventsAlerts as $eventsAlert)
                                        <li class="list-group-item lt box-shadow-z0 b">
                                            <span class="clear block">
                                                <a href="{{ route("calendarEdit",["id"=>$eventsAlert->id]) }}"
                                                   class="text-primary">{{ $eventsAlert->title }}</a>
                                                <br>
                                                <small class="text-muted">
                                                    @if($eventsAlert->type ==3 || $eventsAlert->type ==2)
                                                        {{ date('d M Y  h:i A', strtotime($eventsAlert->start_date)) }}
                                                    @else
                                                        {{ date('d M Y', strtotime($eventsAlert->start_date)) }}
                                                    @endif
                                                </small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            
            <li class="nav-item dropdown">
                <a class="nav-link clear text-dark" data-toggle="dropdown">
                  <span class="avatar w-32" style="border: 2px solid #A0C242;">
                      @if(Auth::user()->photo !="")
                          <img src="{{ asset('uploads/users/'.Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                               title="{{ Auth::user()->name }}" style="border-radius: 50%;">
                      @else
                          <img src="{{ asset('uploads/contacts/profile.jpg') }}" alt="{{ Auth::user()->name }}"
                               title="{{ Auth::user()->name }}" style="border-radius: 50%;">
                      @endif
                      <i class="on b-white bottom" style="background: #A0C242;"></i>
                  </span>
                </a>
                <div class="dropdown-menu pull-right dropdown-menu-scale" style="border: 1px solid #e9ecef; border-radius: 10px;">
                    <div class="dropdown-header bg-light" style="border-radius: 10px 10px 0 0;">
                        <h6 class="mb-0 text-dark">Welcome, {{ Auth::user()->name }}</h6>
                        <small class="text-muted">KIWI Ticketing Portal</small>
                    </div>
                    
                    @if(Helper::GeneralWebmasterSettings("inbox_status"))
                        @if(@Auth::user()->permissionsGroup->inbox_status)
                            <!-- <a class="dropdown-item d-flex align-items-center"
                               href="{{ route('webmails') }}">
                                <i class="material-icons mr-2 text-muted">&#xe0be;</i>
                                <span>{{ __('backend.siteInbox') }}</span>
                                @if( @$webmailsNewCount >0)
                                    <span class="badge badge-pill ml-auto" style="background: #A0C242;">{{ @$webmailsNewCount }}</span>
                                @endif
                            </a> -->
                        @endif
                    @endif
                    
                    @if(Auth::user()->permissions ==0 || Auth::user()->permissions ==1)
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('usersEdit',Auth::user()->id) }}">
                            <i class="material-icons mr-2 text-muted">&#xe853;</i>
                            <span>{{ __('backend.profile') }}</span>
                        </a>
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('adminLogout') }}">
                        <i class="material-icons mr-2">&#xe879;</i>
                        <span>{{ __('backend.logout') }}</span>
                    </a>
                </div>
            </li>

            <li class="nav-item hidden-md-up">
                <a class="nav-link text-dark" data-toggle="collapse" data-target="#collapse">
                    <i class="material-icons">&#xe5d4;</i>
                </a>
            </li>
        </ul>

        <!-- navbar collapse -->
        <div class="collapse navbar-toggleable-sm" id="collapse">
            @if(Route::currentRouteName() !="adminSearch")
                {{Form::open(['route'=>['adminSearch'],'method'=>'GET', 'role'=>'search', 'class' => "navbar-form form-inline pull-right pull-none-sm navbar-item v-m" ])}}

                <div class="form-group l-h m-a-0">
                    <div class="input-group" style="border-radius: 20px; overflow: hidden;">
                        <input type="text" name="q" class="form-control p-x border-0" autocomplete="off"
                               placeholder="{{ __('backend.search') }}..." style="background: transparent;">
                        <span class="input-group-btn">
                            <button type="submit" class="btn border-0" style="background: #A0C242; color: white;">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                {{Form::close()}}
            @endif

            @if(Helper::GeneralWebmasterSettings("license") && Helper::GeneralWebmasterSettings("purchase_code")!="")
                @if(@Auth::user()->permissionsGroup->add_status)
                    <!-- <ul class="nav navbar-nav">
                        <li class="nav-item dropdown pa-13">
                            <a class="btn" data-toggle="dropdown" style="background: #A0C242; color: white; border-radius: 20px; border: none;">
                                <i class="material-icons">&#xe145;</i>
                                <span>{{ __('backend.new') }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-scale" style="border: 1px solid #e9ecef; border-radius: 10px;">
                                <div class="dropdown-header bg-light" style="border-radius: 10px 10px 0 0;">
                                    <h6 class="mb-0 text-dark">Create New</h6>
                                </div>
                                <?php
                                $data_sections_arr = explode(",", Auth::user()->permissionsGroup->data_sections);
                                $clr_ary = array("info", "danger", "success", "accent",);
                                $ik = 0;
                                $mnu_title_var = "title_" . @Helper::currentLanguage()->code;
                                $mnu_title_var2 = "title_" . config('smartend.default_language');
                                ?>
                                @if(@Auth::user()->permissionsGroup->add_status)
                                    @foreach($GeneralWebmasterSections as $headerWebmasterSection)
                                        @if(in_array($headerWebmasterSection->id,$data_sections_arr))
                                            <?php
                                            if ($headerWebmasterSection->$mnu_title_var != "") {
                                                $GeneralWebmasterSectionTitle = $headerWebmasterSection->$mnu_title_var;
                                            } else {
                                                $GeneralWebmasterSectionTitle = $headerWebmasterSection->$mnu_title_var2;
                                            }
                                            $LiIcon = "&#xe2c8;";
                                            if ($headerWebmasterSection->type == 3) {
                                                $LiIcon = "&#xe050;";
                                            }
                                            if ($headerWebmasterSection->type == 2) {
                                                $LiIcon = "&#xe63a;";
                                            }
                                            if ($headerWebmasterSection->type == 1) {
                                                $LiIcon = "&#xe251;";
                                            }
                                            if ($headerWebmasterSection->type == 0) {
                                                $LiIcon = "&#xe2c8;";
                                            }
                                            if ($headerWebmasterSection->id == 1) {
                                                $LiIcon = "&#xe3e8;";
                                            }
                                            if ($headerWebmasterSection->id == 7) {
                                                $LiIcon = "&#xe02f;";
                                            }
                                            if ($headerWebmasterSection->id == 2) {
                                                $LiIcon = "&#xe540;";
                                            }
                                            if ($headerWebmasterSection->id == 3) {
                                                $LiIcon = "&#xe307;";
                                            }
                                            if ($headerWebmasterSection->id == 8) {
                                                $LiIcon = "&#xe8f6;";
                                            }
                                            ?>
                                            <a class="dropdown-item d-flex align-items-center"
                                               href="{{route("topicsCreate",$headerWebmasterSection->id)}}">
                                                <i class="material-icons mr-2 text-muted">{!! $LiIcon !!}</i>
                                                <span>{!! $GeneralWebmasterSectionTitle !!}</span>
                                            </a>
                                        @endif
                                    @endforeach

                                    @if(@Auth::user()->permissionsGroup->banners_status)
                                        <a class="dropdown-item d-flex align-items-center" href="{{route("Banners")}}">
                                            <i class="material-icons mr-2 text-muted">&#xe433;</i>
                                            <span>{{ __('backend.adsBanners') }}</span>
                                        </a>
                                    @endif
                                    <div class="dropdown-divider"></div>

                                    @if(Helper::GeneralWebmasterSettings("newsletter_status"))
                                        @if(@Auth::user()->permissionsGroup->newsletter_status)
                                            <a class="dropdown-item d-flex align-items-center" href="{{route("contacts")}}">
                                                <i class="material-icons mr-2 text-muted">&#xe7ef;</i>
                                                <span>{{ __('backend.newContacts') }}</span>
                                            </a>
                                        @endif
                                    @endif
                                @endif
                                @if(Helper::GeneralWebmasterSettings("inbox_status"))
                                    @if(@Auth::user()->permissionsGroup->inbox_status)
                                        <a class="dropdown-item d-flex align-items-center"
                                           href="{{ route("webmails",["group_id"=>"create"]) }}">
                                            <i class="material-icons mr-2 text-muted">&#xe0be;</i>
                                            <span>{{ __('backend.compose') }}</span>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </li>
                    </ul> -->
                @endif
            @else
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <div class="pa-13">
                            <strong class="inline-block text-danger">{{ __('backend.unlicensed') }}</strong> &nbsp;
                            <a href="{{ route("webmasterSettings") }}?tab=license"
                               class="btn btn-danger" style="border-radius: 20px;">{{ __('backend.licenseNow') }}</a>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>