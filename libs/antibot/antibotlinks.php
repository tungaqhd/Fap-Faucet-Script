<?php
class antibotlinks {
    var $version=600;
    var $link_count=3;
    var $links_data=array();
    var $use_gd=true;
    var $fonts=array();
    var $abl_settings=array('abl_light_colors'=>'off', 'abl_background'=>'off', 'abl_noise'=>'on', 'abl_universe'=>'');

    public function generate($link_count=3, $force_regeneration=false) {

        $this->link_count=$link_count;
        if ((!$force_regeneration)&&
            (isset($_SESSION['antibotlinks']))&&
            (is_array($_SESSION['antibotlinks']))&&
            ((isset($_POST['antibotlinks']))||($_SESSION['antibotlinks']['time']>time()-60))) {
            return true;
    }

    if ($this->link_count<3) {
        $this->link_count=3;
    }
    if ($this->link_count>5) {
        $this->link_count=5;
    }
    $word_universe=array();
    if (!empty($this->abl_settings['abl_universe'])) {
        $universe_string=$this->abl_settings['abl_universe'];
        $universe_string=str_replace("\r\n", "\n", $universe_string);
        $universe_string=str_replace("\r", "\n", $universe_string);
            // explode the line at "new line"
        $universe_array=explode("\n", $universe_string);
        foreach ($universe_array as $universe_array_line) {
            if (empty($universe_array_line)) {
                continue;
            }
                // set temp_universe
            $temp_universe=array();
                // explode the line at ","
            $universe_array_line_array=explode(',', $universe_array_line);
            foreach ($universe_array_line_array as $universe_array_line_array_element) {
                    // explode key=>value
                $universe_array_line_array_element_kv=explode('=>', $universe_array_line_array_element);
                foreach ($universe_array_line_array_element_kv as $k=>$v) {
                    $temp_universe[trim($k)]=trim($v);
                }
            }
            if (count($temp_universe)>=3) {
                $word_universe[]=$temp_universe;
            }
        }
    }
        // if no universe specified in the admin
    if (count($word_universe)<1) {
        $word_universe[]=array('one'=>'1', 'two'=>'2', 'three'=>'3', 'four'=>'4', 'five'=>'5', 'six'=>'6', 'seven'=>'7', 'eight'=>'8', 'nine'=>'9', 'ten'=>'10');
        $word_universe[]=array('1'=>'one', '2'=>'two', '3'=>'three', '4'=>'four', '5'=>'five', '6'=>'six', '7'=>'seven', '8'=>'eight', '9'=>'nine', '10'=>'ten');
        $word_universe[]=array('1'=>'I', '2'=>'II', '3'=>'III', '4'=>'IV', '5'=>'V', '6'=>'VI', '7'=>'VII', '8'=>'VIII', '9'=>'IX', '10'=>'X');
        $word_universe[]=array('cat'=>'C@t', 'dog'=>'d0g', 'lion'=>'1!0n', 'tiger'=>'T!g3r', 'monkey'=>'m0nk3y', 'elephant'=>'31eph@nt', 'cow'=>'c0w', 'fox'=>'f0x', 'mouse'=>'m0us3', 'ant'=>'@nt');
        $word_universe[]=array('2-1'=>'1', '1+1'=>'2', '1+2'=>'3', '2+2'=>'4', '3+2'=>'5', '2+4'=>'6', '3+4'=>'7', '4+4'=>'8', '1+8'=>'9', '5+6'=>'11');
        $word_universe[]=array('1'=>'3-2', '2'=>'8-6', '3'=>'1+2', '4'=>'3+1', '5'=>'9-4', '6'=>'3+3', '7'=>'6+1', '8'=>'2*4', '9'=>'3+6', '10'=>'2+8');
        $word_universe[]=array('--x'=>'OOX', '-x-'=>'OXO', 'x--'=>'XOO', 'xx-'=>'XXO', '-xx'=>'OXX', 'x-x'=>'XOX', '---'=>'OOO', 'xxx'=>'XXX', 'x-x-'=>'XOXO', '-x-x'=>'OXOX');
        $word_universe[]=array('--x'=>'--+', '-x-'=>'-+-', 'x--'=>'+--', 'xx-'=>'++-', '-xx'=>'-++', 'x-x'=>'+-+', '---'=>'---', 'xxx'=>'+++', 'x-x-'=>'+-+-', '-x-x'=>'-+-+');
        $word_universe[]=array('--x'=>'oo+', '-x-'=>'o+o', 'x--'=>'+oo', 'xx-'=>'++o', '-xx'=>'o++', 'x-x'=>'+o+', '---'=>'ooo', 'xxx'=>'+++', 'x-x-'=>'+o+o', '-x-x'=>'o+o+');
        $word_universe[]=array('oox'=>'--+', 'oxo'=>'-+-', 'xoo'=>'+--', 'xxo'=>'++-', 'oxx'=>'-++', 'xox'=>'+-+', 'ooo'=>'---', 'xxx'=>'+++', 'xoxo'=>'+-+-', 'oxox'=>'-+-+');
        $word_universe[]=array('2*A'=>'AA', '3*A'=>'AAA', '2*B'=>'BB', '3*B'=>'BBB', '1*A+1*B'=>'AB', '1*A+2*B'=>'ABB', '2*A+2*B'=>'AABB', '2*C'=>'CC', '3*C'=>'CCC', '1*C+1*A'=>'CA', '1*C+1*B'=>'CB', '1*C+2*A'=>'CAA', '1*C+2*B'=>'CBB', '2*C+1*A'=>'CCA');
        $word_universe[]=array('AA'=>'2*A', 'AAA'=>'3*A', 'BB'=>'2*B', 'BBB'=>'3*B', 'AB'=>'1*A+1*B', 'ABB'=>'1*A+2*B', 'AABB'=>'2*A+2*B', 'CC'=>'2*C', 'CCC'=>'3*C', 'CA'=>'1*C+1*A', 'CB'=>'1*C+1*B', 'CAA'=>'1*C+2*A', 'CBB'=>'1*C+2*B', 'CCA'=>'2*C+1*A');
        $word_universe[]=array('zoo'=>'200', 'ozo'=>'020', 'ooz'=>'002', 'soo'=>'500', 'oso'=>'050', 'oos'=>'005', 'lol'=>'101', 'sos'=>'505', 'zoz'=>'202', 'lll'=>'111');
    }

    $universe_number=mt_rand(0, count($word_universe)-1);
    $universe=$word_universe[$universe_number];

    $antibotlinks_solution='';

    $used_keywords_array=array();

    $antibotlinks_array=array();
    $antibotlinks_array['links']=array();
    $background_item=mt_rand(1, 3);
    for ($z=0;$z<$this->link_count;$z++) {
        $random_number=mt_rand(1000, 9999);
        $antibotlinks_solution.=$random_number.' ';

            // Choose the keyword
        do {
            $keyword=array_rand($universe, 1);
        } while (isset($used_keywords_array[$keyword]));
        $used_keywords_array[$keyword]=1;

        if (count($this->fonts)>0) {
            ob_start();
                // use ttf/otf
            $info_font=$this->fonts[mt_rand(0, count($this->fonts)-1)];
            $angle=mt_rand(-7, 7);

                // get dimension
            $infostring_length=(strlen($universe[$keyword])+1)*14;
            $imx = imagecreate($infostring_length, 40);
            $fontcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
            $imageinfo=imagefttext($imx, 18, $angle, 1, 28, $fontcolor, APPPATH . 'third_party/antibot/fonts/'.$info_font, $universe[$keyword]);

                // draw the image
                $infostring_length=$imageinfo[2]+16;//4
                $im = imagecreatetruecolor($infostring_length, 40);
                imagealphablending($im, true);
                $background = imagecolorallocatealpha($im, 0, 0, 0, 127);
                imagefill($im, 0, 0, $background);

                if ($this->abl_settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                }

                // draw image background
                if ($this->abl_settings['abl_background']=='on') {
                    $resample_factor=mt_rand(50, 100);
                    $resample_factor=$resample_factor/100;
                    if ($this->abl_settings['abl_light_colors']=='on') {
                        $background_image = imagecreatefrompng('libs/antibot/abl_'.$background_item.'_l.png');
                    } else {
                        $background_image = imagecreatefrompng('libs/antibot/abl_'.$background_item.'_d.png');
                    }
                    imagecopyresampled($im, $background_image, mt_rand(-80, 0), mt_rand(-100, 0), 0, 0, imagesx($background_image), imagesy($background_image), imagesx($background_image)/$resample_factor, imagesy($background_image)/$resample_factor);
                }
                //

                // draw some noise
                if ($this->abl_settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(1, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                            $noise_plus_or_minus=-1;
                            break;
                            default:
                            $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                //

                imagefttext($im, 18, $angle, 8, 28, $fontcolor, 'libs/antibot/fonts/'.$info_font, $universe[$keyword]);
                imagesavealpha($im, true);
                imagepng($im);
                $imagedata = ob_get_contents();
                ob_end_clean();
                $abdata='<img src="data:image/png;base64,'.base64_encode($imagedata).'" alt="" width="'.$infostring_length.'" height="40" style="border:1px solid #222222;border-radius:5px;margin:2px;" />';
                $antibotlinks_array['links'][$z]['link']='<a href="/" rel="'.$random_number.'">Anti-Bot '.$abdata.'</a>';
            } else {
                $abdata=$universe[$keyword];
                $antibotlinks_array['links'][$z]['link']='<a href="/" rel="'.$random_number.'">Anti-Bot ( '.$abdata.' )</a>';
            }
            
            $antibotlinks_array['links'][$z]['keyword']=$keyword;
        }

        $info_array=array();
        foreach ($antibotlinks_array['links'] as $link) {
            $info_array[]=$link['keyword'];
        }

        $info_string=implode(', ', $info_array);
        if ($this->use_gd) {
            ob_start();
            if (count($this->fonts)>0) {
                // use ttf/otf
                $info_font=$this->fonts[mt_rand(0, count($this->fonts)-1)];
                $angle=mt_rand(-1, 1);

                // get dimension
                $infostring_length=(strlen($universe[$keyword])+1)*14;
                $imx = imagecreate($infostring_length, 32);
                $fontcolor = imagecolorallocate($imx, mt_rand(5, 50), mt_rand(5, 50), mt_rand(5, 50));
                $imageinfo=imagefttext($imx, 16, $angle, 1, 14, $fontcolor, 'libs/antibot/fonts/'.$info_font, $info_string);

                // draw the image
                $infostring_length=$imageinfo[2]+10;
                $im = imagecreatetruecolor($infostring_length, 24);
                imagealphablending($im, true);
                $background = imagecolorallocatealpha($im, 0, 0, 0, 127);
                imagefill($im, 0, 0, $background);
                if ($this->abl_settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                }
                imagecolortransparent($im, $background);
                imagerectangle($im, 0, 0, $infostring_length, 14, $background);

                if ($this->abl_settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(0, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                            $noise_plus_or_minus=-1;
                            break;
                            default:
                            $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                imagefttext($im, 16, mt_rand(-1, 1), 2, 18, $fontcolor, 'libs/antibot/fonts/'.$info_font, $info_string);
                imagesavealpha($im, true);
                imagepng($im);
                $imagedata = ob_get_contents();
            } else {
                // use standard fonts
                $infostring_length=(strlen($info_string)+1)*8;
                $im = imagecreate($infostring_length, 24);
                $background = imagecolorallocate($im, mt_rand(0, 4), mt_rand(0, 4), mt_rand(0, 4));
                if ($this->abl_settings['abl_light_colors']=='on') {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(174, 254), mt_rand(174, 254), mt_rand(174, 254), mt_rand(0, 32));
                } else {
                    $fontcolor = imagecolorallocatealpha($im, mt_rand(1, 80), mt_rand(1, 80), mt_rand(1, 80), mt_rand(0, 32));
                }
                imagecolortransparent($im, $background);
                imagerectangle($im, 0, 0, $infostring_length, 16, $background);

                if ($this->abl_settings['abl_noise']=='on') {
                    $noise_dots=$infostring_length/2;
                    for ($zz=0;$zz<$noise_dots;$zz++) {
                        $noisex=mt_rand(0, $infostring_length-3);
                        $noisey=mt_rand(1, 40-3);
                        $noise_plus_or_minus=mt_rand(0, 1);
                        switch ($noise_plus_or_minus) {
                            case 0:
                            $noise_plus_or_minus=-1;
                            break;
                            default:
                            $noise_plus_or_minus=+1;
                            break;
                        }
                        imageline($im, $noisex, $noisey, $noisex+1, $noisey+$noise_plus_or_minus, $fontcolor);
                    }
                }
                imagestring($im, 4, mt_rand(1, 5), 2, $info_string, $fontcolor);
                imagepng($im);
                $imagedata = ob_get_contents();
            }
            ob_end_clean();
            $antibotlinks_array['info']='Please click on the Anti-Bot links in the following order <img src="data:image/png;base64,'.base64_encode($imagedata).'" alt="" width="'.$infostring_length.'" height="24"/> <a href="#" id="antibotlinks_reset">( reset )</a>';
        } else {
            $antibotlinks_array['info']='Please click on the Anti-Bot links in the following order '.$info_string.' <a href="#" id="antibotlinks_reset">( reset )</a>';
        }

        shuffle($antibotlinks_array['links']);

        $antibotlinks_array['time']=time();
        $antibotlinks_array['solution']=trim($antibotlinks_solution);

        if (!$force_regeneration) {
            $antibotlinks_array['valid']=true;
        }

        $antibotlinks_array['universe']=$word_universe[$universe_number];

        $_SESSION['antibotlinks']=$antibotlinks_array;
        return true;
    }

    public function check() {
        $zero_solution='';
        for ($z=0;$z<$this->link_count;$z++) {
            $zero_solution.='0 ';
        }
        $zero_solution=trim($zero_solution);
        if (trim($_POST['antibotlinks'])==$zero_solution) {
            $_SESSION['antibotlinks']['valid']=false;
            return $_SESSION['antibotlinks']['valid'];
        }
        if ((trim($_POST['antibotlinks'])==$_SESSION['antibotlinks']['solution'])&&(!empty($_SESSION['antibotlinks']['solution']))) {
            $_SESSION['antibotlinks']['valid']=true;
        } else {
            $_SESSION['antibotlinks']['valid']=false;
        }
        return $_SESSION['antibotlinks']['valid'];
    }

    public function get_links() {
        $retval='';
        foreach ($_SESSION['antibotlinks']['links'] as $linkarray) {
            if (!empty($retval)) {
                $retval.='","';
            }
            $retval.= str_replace('"', '\"', $linkarray['link']);
        }
        return '["'.$retval.'"]';
    }

    public function get_js() {
        return '<script type="text/javascript"> var ablinks=' .$this->get_links(). '</script>';
    }

    public function show_info() {
        $return = '<input type="hidden" name="antibotlinks" id="antibotlinks" value="" /><p class="alert alert-info">'.$_SESSION['antibotlinks']['info'].'</p>';
        return $return;
    }

    public function is_valid($record_in_db=true) {
        if (empty($_SESSION['antibotlinks']['valid'])) {
            $_SESSION['antibotlinks']['valid']=false;
        }

        // record the log
        // Log the request/response
        if ((is_array($_POST))&&(count($_POST)>0)&&($record_in_db)) {
            $Faucetinabox_ABL_Log_status='invalid';
            switch ($_SESSION['antibotlinks']['valid']) {
                case true:
                $Faucetinabox_ABL_Log_status='valid';
                break;
                case false:
                if (empty($_POST['antibotlinks'])) {
                    $Faucetinabox_ABL_Log_status='possibly bot';
                } else {
                    $Faucetinabox_ABL_Log_status='invalid';
                }
                break;
            }
        }
        //
        return $_SESSION['antibotlinks']['valid'];
    }

    public function get_link_count() {
        // return if not enabled
        if ($this->abl_settings['abl_enabled']!='on') {
            return 0;
        }
        //
        return count($_SESSION['antibotlinks']['links']);
    }

}

?>