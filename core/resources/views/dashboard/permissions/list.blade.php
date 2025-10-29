<div class="padding">
    <div class="box">
        <div class="box-header dker" style="background: #A0C242; border-color: #8AA936;">
            <h3 style="color: white;">{{ __('backend.permissions') }}</h3>
            <small style="color: rgba(255,255,255,0.8);">
                <a href="{{ route('adminHome') }}" style="color: rgba(255,255,255,0.8);">{{ __('backend.home') }}</a> /
                <a href="" style="color: white;">{{ __('backend.settings') }}</a>
            </small>
        </div>
        
        @if(count($Permissions) >0)
            <div class="row p-a pull-right" style="margin-top: -70px;">
                <div class="col-sm-12">
                    <a class="btn btn-fw primary" href="{{route("permissionsCreate")}}" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                        <i class="material-icons">&#xe03b;</i>
                        &nbsp; {{ __('backend.newPermissions') }}
                    </a>
                </div>
            </div>
        @endif

        @if(count($Permissions)  == 0)
            <div class="row p-a">
                <div class="col-sm-12">
                    <div class="p-a text-center">
                        {{ __('backend.noData') }}
                        <br>
                        <br>
                        <a class="btn btn-fw primary" href="{{route("permissionsCreate")}}" style="background: #A0C242; border-color: #8AA936; color: white;">
                            <i class="material-icons">&#xe03b;</i>
                            &nbsp; {{ __('backend.newPermissions') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if(count($Permissions) > 0)
            <div class="table-responsive">
                <table class="table table-bordered m-a-0">
                    <thead class="dker" style="background: #f8f9fa; color: #696f75;">
                    <tr>
                        <th>{{ __('backend.title') }}</th>
                        <th>{{ __('backend.permissions') }}</th>
                        <th class="text-center" style="width:50px;">{{ __('backend.status') }}</th>
                        <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($Permissions as $Permission)
    <tr>
        <td class="h6">
            <strong style="color: #2c3e50; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-shield-alt" style="color: #A0C242;"></i>
                {!! $Permission->name !!}
            </strong>
        </td>
        <td>
            <!-- Basic Permissions -->
            <div style="margin-bottom: 12px; padding: 8px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 6px; font-weight: 600;">BASIC PERMISSIONS:</div>
                <div>
                    @if($Permission->add_status)
                        <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 4px 12px; border-radius: 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; margin: 2px; font-weight: 500; box-shadow: 0 2px 4px rgba(160, 194, 66, 0.3);">
                            <i class="fas fa-check" style="font-size: 10px;"></i> {{ __('backend.perAdd') }}
                        </span>
                    @endif
                    @if($Permission->edit_status)
                        <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 4px 12px; border-radius: 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; margin: 2px; font-weight: 500; box-shadow: 0 2px 4px rgba(160, 194, 66, 0.3);">
                            <i class="fas fa-check" style="font-size: 10px;"></i> {{ __('backend.perEdit') }}
                        </span>
                    @endif
                    @if($Permission->delete_status)
                        <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 4px 12px; border-radius: 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; margin: 2px; font-weight: 500; box-shadow: 0 2px 4px rgba(160, 194, 66, 0.3);">
                            <i class="fas fa-check" style="font-size: 10px;"></i> {{ __('backend.perDelete') }}
                        </span>
                    @endif
                    @if($Permission->add_status==0 && $Permission->edit_status==0 && $Permission->delete_status==0)
                        <span style="background: #6c757d; color: white; padding: 4px 12px; border-radius: 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; margin: 2px; font-weight: 500;">
                            <i class="fas fa-eye" style="font-size: 10px;"></i> {{ __('backend.viewOnly') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Module Permissions -->
            <div style="margin-bottom: 12px; padding: 8px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 6px; font-weight: 600;">MODULE ACCESS:</div>
                <div>
                    @if($Permission->analytics_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.visitorsAnalytics') }}
                        </span>
                    @endif
                    @if($Permission->newsletter_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.newsletter') }}
                        </span>
                    @endif
                    @if($Permission->inbox_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.siteInbox') }}
                        </span>
                    @endif
                    @if($Permission->calendar_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.calendar') }}
                        </span>
                    @endif
                    @if($Permission->banners_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.adsBanners') }}
                        </span>
                    @endif
                    @if($Permission->menus_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.siteMenus') }}
                        </span>
                    @endif
                    @if($Permission->file_manager_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.fileManager') }}
                        </span>
                    @endif
                    @if($Permission->roles_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.usersPermissions') }}
                        </span>
                    @endif
                    @if($Permission->settings_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.generalSiteSettings') }}
                        </span>
                    @endif
                    @if($Permission->webmaster_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.generalSettings') }}
                        </span>
                    @endif
                    @if($Permission->modules_status)
                        <span style="background: rgba(160, 194, 66, 0.1); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px solid rgba(160, 194, 66, 0.3); font-weight: 500;">
                            {{ __('backend.siteSectionsSettings') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Data Sections -->
            <div style="padding: 8px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 6px; font-weight: 600;">DATA SECTIONS:</div>
                <div>
                    <?php $i = 0; ?>
                    @foreach($GeneralWebmasterSections as $WebmasterSection)
                        <?php
                        $data_sections_arr = explode(",", $Permission->data_sections);
                        ?>
                        @if(in_array($WebmasterSection->id,$data_sections_arr))
                            <span style="background: rgba(160, 194, 66, 0.15); color: #5A6E1E; padding: 4px 10px; border-radius: 14px; font-size: 11px; display: inline-block; margin: 2px; border: 1px dashed rgba(160, 194, 66, 0.4); font-weight: 500;">
                                {!! $WebmasterSection->{"title_" . @Helper::currentLanguage()->code} !!}
                            </span>
                            <?php $i++; ?>
                        @endif
                    @endforeach
                    @if($i == 0)
                        <span style="color: #6c757d; font-size: 11px; font-style: italic;">No data sections assigned</span>
                    @endif
                </div>
            </div>
        </td>
        <td class="text-center">
            <span style="background: {{ $Permission->status ? 'linear-gradient(135deg, #A0C242 0%, #8AAE38 100%)' : '#E74C3C' }}; color: white; padding: 6px 12px; border-radius: 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas {{ $Permission->status ? 'fa-check' : 'fa-times' }}" style="font-size: 10px;"></i>
                {{ $Permission->status ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td class="text-center">
            <div style="display: flex; gap: 6px; justify-content: center;">
                <a class="btn btn-sm" href="{{ route("permissionsEdit",["id"=>$Permission->id]) }}" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border-radius: 8px; border: none; color: white; padding: 6px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);">
                    <i class="material-icons" style="font-size: 14px;">&#xe3c9;</i>
                    <span>Edit</span>
                </a>

                <button class="btn btn-sm" data-toggle="modal"
                        data-target="#p-{{ $Permission->id }}" ui-toggle-class="bounce"
                        ui-target="#animate" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 8px; border: none; color: white; padding: 6px 12px; display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);">
                    <i class="material-icons" style="font-size: 14px;">&#xe872;</i>
                    <span>Delete</span>
                </button>
            </div>
        </td>
    </tr>

    <!-- .modal -->
    <div id="p-{{ $Permission->id }}" class="modal fade" data-backdrop="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; border-bottom: none; padding: 1.5rem;">
                    <h5 class="modal-title" style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ __('backend.confirmation') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-lg" style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <i class="fas fa-trash-alt" style="font-size: 3rem; color: #E74C3C; margin-bottom: 1rem;"></i>
                        <h5 style="color: #2c3e50; margin-bottom: 1rem; font-weight: 600;">{{ __('backend.confirmationDeleteMsg') }}</h5>
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #A0C242;">
                            <strong style="color: #2c3e50; display: block; font-size: 1.1rem;">[ {{ $Permission->name }} ]</strong>
                            <small style="color: #6c757d;">This permission group will be permanently deleted</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary p-x-md" 
                            data-dismiss="modal" 
                            style="background: #6c757d; border: none; border-radius: 8px; padding: 8px 20px; font-weight: 500; transition: all 0.3s ease;">
                        {{ __('backend.no') }}
                    </button>
                    <a href="{{ route("permissionsDestroy",["id"=>$Permission->id]) }}"
                       class="btn btn-danger p-x-md" 
                       style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; border-radius: 8px; padding: 8px 20px; font-weight: 500; transition: all 0.3s ease; color: white; text-decoration: none;">
                        {{ __('backend.yes') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- / .modal -->
@endforeach

                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
/* Additional styling for better appearance */
.label {
    font-size: 12px;
    margin: 2px;
}
.box {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.table th {
    border-bottom: 2px solid #8AA936;
}
.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: all 0.2s;
}
</style>