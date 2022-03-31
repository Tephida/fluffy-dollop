<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Support;

class Id3v2
{
    /** @var string */
    public string $error;

    /** @var array|string[] */
    private array $tags = array(
        'TALB' => 'Album',
        'TCON' => 'Genre',
        'TENC' => 'Encoder',
        'TIT2' => 'Title',
        'TPE1' => 'Artist',
        'TPE2' => 'Ensemble',
        'TYER' => 'Year',
        'TCOM' => 'Composer',
        'TCOP' => 'Copyright',
        'TRCK' => 'Track',
        'WXXX' => 'URL',
        'COMM' => 'Comment'
    );

    /**
     * @param string $tag
     * @param string $type
     * @return false|string
     */
    private function decTag(string $tag, string $type): false|string
    {
        //TODO- handling of comments is quite weird
        //but I don't know how it is encoded so I will leave the way it is for now
        if ($type === 'COMM') {
            $tag = substr($tag, 0, 3) . substr($tag, 10);
        }
        //mb_convert_encoding is corrupted in some versions of PHP so I use iconv
        return match (ord($tag[2])) {
            0 => iconv('cp1251', 'UTF-8', substr($tag, 3)),
            1 => iconv('UTF-16LE', 'UTF-8', substr($tag, 5)),
            2 => iconv('UTF-16BE', 'UTF-8', substr($tag, 5)),
            3 => substr($tag, 3),
            default => false,
        };
    }

    /**
     * @param string $file
     * @return false|array
     */
    public function read(string $file): false|array
    {
        if (!file_exists($file)) {
            return false;
        }
        $f = fopen($file, 'rb');
        $header = fread($f, 10);
        $header = unpack("a3signature/c1version_major/c1version_minor/c1flags/Nsize", $header);

        if (!$header['signature'] === 'ID3') {
            $this->error = 'This file does not contain ID3 v2 tag';
            fclose($f);
            return false;
        }

        $result = array();
        for ($i=0; $i<22; $i++) {
            $tag = rtrim(fread($f, 6));

            if (!isset($this->tags[$tag])) {
                break;
            }
            $size = fread($f, 2);
            $size = unpack('n', $size);
            $size = $size[1]+2;

            $value = fread($f, $size);
            $value = $this->decTag($value, $tag);

            $result[$this->tags[$tag]] = $value;
        }
        fclose($f);
        return $result;
    }
}