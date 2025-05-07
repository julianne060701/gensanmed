<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'GENSANMED',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,
    'favicon' => 'LOGO.ico',

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>GENSANMED</b>',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 120,
            'height' => 120,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

'classes_auth_card' => '',
'classes_auth_header' => 'bg-gradient-info',
'classes_auth_body' => '',
'classes_auth_footer' => 'text-center',
'classes_auth_icon' => 'fa-lg text-info',
'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => 'bg-gradient',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

   'menu' => [
    [
        'type' => 'navbar-notification',
        'id' => 'my-notification',
        'icon' => 'fas fa-bell',
        'icon_color' => 'warning',
        'label' => '0',  // Default value as a fallback
        'label_color' => 'danger',
        'url' => 'notifications/show',
        'topnav_right' => true,
        'dropdown_mode' => true,
        'dropdown_flabel' => 'See notifications',
        'update_cfg' => [
            'url' => 'notifications/get',
            'period' => 30,
        ],
    ],
        
        

        // -----------------------------------------------------
            // Administrator Sidebar Items (Only for Administrators)
            // -----------------------------------------------------
            [
                'header' => 'ADMIN PANEL',
                'can'    => 'view-admin-menu',
            ],
            [
                'text'  => 'Dashboard',
                'icon'  => 'fas fa-fw fa-tachometer-alt',
                'url'   => 'admin/dashboard',
                'can'   => 'view-admin-menu',
            ],
            [
                'text'    => 'Manage Schedule',
                'icon'    => 'fas fa-calendar-alt',
                'can'     => 'view-admin-menu',
                'submenu' => [
                    [
                        'text'        => 'Calendar Schedule',
                        'url'         => 'admin/schedule',
                        'icon'        => 'far fa-fw fa-calendar',
                        'label_color' => 'success',
                        'can'         => 'view-admin-menu',
                    ],
                    [
                        'text' => 'SMS Schedule',
                        'icon' => 'fas fa-envelope',
                        'url'  => 'admin/sms',
                        'can'  => 'view-admin-menu',
                    ],
                ],
            ],
           
            [
                'text'  => 'Manage User',
                'icon'  => 'fas fa-user',
                'url'   => 'admin/user',
                'can'   => 'view-admin-menu',
            ],
            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'admin/ticketing',
                'can'   => 'view-admin-menu',
            ],
            [
                'text'    => 'Manage Purchase',
                'icon'    => 'fas fa-cart-plus',
                'can'     => 'view-admin-menu',
                'submenu' => [
                    [
                        'text' => 'Purchase Order',
                        'icon' => 'fas fa-file-invoice',
                        'url'  => 'admin/purchase',
                        'can'  => 'view-admin-menu',
                    ],
                    [
                        'text' => 'Purchase Request',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'admin/purchase_request',
                        'can'  => 'view-admin-menu',
                    ],
                ],
            ],
                       
            [
                'text'    => 'Manage Reports',
                'icon'    => 'fas fa-fw fa-file-alt',
                'can'     => 'view-admin-menu',
                'submenu' => [
                    
                        [
                            'text'  => 'Ticketing Report',
                            'icon'  => 'fas fa-fw fa-file-alt',
                            'url'   => 'admin/reports/ticketing_report',
                            'can'   => 'view-admin-menu',
                        ],
                    
                    [
                        'text' => 'Purchase Request Report',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'admin/reports/purchase_request',
                        'can'  => 'view-admin-menu',
                    ],
                    [
                        'text' => 'Purchase Order Report',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'admin/reports/purchase_order',
                        'can'  => 'view-admin-menu',
                    ],
                ],
            ],

             // -----------------------------------------------------
            // MMO Sidebar Items (Only for MMO)
            // -----------------------------------------------------
            [
                'header' => 'MMO PANEL',
                'can'    => 'view-mmo-menu',
            ],
            [
                'text'  => 'Dashboard',
                'icon'  => 'fas fa-fw fa-tachometer-alt',
                'url'   => 'mmo/dashboard',
                'can'   => 'view-mmo-menu',
            ],
            [
                'text'    => 'Manage Schedule',
                'icon'    => 'fas fa-calendar-alt',
                'can'     => 'view-mmo-menu',
                'submenu' => [
                    [
                        'text'        => 'Calendar Schedule',
                        'url'         => 'mmo/schedule',
                        'icon'        => 'far fa-fw fa-calendar',
                        'label_color' => 'success',
                        'can'         => 'view-mmo-menu',
                    ],
                ],
            ],
            [
                'text'    => 'Manage Purchase',
                'icon'    => 'fas fa-cart-plus',
                'can'     => 'view-mmo-menu',
                'submenu' => [
        
                    [
                        'text' => 'Purchase Request',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'mmo/purchase_request',
                        'can'  => 'view-mmo-menu',
                    ],
                   
                    [
                        'text' => 'Purchase Order Report',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'admin/reports/purchase_order',
                        'can'  => 'view-admin-menu',
                    ],
                ],
            ],
           

            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'mmo/ticketing',
                'can'   => 'view-mmo-menu',
            ],
                       
            [
                'text'    => 'Manage Reports',
                'icon'    => 'fas fa-fw fa-file-alt',
                'can'     => 'view-mmo-menu',
                'submenu' => [
                    
                        [
                            'text'  => 'Ticketing Report',
                            'icon'  => 'fas fa-fw fa-file-alt',
                            'url'   => 'mmo/reports/ticketing_report',
                            'can'   => 'view-mmo-menu',
                        ],
                    
                        [
                            'text' => 'Purchase Request Report',
                            'icon' => 'fas fa-file-alt',
                            'url'  => 'mmo/reports/purchase_request',
                            'can'  => 'view-mmo-menu',
                        ],
                        [
                            'text' => 'Purchase Order Report',
                            'icon' => 'fas fa-file-alt',
                            'url'  => 'mmo/reports/purchase_order',
                            'can'  => 'view-mmo-menu',
                        ],
                ],
            ],

              // -----------------------------------------------------
            // IT Sidebar Items (Only for IT)
            // -----------------------------------------------------
            [
                'header' => 'IT PANEL',
                'can'    => 'view-it-menu',
            ],
            [
                'text'        => 'Schedule',
                'url'         => 'IT/home',
                'icon'        => 'far fa-fw fa-calendar',
                'label_color' => 'success',
                'can'         => 'view-HIMS-menu',
            ],
            [
                'text'  => 'Manage User',
                'icon'  => 'fas fa-user',
                'url'   => 'IT/user',
                'can'   => 'view-HIMS-menu',
            ],
            // [
            //     'text'    => 'Manage Purchase',
            //     'icon'    => 'fas fa-cart-plus',
            //     'can'     => 'view-it-menu',
            //     'submenu' => [
            //         [
            //             'text' => 'Purchase Order',
            //             'icon' => 'fas fa-file-invoice',
            //             'url'  => 'IT/purchase_order',
            //             'can'  => 'view-it-menu',
            //         ],
            //         [
            //             'text' => 'Purchase Request',
            //             'icon' => 'fas fa-file-alt',
            //             'url'  => 'IT/purchase_request',
            //             'can'  => 'view-it-menu',
            //         ],
            //     ],
            // ],
            
            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'IT/ticketing',
                'can'   => 'view-HIMS-menu',
            ],

            
            [
                'text'    => 'Manage Reports',
                'icon'    => 'fas fa-fw fa-file-alt',
                'can'     => 'view-HIMS-menu',
                'submenu' => [
                    
                        [
                            'text'  => 'Ticketing Report',
                            'icon'  => 'fas fa-fw fa-file-alt',
                            'url'   => 'IT/reports/ticketing_report',
                            'can'   => 'view-HIMS-menu',
                        ],
                ],
            ],
            

                  // -----------------------------------------------------
            // Staff Sidebar Items (Only for Staff)
            // -----------------------------------------------------
            [
                'header' => 'STAFF PANEL',
                'can'    => 'view-staff-menu',
            ],
            [
                'text'        => 'Schedule',
                'url'         => 'staff/home',
                'icon'        => 'far fa-fw fa-calendar',
                'label_color' => 'success',
                'can'         => 'view-staff-menu',
            ],
            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'staff/ticketing',
                'can'   => 'view-staff-menu',
            ],

            // -----------------------------------------------------
            // Engineer Sidebar Items (Only for Engineers)
            // -----------------------------------------------------
            [
                'header' => 'ENGINEERING PANEL',
                'can'    => 'view-engineer-menu',
            ],
            [
                'text'        => 'Schedule',
                'url'         => 'engineer/home',
                'icon'        => 'far fa-fw fa-calendar',
                'label_color' => 'success',
                'can'         => 'view-engineer-menu',
            ],
            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'engineer/ticketing',
                'can'   => 'view-engineer-menu',
            ],
            // [
            //     'text'  => 'PMS',
            //     'icon'  => 'fas fa-fw fa-ticket-alt',
            //     'url'   => 'engineer/pms',
            //     'can'   => 'view-engineer-menu',
            // ],
            [
                'text'    => 'Manage Reports',
                'icon'    => 'fas fa-fw fa-file-alt',
                'can'     => 'view-engineer-menu',
                'submenu' => [
                    
                        [
                            'text'  => 'Ticketing Report',
                            'icon'  => 'fas fa-fw fa-file-alt',
                            'url'   => 'engineer/ticketing_report',
                            'can'   => 'view-engineer-menu',
                        ],
                    
                ],
            ],
             // -----------------------------------------------------
            // Purchaser Sidebar Items (Only for Purchaser)
            // -----------------------------------------------------
            [
                'header' => 'PURCHASER PANEL',
                'can'    => 'view-purchaser-menu',
            ],
            [
                'text'        => 'Schedule',
                'url'         => 'purchaser/home',
                'icon'        => 'far fa-fw fa-calendar',
                'label_color' => 'success',
                'can'         => 'view-purchaser-menu',
            ],
            [
                'text'    => 'Manage Purchase',
                'icon'    => 'fas fa-cart-plus',
                'can'     => 'view-purchaser-menu',
                'submenu' => [
                    [
                        'text' => 'Purchase Order',
                        'icon' => 'fas fa-file-invoice',
                        'url'  => 'purchaser/purchase',
                        'can'  => 'view-purchaser-menu',
                    ],
                    [
                        'text' => 'Purchase Request',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'purchaser/purchase_request',
                        'can'  => 'view-purchaser-menu',
                    ],
                ],
            ],

              // -----------------------------------------------------
            // Head Sidebar Items (Only for Head Request)
            // -----------------------------------------------------
            [
                'header' => 'For Head Panel',
                'can'    => 'view-head-menu',
            ],
            [
                'text'        => 'Schedule',
                'url'         => 'head/home',
                'icon'        => 'far fa-fw fa-calendar',
                'label_color' => 'success',
                'can'         => 'view-head-menu',
            ],
            [
                'text'    => 'Manage Purchase',
                'icon'    => 'fas fa-cart-plus',
                'can'     => 'view-head-menu',
                'submenu' => [
        
                    [
                        'text' => 'Purchase Request',
                        'icon' => 'fas fa-file-alt',
                        'url'  => 'head/purchase_request',
                        'can'  => 'view-head-menu',
                    ],
                ],
            ],
                 
            [
                'text'  => 'Manage Ticket',
                'icon'  => 'fas fa-fw fa-ticket-alt',
                'url'   => 'head/ticketing',
                'can'   => 'view-head-menu',
            ],
       
        ],


    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
       // App\Menu\Filters\RoleFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
