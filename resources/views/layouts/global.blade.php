@php

    $color_var = ':root{';
        // Start Color Variable
        $color_var .='--primary_color:' . (empty(setting("PRIMARY_COLOR")) ? "#108b3a" : setting("PRIMARY_COLOR")) .';';
        $color_var .='--primary_bg_color_text:' . (empty(setting("PRIMARY_BG_TEXT_COLOR")) ? "#ffffff" : setting("PRIMARY_BG_TEXT_COLOR")) .';';
        $color_var .='--secondary_color:' . (empty(setting("SECONDARY_COLOR")) ? "#000000" : setting("SECONDARY_COLOR")) .';';
        $color_var .='--optional_color:' . (empty(setting("OPTIONAL_COLOR")) ? "#00aa3a" : setting("OPTIONAL_COLOR")) .';';
        $color_var .='--optional_bg_color_text:' . (empty(setting("OPTIONAL_BG_TEXT_COLOR")) ? "#ffffff" : setting("OPTIONAL_BG_TEXT_COLOR")) .';';
        $color_var .='--MAIN_MENU_BG:' . (empty(setting("MAIN_MENU_BG")) ? "var(--primary_color)" : setting("MAIN_MENU_BG")) .';';
        $color_var .='--MAIN_MENU_ul_li_color:' . (empty(setting("MAIN_MENU_ul_li_color")) ? "var(--primary_bg_color_text)" : setting("MAIN_MENU_ul_li_color")) .';';
    // End Color Variable
    $color_var .= '}';
@endphp
<!-- GLOBAL TOP CSS _@stack('internaal_global_top_css') -->@php

    $color_var = ':root{';
        // Start Color Variable
        $color_var .='--primary_color:' . (empty(setting("PRIMARY_COLOR")) ? "#108b3a" : setting("PRIMARY_COLOR")) .';';
        $color_var .='--primary_bg_color_text:' . (empty(setting("PRIMARY_BG_TEXT_COLOR")) ? "#ffffff" : setting("PRIMARY_BG_TEXT_COLOR")) .';';
        $color_var .='--secondary_color:' . (empty(setting("SECONDARY_COLOR")) ? "#000000" : setting("SECONDARY_COLOR")) .';';
        $color_var .='--optional_color:' . (empty(setting("OPTIONAL_COLOR")) ? "#00aa3a" : setting("OPTIONAL_COLOR")) .';';
        $color_var .='--optional_bg_color_text:' . (empty(setting("OPTIONAL_BG_TEXT_COLOR")) ? "#ffffff" : setting("OPTIONAL_BG_TEXT_COLOR")) .';';
        $color_var .='--MAIN_MENU_BG:' . (empty(setting("MAIN_MENU_BG")) ? "#ffffff" : setting("MAIN_MENU_BG")) .';';
        $color_var .='--MAIN_MENU_ul_li_color:' . (empty(setting("MAIN_MENU_ul_li_color")) ? "#000000" : setting("MAIN_MENU_ul_li_color")) .';';
    $color_var .= '}';
@endphp

<!-- GLOBAL TOP CSS _@stack('internaal_global_top_css') -->
<style>
a,i:hover{text-decoration:none!important;}
{{ $color_var }}
select{cursor:pointer!important;}
.modal{z-index:99999999999999;}
@stack('internaal_global_top_css')

/* Admin sidebar white + black */
.main-sidebar {
    background: #ffffff !important;
}

.nav-sidebar .nav-link {
    color: #000000 !important;
}

.nav-sidebar .nav-link i {
    color: #444 !important;
}

.nav-sidebar .nav-link:hover {
    background-color: #F2D231 !important;
    color: #000000 !important;
}

.nav-sidebar .nav-link:hover i {
    color: #000000 !important;
}

.nav-sidebar .nav-link.active {
    
}

.brand-link,
.user-panel .info a {
    color: #000000 !important;
    font-weight: 700;
    text-transform: uppercase;
}
[class*=sidebar-dark] .brand-link, [class*=sidebar-dark] .brand-link .pushmenu {
    display: none;
}
footer,
.footer,
.main-footer {
    background: #ffffff !important;
}
footer a {
    color: #000000!important;
    text-decoration: none !important;
}

.copy {
    background-color: #efeff0 !important;
}

</style>