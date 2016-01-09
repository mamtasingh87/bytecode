<?php

$nav_array = array(
    array(
        'title' => 'Dashboard',
        'url' => '/',
        'id' => 'dashboard',
        'sub' => array(),
    ),
    array(
        'title' => 'Pages',
        'url' => 'content/entries',
            'sub'   => array(
//                    array(
//                        'title' => 'Entries',
//                        'url'   => 'content/entries',
//                    ),
                    array(
                        'title' => 'Navigations',
                        'url'   => 'navigations',
                    ),
//                    array(
//                        'title' => 'Galleries',
//                        'url'   => 'galleries',
//                    ),
                ),
    ),
    array(
        'title' => 'Users',
        'url' => 'users',
//            'sub'   => array(
//                    array(
//                        'title' => 'Users',
//                        'url'   => 'users',
//                    ),
//                    array(
//                        'title' => 'User Groups',
//                        'url'   => 'users/groups',
//                    ),
//                ),
    ),
//        array(
//            'title' => 'Tools',
//            'url'   => 'content/types',
//            'sub'   => array(
//                    array(
//                        'title' => 'Content Types',
//                        'url'   => 'content/types',
//                    ),
//                    array(
//                        'title'  => 'Content Fields',
//                        'url'    => 'content/fields',
//                        'hidden' => TRUE, // Used for selected parents for this section
//                    ),
//                    array(
//                        'title' => 'Code Snippets',
//                        'url'   => 'content/snippets',
//                    ),
//                    array(
//                        'title' => 'Categories',
//                        'url'   => 'content/categories/groups',
//                    ),
//                    array(
//                        'title' => 'Theme Editor',
//                        'url'   => 'settings/theme-editor',
//                    ),
//                ),
//        ),
//    array(
//        'title' => 'Reward',
//        'url' => 'trivia/reward',
//        'sub' => array(
//            array(
//                'title' => 'Reward Log',
//                'url' => 'trivia/reward',
//            ),
//        ),
//    ),
    array(
        'title' => 'Trivia Questions',
        'url' => 'trivia/categories',
        'sub' => array(
            array(
                'title' => 'Categories',
                'url' => 'trivia/categories',
            ),
            array(
                'title' => 'Questions',
                'url' => 'trivia/questions',
            ),
        ),
    ),
    
    array(
        'title' => 'Requests',
        'url' => 'quote/quote',
        'sub' => array(
            array(
                'title' => 'Quote',
                'url' => 'quote/quote',
            ),
            array(
                'title' => 'Binder',
                'url' => 'quote/quote/binder',
            ),
        ),
    ),
//    array(
//        'title' => 'Invitation Logs',
//        'url' => 'trivia/invitationlog',
//    ),
    array(
        'title' => 'Reports',
        'url' => 'reports/quote',
        'sub' => array(
            array(
                'title' => 'Quote Reports',
                'url' => 'reports/quote',
            ),
            array(
                'title' => 'Binder Reports',
                'url' => 'reports/binder',
            ),
            array(
                'title' => 'Auto Registered Users',
                'url' => 'reports/autousers',
            ),
            array(
                'title' => 'Reward Logs',
                'url' => 'trivia/reward',
            ),
            array(
        'title' => 'Invitation Logs',
        'url' => 'trivia/invitationlog',
    ),
        ),
    ),
    array(
        'title' => 'E-Store',
        'url' => '#',
        'sub' => array(
            array(
                'title' => 'Categories',
                'url' => 'redemption/categories',
            ),
            array(
                'title' => 'Products',
                'url' => 'redemption/products',
            ),
            array(
                'title' => 'Orders',
                'url' => 'redemption/order',
            ),
        ),
    ),
    array(
        'title' => 'System',
        'url' => 'settings/general-settings',
        'sub' => array(
            array(
                'title' => 'General Settings',
                'url' => 'settings/general-settings',
            ),
            array(
                'title' => 'Clear Cache',
                'url' => 'settings/clear-cache',
            ),
            array(
                'title' => 'Server Info',
                'url' => 'settings/server-info',
            ),
        ),
    ),
);

echo admin_nav($nav_array);
?>
