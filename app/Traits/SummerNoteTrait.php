<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait SummerNoteTrait
{
    public function summerNoteStore($content, $path)
    {
        if ($content) {
            $dom = new \DomDocument();
            $dom->loadHtml(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $src = $image->getAttribute('src');

                if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                    $imageType = strtolower($type[1]); // get the image extension (e.g., png, jpg)
                    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $src);
                    $imageData = base64_decode($imageData);
                    // $imageName = "/uploads/images/" . $path .' / '. time() . $item . '.' . $imageType;

                    $uniqueId = uniqueId(10);
                    $imageName = $uniqueId.$item.'.webp';
                    $webpPath = '/uploads/images/'.$path.'/'.$imageName;
                    if (file_put_contents(public_path($webpPath), $imageData)) {
                        $webpImage = Image::make(public_path($webpPath));
                        $webpImage->encode('webp', 80);
                        $webpImage->save();
                        $image->removeAttribute('src');
                        $image->setAttribute('src', $webpPath);
                    }
                }
            }

            return $dom->saveHTML();
        }
    }

    public function summerNoteAllImageDestroy($content)
    {
        if ($content) {
            $pattern = '/<img[^>]+>/i';
            preg_match_all($pattern, $content, $matches);
            $imageTags = $matches[0];
            $srcAttributes = [];
            foreach ($imageTags as $key => $imageTag) {
                if (preg_match('/src=["\']([^"\']+)["\']/', $imageTag, $srcMatch)) {
                    $srcAttributes[$key] = $srcMatch[1];
                    $path = public_path($srcAttributes[$key]);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
        }
    }
}
