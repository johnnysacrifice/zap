<?php

  namespace zap{
    class ZapView{
      private $model;
      
      public function __construct(Array $model){
        $this->model = $this->dynamic($model);  
      }
      
      private function dynamic(Array $object){
        return json_decode(json_encode($object));
      }
      
      public function render(){
        $model = $this->model;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="it is surely not the xchng ...">
    <meta name="author" content="loo">
    <title><?php echo $model->title; ?></title>
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/sticky-footer-navbar.css">
    <link rel="stylesheet" href="assets/css/site.css?v=<?php echo $model->version; ?>">
    <!--[if lt IE 9]>
      <script src="vendor/html5shiv/dist/html5shiv.min.js"></script>
      <script src="vendor/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div>
      <nav class="navbar navbar-default navbar-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand"><?php echo $model->title; ?></a>
          </div>
        </div>
      </nav>
    </div>
    <div>
      <div class="container-fluid">

        <div>
          <h1>upload</h1>
          <hr>
          <p>Please select and upload one of your favourite picture.</p>
          <form method="post" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button" id="x-app-browse">browse</button>
                </span>
                <input class="form-control" type="text" id="x-app-selected">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <button class="btn btn-default" type="submit" name="do" value="upload">do</button>
              </div>
            </div>
            <input class="hide" name="picture" type="file" id="x-app-picture">
          </form>
        </div>
        
        <div>
          <h1>review</h1>
          <hr>
          <div>
<?php if(!$model->editable): ?>
            <p>There is no picture to review, yet ...</p>
<?php else: ?>
            <p>Please review your uploaded picture.</p>
            <div>
              <div class="viewport">
                <img src="<?php echo sprintf('%s?v=%s', $model->files->editable, $model->version); ?>">
              </div>
            </div>
<?php endif; ?>
          </div>
        </div>
        
        <div>
          <h1>retouch</h1>
          <hr>
          <div class="<?php if($model->editable): ?>hide<?php endif; ?>">
            <p>There is no picture to retouch, yet ...</p>
          </div>
          <div class="<?php if(!$model->editable): ?>hide<?php endif; ?>">
            <form method="post">
              <div class="form-group">
                <p>It is time to get your hands dirty!</p>
              </div>
              <div class="form-group">
                <div class="viewport" id="x-app-retouch-viewport">
                  <img src="<?php echo sprintf('%s?v=%s', $model->files->editable, $model->version); ?>" id="x-app-retouch-picture">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <div class="btn-toolbar">
                    <div class="btn-group">
                      <div class="btn btn-primary">S</div>
                      <button class="btn btn-primary" type="button" id="x-app-scale-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-scale-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-scale-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <div class="btn btn-primary">R</div>
                      <button class="btn btn-primary" type="button" id="x-app-rotate-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-rotate-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-rotate-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <div class="btn btn-primary">H</div>
                      <button class="btn btn-primary" type="button" id="x-app-horizontal-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-horizontal-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-horizontal-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <div class="btn btn-primary">V</div>
                      <button class="btn btn-primary" type="button" id="x-app-vertical-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-vertical-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-vertical-no">...</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <div class="btn-toolbar">
                    <div class="btn-group">
                      <div class="btn btn-primary">B</div>
                      <div class="btn btn-primary"><input class="colorpicker" type="color" id="x-app-color" value="#FFFFFF"></div>
                      <button class="btn btn-primary" type="button" id="x-app-color-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-color-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-color-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <div class="btn btn-primary">O</div>
                      <button class="btn btn-primary" type="button" id="x-app-opacity-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-opacity-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-opacity-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <div class="btn btn-primary">Q</div>
                      <button class="btn btn-primary" type="button" id="x-app-quality-in" value="+">+</button>
                      <button class="btn btn-primary" type="button" id="x-app-quality-de" value="-">-</button>
                      <div class="btn btn-primary">
                        <span id="x-app-quality-no">...</span>
                      </div>
                    </div>
                    <div class="btn-group">
                      <button class="btn btn-primary" type="button" id="x-app-reset">reset</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                [S]cale, [R]otate, [H]orizontal position, [V]ertical position, [B]ackground, [O]pacity, [Q]uality
              </div>
              <div class="form-group">
                <div class="input-group">
                  <button class="btn btn-default" type="submit" name="do" value="retouch">do</button>
                </div>
              </div>
              <input type="hidden" name="data" id="x-app-data" value="<?php echo htmlentities($model->data); ?>">
            </form>
          </div>
        </div>

        <div>
          <h1>enjoy</h1>
          <hr>
          <div>
<?php if(!$model->retouched): ?>
            <p>No picture has been retouced, yet ...</p>
<?php else: ?>
            <form method="post">
              <div class="form-group">
                <div class="viewport">
                  <img src="<?php echo sprintf('%s?v=%s', $model->files->retouched, $model->version); ?>">
                </div>
              </div>
              <div class="form-group">
<?php foreach($model->files->thumbnails as &$thumb): ?>
                <img class="thumb" src="<?php echo sprintf('%s?v=%s', $thumb, $model->version); ?>">
<?php endforeach; ?>
              </div>
              <div class="form-group">
                <p>Not happy? No problem as we will shred your picture for free just for you!</p>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <button class="btn btn-default" type="submit" name="do" value="delete">do</button>
                </div>
              </div>
            </form>
<?php endif; ?>
          </div>
        </div>

        <div class="bottom"></div>

      </div>
    </div>
    <footer class="footer">
      <div class="container-fluid">
        <p class="text-muted">powered by zap</p>
      </div>
    </footer>
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="zap/zap.js?v=<?php echo $model->version; ?>"></script>
  </body>
</html>
<?php
      }
    }
  }

?>