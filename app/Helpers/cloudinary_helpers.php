<?php

use Cloudinary\Cloudinary;

if (!function_exists('cloudinary_url')) {
    function cloudinary_url($path) {
        $cloudinary = new Cloudinary();
        return $cloudinary->image($path)->toUrl();
    }
}
