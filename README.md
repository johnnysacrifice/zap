## What is Zap?
**Zap** is a simplified interface built on the top of the *GD Graphics Library*.

## What is the purpose of it?
The intent is to give the ability to easily create, manipulate and store images using PHP.

## How it works?
#### Scenario - store an image

In such a case the **ZapStream** class can provide a great help to manage uploaded **JPEG** and **PNG** images quickly.

```php
public function upload(){
  if(!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK)
    return $this->index();
  $file = $_FILES['picture'];
  $filename = $file['tmp_name'];
  $ou = ZapStream::createFromFile($filename);
  $ou->save(sprintf('%s/%s', _, $this->editable), $quality = 100.00);
  $ou->dispose();
  $ou = null;
  return new ZapView($this->model());
}
```

###### ZapStream class method(s)
* `create($width, $height)`where
  * `$width` and `$height` is the resolution of the new (output) image in pixels
* `createFromResource($resrouce)` where
  * `$resrouce` is a pointer to a *GD Graphics Library* resrouce 
* `createFromFile($file)` where
  * `$file` is the uploaded (input) file path
* `save($file, $quality)` where
  * `$file` is the output file path
  * and the `$quality` is the output image quality (0..100)
* `dispose()` is responsibe for closing the underlying *GD Graphics Library* resource gracefully

#### Scenario - manipulate an image

In this case the **Zap** class can help to rotate, scale and crop an image.
It is also possible to change the background or the opacity of an image with the current implementation.

```
private function process(){
  $data = json_decode($_POST['data'], true);
  list($width, $height, $scale, $rotate, $horizontal, $vertical, $color, $opacity, $quality) =
    array(
      $data['output']['width'], $data['output']['height'], $data['scale'], $data['rotate'],
      $data['horizontal'], $data['vertical'], $data['color'], $data['opacity'], $data['quality']
    );
  $transformation = new ZapTransformation(
    $scale, $rotate, new ZapPosition($horizontal, $vertical),
    new ZapColor($color['r'], $color['g'], $color['b'], $color['a']), $opacity
  );
  $in = ZapStream::createFromFile(sprintf('%s/%s', _, $this->editable));
  $ou = ZapStream::create($width, $height);
  $zp = new Zap();
  $zp->process($transformation, $in, $ou);
  $ou->save(sprintf('%s/%s', _, $this->retouched), $quality);
  $ou->dispose();
  $in->dispose();
  $in = $ou = null;
}
```

###### Zap class method(s)
* `process(ZapTransformation $transformation, ZapStream &$streamIn, ZapStream &$streamOut)` where
  * `$transformation` has all the required information to alter (e.g.: rotate, scale, etc ...) an image
  * `$streamIn` is a pointer to the input image stream
  * `$streamOut`is a pointer to the output image stream

## How to create a transformation?
#### A basic guide

The snippet below will help to get started with the **ZapTransformation** class.

```
$transformation = new ZapTransformation(
  $scale, $rotate, new ZapPosition($horizontal, $vertical),
  new ZapColor($color['r'], $color['g'], $color['b'], $color['a']), $opacity
);
```

###### ZapTransformation class method(s)
* `__construct($scale, $rotate, ZapPosition $position, ZapColor $color, $opacity)` where
  * `$scale` 0..1, 1 = 100%
  * `$rotate` 0...360, 1 = 1 degree
  * `$position`is the postion of the top layer (input image)
    * `$x` is the horizontal coordinate
    * `$y` is the vertical coordinate
  * `$color` is the background color of the bottom layer (output image)
    * `$r` 0..255, red color
    * `$g` 0..255, green color
    * `$b` 0..255, blue color,
    * `$a` 0..1, opacity, 1 = 100%
  * `$opacity` 0..1, opacity of the top layer (input image), 1 = 100%
  
## Is there any working examples?
  
Yes, it is. The source code is bundled with a demo web application.
Just navigate to the index page and then give it a go!

#### First run

`bower install`
  
## Any legal issue?
  
No, it is absoultely free.
Please feel free to contribute or change any part of the code.
