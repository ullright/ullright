<?php

class UllNewsImageUploadReq extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('ull_news', 'image_upload', 'string', 255);
  }

  public function down()
  {
  }
}
