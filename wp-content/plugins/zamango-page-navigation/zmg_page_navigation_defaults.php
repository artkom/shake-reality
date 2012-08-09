<?

/*
 * $Date: 2010/02/28 19:02:54 $
 * $Revision: 1.0 $
 */

    $this->default_options =  array(
        "left"             => array(
            "default"      => 1,
            "regs"         => array("/^\d+$/"),
            "min"          => 0,
            "ifnotdefined" => 0
        ),
        "center"           => array(
            "default"      => 11,
            "regs"         => array("/^\d+$/"),
            "min"          => 1,
            "ifnotdefined" => 1
        ),
        "right"            => array(
            "default"      => 1,
            "regs"         => array("/^\d+$/"),
            "min"          => 0,
            "ifnotdefined" => 0
        ),
        "clear_options"    => array(
            "default"      => 0,
            "definedornot" => 1
        ),
        "before_loop"      => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "after_loop"       => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "before_loop_s"    => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "after_loop_s"     => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "separator"        => array(
            "default"      => "...",
            "minlen"       => 1,
            "maxlen"       => 5
        ),
        "prev_link"        => array(
            "default"      => "auto",
            "regs"         => array("/^(auto|always|never)$/")
        ),
        "next_link"        => array(
            "default"      => "auto",
            "regs"         => array("/^(auto|always|never)$/")
        ),
        "prev_tag"         => array(
            "default"      => "&laquo;",
            "minlen"       => 1
        ),
        "next_tag"         => array(
            "default"      => "&raquo;",
            "minlen"       => 1
        ),
        "prev_tag_s"       => array(
            "default"      => "&laquo; [zmg_pn:prev_post]",
            "minlen"       => 1
        ),
        "next_tag_s"       => array(
            "default"      => "[zmg_pn:next_post] &raquo;",
            "minlen"       => 1
        ),
        "first_tag"        => array(
            "default"      => "[zmg_pn:page]",
            "minlen"       => 1
        ),
        "last_tag"         => array(
            "default"      => "[zmg_pn:page]",
            "minlen"       => 1
        ),
        "curr_tag"         => array(
            "default"      => "[zmg_pn:page]",
            "minlen"       => 1
        ),
        "stnd_tag"         => array(
            "default"      => "[zmg_pn:page]",
            "minlen"       => 1
        ),
        "tooltips"         => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "ttip_tag"         => array(
            "default"      => "Page [zmg_pn:page] of [zmg_pn:total]",
            "minlen"       => 1,
            "stoper"       => create_function('',
                'return ! isset($_POST[\'tooltips\']);')
        ),
        "is_zmg_css"       => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "in_category"      => array(
            "default"      => 0,
            "definedornot" => 1
        ),
        "fl_linking"      => array(
            "default"      => 1,
            "definedornot" => 1
        ),
        "css"              => array(
            "default"      => "
/*********************************/
/*           CSS for whole navigation bar       */

/* Parent DIV containing whole page navigation bar */
.zmg_pn {
    font-family: Verdana, Arial, Sans-Serif;
    text-align: center;
    margin: 10px;
    font-size: 90%;
    color: #121212;
    font-weight: bold;
}

/*  All links inside the navigation bar */
.zmg_pn a {
    margin: 0;
    padding: 5px;
    color: #121212;
    text-decoration: none;
}

.zmg_pn a:hover{
    background-color: #cdcdcd ;
    margin: 0;
    padding: 5px;
    text-decoration: none;
    color: #121212;
}

/*  Following declaration hides standard navigation of Classic theme  */
.navigation {
    display:none;
}


/*********************************/
/*           CSS for blogroll navigation        */

/* Current page sign */
.zmg_pn_current {
    padding: 10px;
    background-color: #dcdcdc ;
    font-weight: bold;
    font-size: 120%;
}

/* Link to next page */
.zmg_pn_next {
    background-color: #fafafa ;
}
.zmg_pn_next a:hover , .zmg_pn_next:hover {
    background-color: #cdcdcd ;
}

/* Link to previous page */
.zmg_pn_prev {
    background-color: #fafafa ;
}
.zmg_pn_prev a:hover , .zmg_pn_prev:hover {
    background-color: #cdcdcd ;
}

/* Common link */
.zmg_pn_standar, .zmg_pn_standar a {
    background-color: #fafafa ;
}
.zmg_pn_standar a:hover , .zmg_pn_standar:hover {
    background-color: #cdcdcd ;
}

/* Inactive page sign (i.e. without link) */
.zmg_pn_inactive {
    background-color: #e3e3e3 ;
}

/* Separator */
.zmg_pn_separator {
    padding: 2px;
}


/*********************************/
/*      CSS for quick links on single post      */

/* Link to previous post */
.zmg_pn_prev_post {
    float: left;
    MAX-WIDTH: 48%;
    min-height: 30px;
    text-align: left;
    margin: 5px;
    padding: 2px;
    background-color: #fafafa ;
}
.zmg_pn_prev_post a:hover , .zmg_pn_prev_post:hover {
    background-color: #cdcdcd ;
}

/* Link to next post */
.zmg_pn_next_post {
    float: right;
    MAX-WIDTH: 48%;
    min-height: 30px;
    text-align: right;
    margin: 5px;
    padding: 2px;
    background-color: #fafafa ;
}
.zmg_pn_next_post:hover  a , .zmg_pn_next_post:hover {
    background-color: #cdcdcd ;
}


/*********************************/
/*     Please don't modify following style      */
.zmg_pn_clear {
    clear: both;
}
            "
        )
    );

