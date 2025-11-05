<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\WebmasterLicenseController;
use App\Http\Controllers\Dashboard\WebmasterSettingsController;
use App\Http\Controllers\Dashboard\WebmasterBannersController;
use App\Http\Controllers\Dashboard\WebmasterSectionsController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\BannersController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\TopicsController;
use App\Http\Controllers\Dashboard\ContactsController;
use App\Http\Controllers\Dashboard\WebmailsController;
use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\AnalyticsController;
use App\Http\Controllers\Dashboard\MenusController;
use App\Http\Controllers\Dashboard\FileManagerController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\PopupController;
use App\Http\Controllers\Dashboard\CabanaPackagesController;
use App\Http\Controllers\Dashboard\KabanaSettingController;
use App\Http\Controllers\Dashboard\KabanaAddonsController;
use App\Http\Controllers\Dashboard\BirthdayPackagesController;
use App\Http\Controllers\Dashboard\BirthdayAddonsController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\GeneralTicketController;
use App\Http\Controllers\Dashboard\GeneralTicketAddonController;
use App\Http\Controllers\Dashboard\GeneralTicketCabanaController;
use App\Http\Controllers\Dashboard\SeasonPassController;
use App\Http\Controllers\Dashboard\SeasonPassAddonsController;
use App\Http\Controllers\Dashboard\GeneralTicketPackagesController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\OfferCreationPackagesController;
use App\Http\Controllers\Dashboard\OfferAddonController;
use App\Http\Controllers\Dashboard\EmailController;
use App\Http\Controllers\Dashboard\EmailLogsController;
use App\Http\Controllers\Dashboard\LogController;
use Illuminate\Support\Facades\Route;

// Admin Home
Route::get('/', [DashboardController::class, 'index'])->name('adminHome');
//Search
Route::get('/search', [DashboardController::class, 'search'])->name('adminSearch');

