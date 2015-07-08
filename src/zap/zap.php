<?php

  namespace zap{
    class Zap{
      public function process(ZapTransformation $transformation, ZapStream &$streamIn, ZapStream &$streamOut){
        $scale = $transformation->scale();
        $rotate =  0 - $transformation->rotate();
        $position = $transformation->position();
        $color = $transformation->color();
        $alpha = 1.27 * (100 - ($color->alpha() * 100.00));
        $opacity = 1.27 * (100 - ($transformation->opacity() * 100.00));
        $roBack = imagecolorallocatealpha($streamOut->resource(), $color->red(), $color->green(), $color->blue(), 127.0);
        $ouBack = imagecolorallocatealpha($streamOut->resource(), $color->red(), $color->green(), $color->blue(), $alpha);
        $this->opacity($streamIn, $opacity);
        $stream = ZapStream::createFromResource(imagerotate($streamIn->resource(), $rotate, $roBack));
        $z = array(0 - ($stream->width() * $scale) * 0.5, 0 - ($stream->height() * $scale) * 0.5);
        $p = array($z[0] + $position->x(), $z[1] + $position->y());
        imagealphablending($streamOut->resource(), true);
        imagefill($streamOut->resource(), $x = 0, $y = 0, $ouBack);
        imagecopyresampled(
          $streamOut->resource(), $stream->resource(), $p[0], $p[1], 0, 0,
          $stream->width() * $scale, $stream->height() * $scale, $stream->width(), $stream->height()
        );
        $stream->dispose();
        $stream = null;
      }
      
      private function opacity(&$stream, $opacity){
        if(intval($opacity) === 0) return;
        list($w, $h) = array($stream->width(), $stream->height());
        for($i = 0, $j = ($w * $h), $x = 0, $y = 0; $i < $j; ++$i, $x += (($y === $h) ? 1 : 0), $y = (($y < $h) ? $y + 1 : 0)){
          $rgb = imagecolorat($stream->resource(), $x, $y);
          $ici = imagecolorsforindex($stream->resource(), $rgb);
          list($r, $g, $b, $a) = array(($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, ($rgb) & 0xFF, $ici['alpha']);
          $color = imagecolorallocatealpha($stream->resource(), $r, $g, $b, min($a + $opacity, 127.0));
          imagesetpixel($stream->resource(), $x, $y, $color);
        }
      }
    }
  }

?>