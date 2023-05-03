<?php
    session_start();

    // generate a CAPTCHA string of $len length
    function generateCode($len) {
        //$chars = "abcdefghkmpqrstwxyzABCDEFGHJKMPRQSTUVWXYZ0123456789";
        $chars = 'abdefhknrstyz23456789';
        $code = "";
        $clen = strlen($chars) - 1;
        for($i = 0; $i < $len; $i++)
            $code .= $chars[mt_rand(0, $clen)];

        return $code;
    }

    // create image
    function generateImage($code) {

        $w = 400;
        $h = 100;
        $im = imagecreatetruecolor($w, $h);

        imagesavealpha($im, true);
        // colors
        if(!isset($_GET['dark']) || isset($_GET['light']))
        {
            $bg = imagecolorallocatealpha($im, 51, 51, 51, 127);
            $graphic = imagecolorallocate($im, 52, 52, 52);
        }
        else
        {
            $bg = imagecolorallocatealpha($im, 51, 51, 51, 0);
            $graphic = imagecolorallocate($im, 100, 100, 100);
        }
        $text = imagecolorallocate($im, 0, 0, 0);
        $border = imagecolorallocate($im, 97, 50, 163);
        
        // fill background
        imagefill($im, 0, 0, $bg);
        
        // draw rectangle
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $border);
        
        // draw random lines
        for ($i = 0; $i < 8; $i++) {
            imageline($im, 0, rand() % ($h - 1), $w - 1, rand() % ($h - 1), $graphic);
        }
        
        // draw random dots
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($im, rand() % ($w - 1), rand() % ($h - 1), $graphic);
        }
        
        //for each character in $code, randomize the color of $text.
        for ($i = 0; $i < strlen($code); $i++) 
        {
            if(isset($_GET['dark'])) {
                $low = 100;
                $high = 250;
            }
            else
            {
                $low = 50;
                $high = 150;
            }
            $c1 = mt_rand($low, $high); //r(ed)
            $c2 = mt_rand($low, $high); //g(reen)
            $c3 = mt_rand($low, $high); //b(lue)
            //get color from palette
            $color = imagecolorexact($im, $c1, $c2, $c3);
            if($color==-1) {
                 //color does not exist...
                 //test if we have used up palette
                 if(imagecolorstotal($im)>=255) {
                      //palette used up; pick closest assigned color
                      $color = imagecolorclosest($im, $c1, $c2, $c3);
                 } else {
                      //palette NOT used up; assign new color
                      $color = imagecolorallocate($im, $c1, $c2, $c3);
                 }
            }
            // determine where the X and Y should be based on the image dimensions
            $x = ($w - 20) / strlen($code) * $i + 25;
            $y = (($h - 20) / 2) + 10;

            // determine the font size based on the image dimensions and the length of the code
            $size = ($h / strlen($code)) * 3;

            imagettftext($im, $size, rand(-25, 25), $x, $y, $color, 'fonts/bearpaw.ttf', $code[$i]);
            
        }
        
        // output image in the browser
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }

    function generateCAPTCHA() 
    {
        // generate code
        $code = generateCode(6);

        // remember generated code in session variable
        $_SESSION['captcha'] = $code;
        
        // generate image
        generateImage($code);
    }

    generateCAPTCHA();
?>