// Webmaster
Route::get('/webmaster', [WebmasterSettingsController::class, 'edit'])->name('webmasterSettings');
Route::get('/webmaster-save/{tab?}', [WebmasterSettingsController::class, 'saved'])->name('webmasterSettingsSaved');
Route::post('/webmaster', [WebmasterSettingsController::class, 'update'])->name('webmasterSettingsUpdate');
Route::post('/webmaster/languages/store', [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
Route::post('/webmaster/languages/store', [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
Route::post('/webmaster/languages/update', [WebmasterSettingsController::class, 'language_update'])->name('webmasterLanguageUpdate');
Route::get('/webmaster/languages/destroy/{id}', [WebmasterSettingsController::class, 'language_destroy'])->name('webmasterLanguageDestroy');
Route::get('/webmaster/seo/repair', [WebmasterSettingsController::class, 'seo_repair'])->name('webmasterSEORepair');

Route::post('/webmaster/mail/smtp', [WebmasterSettingsController::class, 'mail_smtp_check'])->name('mailSMTPCheck');
Route::post('/webmaster/mail/test', [WebmasterSettingsController::class, 'mail_test'])->name('mailTest');


Route::post('/webmaster-license', [WebmasterLicenseController::class, 'index'])->name('licenseCheck');

// Webmaster Sections
Route::get('/modules', [WebmasterSectionsController::class, 'index'])->name('WebmasterSections');
Route::get('/modules/create', [WebmasterSectionsController::class, 'create'])->name('WebmasterSectionsCreate');
Route::post('/modules/store', [WebmasterSectionsController::class, 'store'])->name('WebmasterSectionsStore');
Route::get('/modules/{id}/edit', [WebmasterSectionsController::class, 'edit'])->name('WebmasterSectionsEdit');
Route::post('/modules/{id}/update',
    [WebmasterSectionsController::class, 'update'])->name('WebmasterSectionsUpdate');

Route::post('/modules/{id}/seo', [WebmasterSectionsController::class, 'seo'])->name('WebmasterSectionsSEOUpdate');

Route::get('/modules/destroy/{id}',
    [WebmasterSectionsController::class, 'destroy'])->name('WebmasterSectionsDestroy');
Route::post('/modules/updateAll',
    [WebmasterSectionsController::class, 'updateAll'])->name('WebmasterSectionsUpdateAll');

// Webmaster Sections :Custom Fields
Route::get('/modules/{webmasterId}/fields', [WebmasterSectionsController::class, 'webmasterFields'])->name('webmasterFields');
Route::get('/{webmasterId}/fields/create', [WebmasterSectionsController::class, 'fieldsCreate'])->name('webmasterFieldsCreate');
Route::post('/modules/{webmasterId}/fields/store', [WebmasterSectionsController::class, 'fieldsStore'])->name('webmasterFieldsStore');
Route::get('/modules/{webmasterId}/fields/{field_id}/edit', [WebmasterSectionsController::class, 'fieldsEdit'])->name('webmasterFieldsEdit');
Route::post('/modules/{webmasterId}/fields/{field_id}/update', [WebmasterSectionsController::class, 'fieldsUpdate'])->name('webmasterFieldsUpdate');
Route::get('/modules/{webmasterId}/fields/destroy/{field_id}', [WebmasterSectionsController::class, 'fieldsDestroy'])->name('webmasterFieldsDestroy');
Route::post('/modules/{webmasterId}/fields/updateAll', [WebmasterSectionsController::class, 'fieldsUpdateAll'])->name('webmasterFieldsUpdateAll');

// Settings
Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
Route::post('/settings', [SettingsController::class, 'updateSiteInfo'])->name('settingsUpdateSiteInfo');

// Ad. Banners
Route::get('/banners', [BannersController::class, 'index'])->name('Banners');
Route::get('/banners/create/{sectionId}', [BannersController::class, 'create'])->name('BannersCreate');
Route::post('/banners/store', [BannersController::class, 'store'])->name('BannersStore');
Route::get('/banners/{id}/edit', [BannersController::class, 'edit'])->name('BannersEdit');
Route::post('/banners/{id}/update', [BannersController::class, 'update'])->name('BannersUpdate');
Route::get('/banners/destroy/{id?}', [BannersController::class, 'destroy'])->name('BannersDestroy');
Route::post('/banners/updateAll', [BannersController::class, 'updateAll'])->name('BannersUpdateAll');

// Webmaster Banners
Route::get('/banners-settings', [WebmasterBannersController::class, 'index'])->name('WebmasterBanners');
Route::get('/banners-settings/create', [WebmasterBannersController::class, 'create'])->name('WebmasterBannersCreate');
Route::post('/banners-settings/store', [WebmasterBannersController::class, 'store'])->name('WebmasterBannersStore');
Route::get('/banners-settings/{id}/edit', [WebmasterBannersController::class, 'edit'])->name('WebmasterBannersEdit');
Route::post('/banners-settings/{id}/update', [WebmasterBannersController::class, 'update'])->name('WebmasterBannersUpdate');
Route::get('/banners-settings/destroy/{id}', [WebmasterBannersController::class, 'destroy'])->name('WebmasterBannersDestroy');
Route::post('/banners-settings/updateAll', [WebmasterBannersController::class, 'updateAll'])->name('WebmasterBannersUpdateAll');


// Sections
Route::get('/{webmasterId}/categories', [CategoriesController::class, 'index'])->name('categories');
Route::get('/{webmasterId}/categories/create', [CategoriesController::class, 'create'])->name('categoriesCreate');
Route::post('/{webmasterId}/categories/store', [CategoriesController::class, 'store'])->name('categoriesStore');
Route::get('/{webmasterId}/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categoriesEdit');
Route::get('/{webmasterId}/categories/{id}/clone', [CategoriesController::class, 'clone'])->name('categoriesClone');
Route::post('/{webmasterId}/categories/{id}/update', [CategoriesController::class, 'update'])->name('categoriesUpdate');
Route::post('/{webmasterId}/categories/{id}/seo', [CategoriesController::class, 'seo'])->name('categoriesSEOUpdate');
Route::get('/{webmasterId}/categories/destroy/{id?}', [CategoriesController::class, 'destroy'])->name('categoriesDestroy');
Route::post('/{webmasterId}/categories/updateAll', [CategoriesController::class, 'updateAll'])->name('categoriesUpdateAll');

// Topics
Route::get('/{webmasterId}/topics', [TopicsController::class, 'index'])->name('topics');
Route::post('/topics-list', [TopicsController::class, 'list'])->name('topicsList');
Route::get('/{webmasterId}/view/{id}', [TopicsController::class, 'view'])->name('topicView');
Route::get('/{webmasterId}/topics/create', [TopicsController::class, 'create'])->name('topicsCreate');
Route::post('/{webmasterId}/topics/store', [TopicsController::class, 'store'])->name('topicsStore');
Route::get('/{webmasterId}/topics/{id}/edit', [TopicsController::class, 'edit'])->name('topicsEdit');
Route::get('/{webmasterId}/topics/{id}/clone', [TopicsController::class, 'clone'])->name('topicsClone');
Route::post('/{webmasterId}/topics/{id}/update', [TopicsController::class, 'update'])->name('topicsUpdate');
Route::get('/{webmasterId}/topics/destroy/{id?}', [TopicsController::class, 'destroy'])->name('topicsDestroy');
Route::post('/{webmasterId}/topics/updateAll', [TopicsController::class, 'updateAll'])->name('topicsUpdateAll');
Route::get('/{webmasterId}/print', [TopicsController::class, 'print'])->name('topicsPrint');
// Topics :SEO
Route::post('/{webmasterId}/topics/{id}/seo', [TopicsController::class, 'seo'])->name('topicsSEOUpdate');
// Topics :Photos
Route::post('/topics/upload', [TopicsController::class, 'upload'])->name('topicsPhotosUpload');
Route::post('/{webmasterId}/topics/{id}/photos', [TopicsController::class, 'photos'])->name('topicsPhotosEdit');
Route::get('/{webmasterId}/topics/{id}/photos/{photo_id}/destroy',
    [TopicsController::class, 'photosDestroy'])->name('topicsPhotosDestroy');
Route::post('/{webmasterId}/topics/{id}/photos/updateAll',
    [TopicsController::class, 'photosUpdateAll'])->name('topicsPhotosUpdateAll');


Route::post('/topics-import', [TopicsController::class, 'import'])->name('topicsImport');


// Topics :Files
Route::get('/{webmasterId}/topics/{id}/files', [TopicsController::class, 'topicsFiles'])->name('topicsFiles');
Route::get('/{webmasterId}/topics/{id}/files/create',
    [TopicsController::class, 'filesCreate'])->name('topicsFilesCreate');
Route::post('/{webmasterId}/topics/{id}/files/store',
    [TopicsController::class, 'filesStore'])->name('topicsFilesStore');
Route::get('/{webmasterId}/topics/{id}/files/{file_id}/edit',
    [TopicsController::class, 'filesEdit'])->name('topicsFilesEdit');
Route::post('/{webmasterId}/topics/{id}/files/{file_id}/update',
    [TopicsController::class, 'filesUpdate'])->name('topicsFilesUpdate');
Route::get('/{webmasterId}/topics/{id}/files/destroy/{file_id}',
    [TopicsController::class, 'filesDestroy'])->name('topicsFilesDestroy');
Route::post('/{webmasterId}/topics/{id}/files/updateAll',
    [TopicsController::class, 'filesUpdateAll'])->name('topicsFilesUpdateAll');


// Topics :Related
Route::get('/{webmasterId}/topics/{id}/related', [TopicsController::class, 'topicsRelated'])->name('topicsRelated');
Route::get('/relatedLoad/{id}', [TopicsController::class, 'topicsRelatedLoad'])->name('topicsRelatedLoad');
Route::get('/{webmasterId}/topics/{id}/related/create',
    [TopicsController::class, 'relatedCreate'])->name('topicsRelatedCreate');
Route::post('/{webmasterId}/topics/{id}/related/store',
    [TopicsController::class, 'relatedStore'])->name('topicsRelatedStore');
Route::get('/{webmasterId}/topics/{id}/related/destroy/{related_id}',
    [TopicsController::class, 'relatedDestroy'])->name('topicsRelatedDestroy');
Route::post('/{webmasterId}/topics/{id}/related/updateAll',
    [TopicsController::class, 'relatedUpdateAll'])->name('topicsRelatedUpdateAll');
// Topics :Comments
Route::get('/{webmasterId}/topics/{id}/comments', [TopicsController::class, 'topicsComments'])->name('topicsComments');
Route::get('/{webmasterId}/topics/{id}/comments/create',
    [TopicsController::class, 'commentsCreate'])->name('topicsCommentsCreate');
Route::post('/{webmasterId}/topics/{id}/comments/store',
    [TopicsController::class, 'commentsStore'])->name('topicsCommentsStore');
Route::get('/{webmasterId}/topics/{id}/comments/{comment_id}/edit',
    [TopicsController::class, 'commentsEdit'])->name('topicsCommentsEdit');
Route::post('/{webmasterId}/topics/{id}/comments/{comment_id}/update',
    [TopicsController::class, 'commentsUpdate'])->name('topicsCommentsUpdate');
Route::get('/{webmasterId}/topics/{id}/comments/destroy/{comment_id}',
    [TopicsController::class, 'commentsDestroy'])->name('topicsCommentsDestroy');
Route::post('/{webmasterId}/topics/{id}/comments/updateAll',
    [TopicsController::class, 'commentsUpdateAll'])->name('topicsCommentsUpdateAll');
// Topics :Maps
Route::get('/{webmasterId}/topics/{id}/maps', [TopicsController::class, 'topicsMaps'])->name('topicsMaps');
Route::get('/{webmasterId}/topics/{id}/maps/create', [TopicsController::class, 'mapsCreate'])->name('topicsMapsCreate');
Route::post('/{webmasterId}/topics/{id}/maps/store', [TopicsController::class, 'mapsStore'])->name('topicsMapsStore');
Route::get('/{webmasterId}/topics/{id}/maps/{map_id}/edit', [TopicsController::class, 'mapsEdit'])->name('topicsMapsEdit');
Route::post('/{webmasterId}/topics/{id}/maps/{map_id}/update',
    [TopicsController::class, 'mapsUpdate'])->name('topicsMapsUpdate');
Route::get('/{webmasterId}/topics/{id}/maps/destroy/{map_id}',
    [TopicsController::class, 'mapsDestroy'])->name('topicsMapsDestroy');
Route::post('/{webmasterId}/topics/{id}/maps/updateAll',
    [TopicsController::class, 'mapsUpdateAll'])->name('topicsMapsUpdateAll');

Route::post('/table-columns-update', [TopicsController::class, 'update_table_columns'])->name('tableColumnsUpdate');

// keditor
Route::get('/keditor/{topic_id?}', [TopicsController::class, 'keditor'])->name('keditor');
Route::get('/keditor-snippets', [TopicsController::class, 'keditor_snippets'])->name('keditorSnippets');
Route::post('/keditor-save', [TopicsController::class, 'keditor_save'])->name('keditorSave');

// Contacts Groups
Route::post('/contacts/storeGroup', [ContactsController::class, 'storeGroup'])->name('contactsStoreGroup');
Route::get('/contacts/{id}/editGroup', [ContactsController::class, 'editGroup'])->name('contactsEditGroup');
Route::post('/contacts/{id}/updateGroup', [ContactsController::class, 'updateGroup'])->name('contactsUpdateGroup');
Route::get('/contacts/destroyGroup/{id}', [ContactsController::class, 'destroyGroup'])->name('contactsDestroyGroup');
// Contacts
Route::get('/contacts/{group_id?}', [ContactsController::class, 'index'])->name('contacts');
Route::post('/contacts/store', [ContactsController::class, 'store'])->name('contactsStore');
Route::post('/contacts/search', [ContactsController::class, 'search'])->name('contactsSearch');
Route::get('/contacts/{id}/edit', [ContactsController::class, 'edit'])->name('contactsEdit');
Route::post('/contacts/{id}/update', [ContactsController::class, 'update'])->name('contactsUpdate');
Route::get('/contacts/destroy/{id}', [ContactsController::class, 'destroy'])->name('contactsDestroy');
Route::post('/contacts/updateAll', [ContactsController::class, 'updateAll'])->name('contactsUpdateAll');

// WebMails Groups
Route::post('/webmails/storeGroup', [WebmailsController::class, 'storeGroup'])->name('webmailsStoreGroup');
Route::get('/webmails/{id}/editGroup', [WebmailsController::class, 'editGroup'])->name('webmailsEditGroup');
Route::post('/webmails/{id}/updateGroup', [WebmailsController::class, 'updateGroup'])->name('webmailsUpdateGroup');
Route::get('/webmails/destroyGroup/{id}', [WebmailsController::class, 'destroyGroup'])->name('webmailsDestroyGroup');
// WebMails
Route::post('/webmails/store', [WebmailsController::class, 'store'])->name('webmailsStore');
Route::post('/webmails/search', [WebmailsController::class, 'search'])->name('webmailsSearch');
Route::get('/webmails/{id}/edit', [WebmailsController::class, 'edit'])->name('webmailsEdit');
Route::get('/webmails/{group_id?}/{wid?}/{stat?}/{contact_email?}', [WebmailsController::class, 'index'])->name('webmails');
Route::post('/webmails/{id}/update', [WebmailsController::class, 'update'])->name('webmailsUpdate');
Route::get('/webmails/destroy/{id}', [WebmailsController::class, 'destroy'])->name('webmailsDestroy');
Route::post('/webmails/updateAll', [WebmailsController::class, 'updateAll'])->name('webmailsUpdateAll');

// Calendar
Route::get('/calendar', [EventsController::class, 'index'])->name('calendar');
Route::get('/calendar/create', [EventsController::class, 'create'])->name('calendarCreate');
Route::post('/calendar/store', [EventsController::class, 'store'])->name('calendarStore');
Route::get('/calendar/{id}/edit', [EventsController::class, 'edit'])->name('calendarEdit');
Route::post('/calendar/{id}/update', [EventsController::class, 'update'])->name('calendarUpdate');
Route::get('/calendar/destroy/{id}', [EventsController::class, 'destroy'])->name('calendarDestroy');
Route::get('/calendar/updateAll', [EventsController::class, 'updateAll'])->name('calendarUpdateAll');
Route::post('/calendar/{id}/extend', [EventsController::class, 'extend'])->name('calendarExtend');

// Analytics
Route::get('/ip/{ip_code?}', [AnalyticsController::class, 'ip'])->name('visitorsIP');
Route::post('/ip/search', [AnalyticsController::class, 'search'])->name('visitorsSearch');
Route::post('/analytics/{stat}', [AnalyticsController::class, 'filter'])->name('analyticsFilter');
Route::get('/analytics/{stat?}', [AnalyticsController::class, 'index'])->name('analytics');
Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');

// Users & Permissions
Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('/users/create/', [UsersController::class, 'create'])->name('usersCreate');
Route::post('/users/store', [UsersController::class, 'store'])->name('usersStore');
Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('usersEdit');
Route::post('/users/{id}/update', [UsersController::class, 'update'])->name('usersUpdate');
Route::get('/users/destroy/{id}', [UsersController::class, 'destroy'])->name('usersDestroy');
Route::post('/users/updateAll', [UsersController::class, 'updateAll'])->name('usersUpdateAll');

Route::get('/users/permissions/create/', [UsersController::class, 'permissions_create'])->name('permissionsCreate');
Route::post('/users/permissions/store', [UsersController::class, 'permissions_store'])->name('permissionsStore');
Route::get('/users/permissions/{id}/edit', [UsersController::class, 'permissions_edit'])->name('permissionsEdit');
Route::post('/users/permissions/{id}/update', [UsersController::class, 'permissions_update'])->name('permissionsUpdate');
Route::post('/users/permissions/{id}/save', [UsersController::class, 'update_custom_home'])->name('permissionsHomePageUpdate');
Route::get('/users/permissions/destroy/{id}', [UsersController::class, 'permissions_destroy'])->name('permissionsDestroy');

Route::post('/permissions-links/store', [UsersController::class, 'links_store'])->name('customLinksStore');
Route::post('/permissions-links/update', [UsersController::class, 'links_update'])->name('customLinksUpdate');
Route::get('/permissions-links/edit/{id?}/{p_id?}', [UsersController::class, 'links_edit'])->name('customLinksEdit');
Route::get('/permissions-links/destroy/{id?}/{p_id?}', [UsersController::class, 'links_destroy'])->name('customLinksDestroy');
Route::get('/permissions-links/list/{p_id?}', [UsersController::class, 'links_list'])->name('customLinksList');

// Kabana Package
Route::get('/cabana', [CabanaPackagesController::class, 'index'])->name('cabana');
Route::post('/cabana/data', [CabanaPackagesController::class, 'getData'])->name('cabana.data');
Route::get('/cabana-package/create/', [CabanaPackagesController::class, 'create'])->name('cabanaCreate');
Route::get('/cabana-packages/{id}/edit', [CabanaPackagesController::class, 'edit'])->name('cabanaEdit');
Route::post('/cabana-packages/store', [CabanaPackagesController::class, 'store'])->name('cabanaStore');
Route::post('/cabana-packages/{id}/update', [CabanaPackagesController::class, 'update'])->name('cabanaUpdate');
Route::get('/cabana-packages/destroy/{id}', [CabanaPackagesController::class, 'destroy'])->name('cabanaDestroy');


Route::get('/kabanasetting', [KabanaSettingController::class, 'index'])->name('kabanasetting');
Route::get('/kabanaddons', [KabanaSettingController::class, 'cabanAddon'])->name('kabanaddons');
Route::get('/kabanaddons/{id}/edit', [KabanaSettingController::class, 'edit'])->name('kabanaaddonEdit');
Route::get('/kabanasetting/create/', [KabanaSettingController::class, 'create'])->name('kabanasettingCreate');
Route::post('/kabanasetting/store', [KabanaSettingController::class, 'store'])->name('kabanasettingStore');
Route::get('/kabanasetting/{id}/edit', [KabanaSettingController::class, 'edit'])->name('kabanasettingEdit');
Route::post('/kabanasetting/{id}/update', [KabanaSettingController::class, 'update'])->name('kabanasettingUpdate');
Route::get('/kabanasetting/destroy/{id}', [KabanaSettingController::class, 'destroy'])->name('kabanasettingDestroy');
Route::post('/kabanasetting/updateAll', [KabanaSettingController::class, 'updateAll'])->name('kabanasettingUpdateAll');
Route::get('/kabana-orders', [OrderController::class, 'getCabanaOrders'])->name('kabanaorders');
Route::post('/cabana-orders', [OrderController::class, 'getOrders'])->name('cabanaorders.data');
Route::get('/kabana-orders-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('kabanaordersdetail');
//Kabana Addons
Route::post('/cabana/store', [KabanaAddonsController::class, 'store'])->name('cabanaAddonStore');
Route::post('/cabana-addon', [KabanaAddonsController::class, 'getData'])->name('cabanaaddon.data');
// Birthday Packages
Route::get('/birthday_packages', [BirthdayPackagesController::class, 'index'])->name('birthdaypackages');
Route::post('/birthday-package', [BirthdayPackagesController::class, 'getData'])->name('birthdaypackages.data');
Route::get('/birthday_packages/{id}/edit', [BirthdayPackagesController::class, 'edit'])->name('birthdaypackagesEdit');
Route::get('/birthday_packages/create/', [BirthdayPackagesController::class, 'create'])->name('birthdaypackagesCreate');
Route::post('/birthday_packages/store', [BirthdayPackagesController::class, 'store'])->name('birthdaypackagesStore');
Route::post('/birthday_packages/{id}/update', [BirthdayPackagesController::class, 'update'])->name('birthdaypackagesUpdate');
Route::get('/birthday_packages/destroy/{id}', [BirthdayPackagesController::class, 'destroy'])->name('birthdaypackagesDestroy');
Route::post('/birthday_packages/updateAll', [BirthdayPackagesController::class, 'updateAll'])->name('birthdaypackagesUpdateAll');
Route::get('/birthday-orders', [OrderController::class, 'getBirthdayOrders'])->name('birthdayorders');
Route::post('/birthday-order', [OrderController::class, 'getOrders'])->name('birthdayorders.data');
Route::get('/birthday-orders-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('birthdayordersdetail');
// Birthday Addons
Route::get('/birthday_addons', [BirthdayAddonsController::class, 'index'])->name('birthdayaddon');
Route::post('/birthday-addons', [BirthdayAddonsController::class, 'getData'])->name('birthdayaddon.data');
Route::get('/birthday_addons/{id}/edit', [BirthdayAddonsController::class, 'edit'])->name('birthdayaddonEdit');
Route::post('/birthday_addons/store', [BirthdayAddonsController::class, 'store'])->name('birthdayaddonStore');

//Generl Tickets Packages
Route::get('/general-ticket-packages', [GeneralTicketPackagesController::class, 'index'])->name('generalticketpackages');
Route::post('/general-ticket', [GeneralTicketPackagesController::class, 'getData'])->name('generalticketpackages.data');
Route::get('/general-ticket-packages/{id}/edit', [GeneralTicketPackagesController::class, 'edit'])->name('generalticketpackagesEdit');
Route::get('/general-ticket-packages/create/', [GeneralTicketPackagesController::class, 'create'])->name('generalticketpackagesCreate');
Route::post('/general-ticket-packages/store', [GeneralTicketPackagesController::class, 'store'])->name('generalticketpackagesStore');
Route::post('/general-ticket-packages/{id}/update', [GeneralTicketPackagesController::class, 'update'])->name('generalticketpackagesUpdate');
Route::get('/general-ticket-packages/destroy/{id}', [GeneralTicketPackagesController::class, 'destroy'])->name('generalticketpackagesDestroy');

// General Tickets
Route::get('/general-tickets', [GeneralTicketController::class, 'index'])->name('generaltickets');
Route::get('/general-tickets/{slug}/edit', [GeneralTicketController::class, 'edit'])->name('generalticketsEdit');
Route::get('/general-tickets/create/', [GeneralTicketController::class, 'create'])->name('generalticketsCreate');
Route::post('/general-tickets/store', [GeneralTicketController::class, 'store'])->name('generalticketsStore');
Route::get('/general-tickets/destroy/{slug}', [GeneralTicketController::class, 'destroy'])->name('generalticketsDestroy');
Route::get('/general-ticket-orders', [OrderController::class, 'getTicketingOrders'])->name('generalticketsorders');
Route::post('/general-ticket-order', [OrderController::class, 'getOrders'])->name('generalticketsorders.data');
Route::get('/general-ticket-orders-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('generalticketsordersdetail');
// General Tickets Addons
Route::get('/general-tickets-addon', [GeneralTicketAddonController::class, 'index'])->name('generalticketsaddon');
Route::post('/general-ticket-addon', [GeneralTicketAddonController::class, 'getData'])->name('generalticketsaddon.data');
Route::get('/general-tickets-addon/create/', [GeneralTicketAddonController::class, 'create'])->name('generalticketsaddonCreate');
Route::post('/general-tickets-addon/store', [GeneralTicketAddonController::class, 'store'])->name('generalticketsaddonStore');
Route::get('/general-tickets-addon/{id}/edit', [GeneralTicketAddonController::class, 'edit'])->name('generalticketsaddonEdit');
Route::post('/general-tickets-addon/{id}/update', [GeneralTicketAddonController::class, 'update'])->name('generalticketsaddonUpdate');
Route::get('/general-tickets-addon/destroy/{id}', [GeneralTicketAddonController::class, 'destroy'])->name('generalticketsaddonDestroy');
// Season Pass
Route::get('/season-pass', [SeasonPassController::class, 'index'])->name('seasonpass');
Route::post('/season-pass-package', [SeasonPassController::class, 'getData'])->name('seasonpass.data');
Route::get('/season-pass/{slug}/edit', [SeasonPassController::class, 'edit'])->name('seasonpassEdit');
Route::get('/season-pass/create/', [SeasonPassController::class, 'create'])->name('seasonpassCreate');
Route::post('/season-pass/store', [SeasonPassController::class, 'store'])->name('seasonpassStore');
Route::post('/season-pass/{id}/update', [SeasonPassController::class, 'update'])->name('seasonpassUpdate');
Route::get('/season-pass/destroy/{slug}', [SeasonPassController::class, 'destroy'])->name('seasonpassDestroy');
Route::get('/season-pass-orders', [OrderController::class, 'getSeasonPassOrders'])->name('seasonpassorders');
Route::post('/season-pass-order', [OrderController::class, 'getOrders'])->name('seasonpassorders.data');
Route::get('/season-pass-orders-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('seasonpassordersdetail');
// Season Pass Addons
Route::get('/season-pass-addons', [SeasonPassAddonsController::class, 'index'])->name('seasonpassaddon');
Route::post('/season-pass-addon', [SeasonPassAddonsController::class, 'getData'])->name('seasonpassaddon.data');
Route::get('/season-pass-addons/create/', [SeasonPassAddonsController::class, 'create'])->name('seasonpassaddonCreate');
Route::get('/season-pass-addons/{id}/edit', [SeasonPassAddonsController::class, 'edit'])->name('seasonpassaddonEdit');
Route::post('/season-pass-addons/store', [SeasonPassAddonsController::class, 'store'])->name('seasonpassaddonStore');
Route::post('/season-pass-addons/{id}/update', [SeasonPassAddonsController::class, 'update'])->name('seasonpassaddonUpdate');
Route::get('/season-pass-addons/destroy/{id}', [SeasonPassAddonsController::class, 'destroy'])->name('seasonpassaddonDestroy');
//Offer Creation Packages
Route::get('/offer-packages', [OfferCreationPackagesController::class, 'index'])->name('offercreationpackages');
Route::post('/offer-ticket', [OfferCreationPackagesController::class, 'getData'])->name('offercreationpackages.data');
Route::get('/offer-packages/{id}/edit', [OfferCreationPackagesController::class, 'edit'])->name('offercreationpackagesEdit');
Route::get('/offer-packages/create/', [OfferCreationPackagesController::class, 'create'])->name('offercreationpackagesCreate');
Route::post('/offer-packages/store', [OfferCreationPackagesController::class, 'store'])->name('offercreationpackagesStore');
Route::post('/offer-packages/{id}/update', [OfferCreationPackagesController::class, 'update'])->name('offercreationpackagesUpdate');
Route::get('/offer-packages/destroy/{id}', [OfferCreationPackagesController::class, 'destroy'])->name('offercreationpackagesDestroy');
Route::get('/offer-packages-orders', [OrderController::class, 'getOfferCreationOrders'])->name('offercreationpackagesorders');
Route::post('/offer-packages-order', [OrderController::class, 'getOrders'])->name('offercreationpackagesorders.data');
Route::get('/offer-packages-orders-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('offercreationpackagesordersdetail');
// Offer Creation Addons
Route::get('/offer-addon', [OfferAddonController::class, 'index'])->name('offeraddon');
Route::post('/offer-addon', [OfferAddonController::class, 'getData'])->name('offeraddon.data');
Route::get('/offer-addon/create/', [OfferAddonController::class, 'create'])->name('offeraddonCreate');
Route::post('/offer-addon/store', [OfferAddonController::class, 'store'])->name('offeraddonStore');
Route::get('/offer-addon/{id}/edit', [OfferAddonController::class, 'edit'])->name('offeraddonEdit');
Route::post('/offer-addon/{id}/update', [OfferAddonController::class, 'update'])->name('offeraddonUpdate');
Route::get('/offer-addon/destroy/{id}', [OfferAddonController::class, 'destroy'])->name('offeraddonDestroy');
// Transactions
Route::get('/transaction', [OrderController::class, 'getTransactions'])->name('transactionorders');
Route::post('/transactions', [OrderController::class, 'getTransaction'])->name('transaction.data');
Route::get('update/transaction', [OrderController::class, 'getUpdateUpgradeTransactions'])->name('updatetransactionorders');
Route::post('update/transactions', [OrderController::class, 'getUpdateTransaction'])->name('updatetransaction.data');
Route::get('/transactions-detail/{slug}', [OrderController::class, 'getByOrderSlug'])->name('transactionordersdetail');
Route::get('/transactions/orders/print', [OrderController::class, 'print'])->name('transactionPrint');
Route::get('/transactions/orders/cabanaprint', [OrderController::class, 'printCabana'])->name('cabanaPrint');
Route::get('/transactions/orders/birthdayprint', [OrderController::class, 'printBirthday'])->name('birthdayPrint');
Route::get('/transactions/orders/generalprint', [OrderController::class, 'printGeneralTicket'])->name('generalTicketPrint');
Route::get('/transactions/orders/seasonpassprint', [OrderController::class, 'printSeasonPass'])->name('seasonPassPrint');
Route::get('/transactions/orders/offercreationprint', [OrderController::class, 'printOfferCreation'])->name('offerCreationPrint');
Route::get('/packages/by-type', [OrderController::class, 'getPackagesByType'])->name('getPackagesByType');
// Coupons Code
Route::get('/coupon', [CouponController::class, 'index'])->name('coupon');
Route::post('/coupon/data', [CouponController::class, 'getData'])->name('coupon.data');
Route::get('/coupon/create/', [CouponController::class, 'create'])->name('couponCreate');
Route::get('/coupon/{id}/edit', [CouponController::class, 'edit'])->name('couponEdit');
Route::post('/coupon/store', [CouponController::class, 'store'])->name('couponStore');
Route::post('/coupon/{id}/update', [CouponController::class, 'update'])->name('couponUpdate');
Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('couponDestroy');
Route::get('/coupon/get-packages-by-type', [CouponController::class, 'getPackagesByType'])->name('get.packages.by.type');
Route::get('/coupon/get-packages-products', [CouponController::class, 'getPackagesProducts'])->name('get.packages.products');
// Email Templates
Route::get('/email-template', [EmailController::class, 'index'])->name('emailTemplate');
Route::post('/email-template/data', [EmailController::class, 'getData'])->name('email.data');
Route::get('/email-template/create/', [EmailController::class, 'create'])->name('emailTemplateCreate');
Route::get('/email-template/{id}/edit', [EmailController::class, 'edit'])->name('emailTemplateEdit');
Route::post('/email-template/store', [EmailController::class, 'store'])->name('emailTemplateStore');
Route::post('/email-template/{id}/update', [EmailController::class, 'update'])->name('emailTemplateUpdate');
Route::get('/email-template/destroy/{id}', [EmailController::class, 'destroy'])->name('emailTemplateDestroy');
Route::get('/smtp-configure', [EmailController::class, 'smtpConfigure'])->name('smtpConfigure');
Route::post('/smtp-configure/update', [EmailController::class, 'updateSmtp'])->name('smtpUpdate');
// Email Logs Templates
Route::get('/email-logs', [EmailLogsController::class, 'index'])->name('emailsLogs');
Route::post('/email-logs/data', [EmailLogsController::class, 'getData'])->name('emailsLogs.data');
Route::get('/email/{id}/view', [EmailLogsController::class, 'show'])->name('emailShow');
Route::get('/resend/{id}/email', [EmailLogsController::class, 'resendmail'])->name('emailResend');
// Orders Logs Templates
Route::get('/orders-logs', [LogController::class, 'index'])->name('ordersLogs');
Route::get('/orders-failed-logs', [LogController::class, 'orderFailedLogs'])->name('ordersfailedLogs');
Route::get('/payment-logs', [LogController::class, 'paymentLogs'])->name('paymentLogs');
Route::get('/payment-failed-logs', [LogController::class, 'paymentFailedLogs'])->name('paymentfailLogs');
Route::post('/orders-logs/data', [LogController::class, 'getData'])->name('ordersLogs.data');
Route::get('/orders-logs/{id}/view', [LogController::class, 'show'])->name('ordersLogsShow');
// Menus
Route::post('/menus/store/parent', [MenusController::class, 'storeMenu'])->name('parentMenusStore');
Route::get('/menus/parent/{id}/edit', [MenusController::class, 'editMenu'])->name('parentMenusEdit');
Route::post('/menus/{id}/update/{ParentMenuId}', [MenusController::class, 'updateMenu'])->name('parentMenusUpdate');
Route::get('/menus/parent/destroy/{id}', [MenusController::class, 'destroyMenu'])->name('parentMenusDestroy');

Route::get('/menus/{ParentMenuId?}', [MenusController::class, 'index'])->name('menus');
Route::get('/menus/create/{ParentMenuId?}', [MenusController::class, 'create'])->name('menusCreate');
Route::post('/menus/store/{ParentMenuId?}', [MenusController::class, 'store'])->name('menusStore');
Route::get('/menus/{id}/edit/{ParentMenuId?}', [MenusController::class, 'edit'])->name('menusEdit');
Route::post('/menus/{id}/update', [MenusController::class, 'update'])->name('menusUpdate');
Route::get('/menus/destroy/{id}', [MenusController::class, 'destroy'])->name('menusDestroy');
Route::post('/menus/updateAll', [MenusController::class, 'updateAll'])->name('menusUpdateAll');

// Tags
Route::get('/tags', [TagController::class, 'index'])->name('tags');
Route::post('/tags/list', [TagController::class, 'list'])->name('tagsList');
Route::get('/tags/create', [TagController::class, 'create'])->name('tagsCreate');
Route::post('/tags/store', [TagController::class, 'store'])->name('tagsStore');
Route::get('/tags/edit/{id?}', [TagController::class, 'edit'])->name('tagsEdit');
Route::post('/tags/update', [TagController::class, 'update'])->name('tagsUpdate');
Route::get('/tags/destroy/{id?}', [TagController::class, 'destroy'])->name('tagsDestroy');
Route::post('/tags/updateAll', [TagController::class, 'updateAll'])->name('tagsUpdateAll');

// popups
Route::get('/popups', [PopupController::class, 'index'])->name('popups');
Route::get('/popups/create', [PopupController::class, 'create'])->name('popupsCreate');
Route::post('/popups/store', [PopupController::class, 'store'])->name('popupsStore');
Route::get('/popups/edit/{id}', [PopupController::class, 'edit'])->name('popupsEdit');
Route::post('/popups/update', [PopupController::class, 'update'])->name('popupsUpdate');
Route::get('/popups/destroy/{id?}', [PopupController::class, 'destroy'])->name('popupsDestroy');
Route::post('/popups/updateAll', [PopupController::class, 'updateAll'])->name('popupsUpdateAll');

// File manager
Route::get('file-manager', [FileManagerController::class, 'index'])->name('FileManager');
Route::get('files-manager', [FileManagerController::class, 'manager'])->name('FilesManager');

// No Permission
Route::get('/403', [DashboardController::class, 'page_403']);
Route::get('/oops', [DashboardController::class, 'page_oops'])->name('NoPermission');

// Clear Cache
Route::get('/cache-clear', [DashboardController::class, 'cache_clear'])->name('cacheClear');
Route::get('/cache-cleared', [DashboardController::class, 'cache_cleared'])->name('cacheCleared');
// logout
Route::get('/logout', [DashboardController::class, 'logout'])->name('adminLogout');
