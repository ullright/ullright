<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

$t = new myTestCase(3, new lime_output_color, $configuration);


$html = '
      <div class="element_text_with_image" id="element_text_with_image_xyz123">
        <h1>Good news everyone!</h1>
        <img src="/ullCoreThemeNG/images/logo120.png" alt="image" />
        <p>
          Wow<br />cool <b>bold!</b>
        </p>
        <input type="hidden" value="{&quot;element&quot;:&quot;text_with_image&quot;,&quot;id&quot;:&quot;xyz123&quot;,&quot;headline&quot;:&quot;Good news everyone!&quot;,&quot;image&quot;:&quot;\/upload\/images\/foo.jpg&quot;,&quot;body&quot;:&quot;&lt;p&gt;\n  Wow&lt;br \/&gt;cool &lt;b&gt;bold!&lt;\/b&gt;\n&lt;\/p&gt;&quot;}" />
      </div>
      
      <div class="element_gallery gallery" id="element_gallery_abx890">
        <div class="gallery_image">
          <a href="/uploads/images/123.jpg">
            <img src="/uploads/userPhotos/001.jpg" alt="image" />
          </a>
        </div>
        <div class="gallery_image">
          <a href="/uploads/images/124.jpg">
            <img src="/uploads/images/124_thumbnail.jpg" alt="image" />
          </a>
        </div>  
        <input type="hidden" value="{&quot;element&quot;:&quot;gallery&quot;,&quot;id&quot;:&quot;abx890&quot;,&quot;gallery&quot;:&quot;\/uploads\/images\/123.jpg\n\/uploads\/images\/124.jpg&quot;}" />
      </div> 
';



$data = array(
  'type'   => 'text_with_image',
  'id'=> 'xyz123',
  'values'    => array(
    'headline'  => 'Good news everyone!',
    'image'     => '/ullCoreThemeNG/images/logo120.png',
    'body'      => '<p>
      Wow<br />cool <b>bold!</b>
      </p>',
  ),
);

$json = htmlentities(json_encode($data));
var_dump($json);


$data = array(
  'type'   => 'gallery',
  'id'=> 'abx890',
  'values'    => array(
    'gallery'   => "/uploads/userPhotos/001.jpg\n/uploads/userPhotos/002.jpg",
  ),
);

$json = htmlentities(json_encode($data));
var_dump($json);
    




