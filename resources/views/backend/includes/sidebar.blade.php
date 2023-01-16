@php
    // echo '<ul>';
    // echo adminDynamicMenus($all_menus); 
    // echo '</ul>';
    // exit;
@endphp 

<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <x-utils.link :href="route('admin.dashboard')" style="color: #fff;">
            <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
            </svg>
        </x-utils.link>
        <x-utils.link :href="route('admin.dashboard')" style="color: #fff;">
            <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
                <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>
            </svg>
        </x-utils.link>
    </div><!--c-sidebar-brand-->

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-title" style="padding-bottom: 2px;">
            <img class="c-avatar-img" style="width: auto; margin-bottom: 10px;" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->email ?? '' }}">
            <h5 style="margin-bottom: 0px;">
                {{ $logged_in_user->name }}
            </h5>
        </li>

        <li class="c-sidebar-nav-dropdown">
            <x-utils.link
                href="#" 
                style="padding-top: 0px; padding-bottom: 0px;" 
                class="c-sidebar-nav-dropdown-toggle" 
                :text="$logged_in_user->email" />

            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        :href="route('frontend.user.account')"
                        class="c-sidebar-nav-link" 
                        :text="__('My Account')" 
                        :active="activeClass(Route::is('frontend.user.account'), 'c-active')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        class="c-sidebar-nav-link"
                        onclick="event.preventDefault();document.getElementById('logout-form-sidebar').submit();">
                        <x-slot name="text">
                            @lang('Logout')
                            <x-forms.post :action="route('frontend.auth.logout')" id="logout-form-sidebar" class="d-none" />
                        </x-slot>
                    </x-utils.link>
                </li>
            </ul>
        </li>
        
        {{-- <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.dashboard')"
                :active="activeClass(Route::is('admin.dashboard'), 'c-active')"
                icon="c-sidebar-nav-icon cil-speedometer"
                :text="__('Dashboard')" />
        </li> --}}

        <li class="c-sidebar-nav-title">@lang('Menu')</li>

        {{-- <li class="c-sidebar-nav-dropdown">
            <x-utils.link
                href="#"
                icon="c-sidebar-nav-icon cil-list"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Exports')" />

            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        :href="route('admin.exports.index')"
                        class="c-sidebar-nav-link"
                        :text="__('Export Excel')"
                        :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                </li>
            </ul>
        </li> --}}
        
        @include('backend.includes.partials.main_menus',[
            'all_menus'=>$all_menus,
            'mu_li_class'=>'dropdown',
            'mu_x_icon'=>'c-sidebar-nav-icon cil-list', 
        ])

        <li class="c-sidebar-nav-dropdown">
            <x-utils.link
                href="#"
                icon="c-sidebar-nav-icon cil-user"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Test Parent')" />

            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-dropdown">
                    <x-utils.link
                        href="#"
                        class="c-sidebar-nav-dropdown-toggle"
                        :text="__('Test Child 1')" />
                    
                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-dropdown">
                            <x-utils.link
                                href="#"
                                class="c-sidebar-nav-dropdown-toggle"
                                :text="__('Test Child 11')" />

                            <ul class="c-sidebar-nav-dropdown-items">
                                <li class="c-sidebar-nav-dropdown">
                                    <x-utils.link
                                        href="#"
                                        class="c-sidebar-nav-dropdown-toggle"
                                        :text="__('Test Child 111')" />
                                    
                                    <ul class="c-sidebar-nav-dropdown-items">
                                        <li class="c-sidebar-nav-item">
                                            <x-utils.link
                                                href="#"
                                                class="c-sidebar-nav-link"
                                                :text="__('Test Child 1111')" />
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="c-sidebar-nav-item">
                    <x-utils.link
                        href="#"
                        class="c-sidebar-nav-link"
                        :text="__('Test Child 2')" />
                </li>
                <li class="c-sidebar-nav-dropdown">
                    <x-utils.link
                        href="#"
                        class="c-sidebar-nav-dropdown-toggle"
                        :text="__('Test Child 3')" />
                    
                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                href="#"
                                class="c-sidebar-nav-link"
                                :text="__('Test Child 33')" />
                        </li>
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                href="#"
                                class="c-sidebar-nav-link"
                                :text="__('Test Child 33 (2)')" />
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- @if (
            $logged_in_user->hasAllAccess() ||
            (
                $logged_in_user->can('admin.access.user.list') ||
                $logged_in_user->can('admin.access.user.deactivate') ||
                $logged_in_user->can('admin.access.user.reactivate') ||
                $logged_in_user->can('admin.access.user.clear-session') ||
                $logged_in_user->can('admin.access.user.impersonate') ||
                $logged_in_user->can('admin.access.user.change-password') 
                // $logged_in_user->can('admin.access.exports.list') ||
                // $logged_in_user->can('admin.access.pegawai.list')
            )
        ) --}}
            <li class="c-sidebar-nav-title">@lang('System')</li> 
            
            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-user"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Access')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    {{-- @if (
                        $logged_in_user->hasAllAccess() ||
                        (
                            $logged_in_user->can('admin.access.user.list') ||
                            $logged_in_user->can('admin.access.user.deactivate') ||
                            $logged_in_user->can('admin.access.user.reactivate') ||
                            $logged_in_user->can('admin.access.user.clear-session') ||
                            $logged_in_user->can('admin.access.user.impersonate') ||
                            $logged_in_user->can('admin.access.user.change-password')
                        )
                    ) --}}
                        <li class="c-sidebar-nav-dropdown">
                            <x-utils.link
                                href="#"
                                class="c-sidebar-nav-dropdown-toggle"
                                :text="__('User Management')" 
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                            
                            <ul class="c-sidebar-nav-dropdown-items">
                                <li class="c-sidebar-nav-item">
                                    <x-utils.link
                                        :href="route('admin.auth.user.index')"
                                        class="c-sidebar-nav-link"
                                        :text="__('List')"
                                        :active="activeClass(Route::is('admin.auth.user'), 'c-active')" />
                                </li>
                                <li class="c-sidebar-nav-item">
                                    <x-utils.link
                                        :href="route('admin.auth.user.deactivated')"
                                        class="c-sidebar-nav-link"
                                        :text="__('Deactivated Users')"
                                        :active="activeClass(Route::is('admin.auth.user.deactivated'), 'c-active')" />
                                </li>
                                <li class="c-sidebar-nav-item">
                                    <x-utils.link
                                        :href="route('admin.auth.user.deleted')"
                                        class="c-sidebar-nav-link"
                                        :text="__('Deleted Users')"
                                        :active="activeClass(Route::is('admin.auth.user.deleted'), 'c-active')" />
                                </li>
                            </ul>
                        </li> 
                    {{-- @endif --}}

                    {{-- @if ($logged_in_user->hasAllAccess()) --}}
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Role Management')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                        </li>
                        
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.categories.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Categories Management')"
                                :active="activeClass(Route::is('admin.auth.categories.*'), 'c-active')" />
                        </li>
                    {{-- @endif --}}
                </ul>
            </li>
        {{-- @endif --}}

        {{-- @if ($logged_in_user->hasAllAccess()) --}}
            <li class="c-sidebar-nav-dropdown">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-list"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Logs')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::dashboard')"
                            class="c-sidebar-nav-link"
                            :text="__('Dashboard')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::logs.list')"
                            class="c-sidebar-nav-link"
                            :text="__('Logs')" />
                    </li>
                </ul>
            </li>
        {{-- @endif --}}
    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div><!--sidebar-->
