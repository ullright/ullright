<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebRequest class.
 *
 * This class manages web requests. It parses input from the request and store them as parameters.
 *
 * @package    symfony
 * @subpackage request
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 * @version    SVN: $Id: sfWebRequest.class.php 13485 2008-11-29 14:48:24Z fabien $
 */
class sfWebRequest extends sfRequest
{
  protected
    $languages              = null,
    $charsets               = null,
    $acceptableContentTypes = null,
    $pathInfoArray          = null,
    $relativeUrlRoot        = null,
    $getParameters          = null,
    $postParameters         = null,
    $requestParameters      = null,
    $formats                = array(),
    $format                 = null;

  /**
   * Initializes this sfRequest.
   *
   * Available options:
   *
   *  * formats:           The list of supported format and their associated mime-types
   *  * path_info_key:     The path info key (default to SERVER)
   *  * path_info_array:   The path info key (default to PATH_INFO)
   *  * relative_url_root: The relative URL root
   *
   * @param  sfEventDispatcher $dispatcher  An sfEventDispatcher instance
   * @param  array             $parameters  An associative array of initialization parameters
   * @param  array             $attributes  An associative array of initialization attributes
   * @param  array             $options     An associative array of options
   *
   * @return bool true, if initialization completes successfully, otherwise false
   *
   * @throws <b>sfInitializationException</b> If an error occurs while initializing this sfRequest
   *
   * @see sfRequest
   */
  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array())
  {
    parent::initialize($dispatcher, $parameters, $attributes, $options);

    // GET parameters
    $this->getParameters = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_GET) : $_GET;
    $this->parameterHolder->add($this->getParameters);

    // POST parameters
    $this->postParameters = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_POST) : $_POST;
    $this->parameterHolder->add($this->postParameters);

    if (isset($_SERVER['REQUEST_METHOD']))
    {
      switch ($_SERVER['REQUEST_METHOD'])
      {
        case 'GET':
          $this->setMethod(self::GET);
          break;

        case 'POST':
          $this->setMethod(strtoupper($this->getParameter('sf_method', 'POST')));
          $this->parameterHolder->remove('sf_method');
          break;

        case 'PUT':
          $this->setMethod(self::PUT);
          break;

        case 'DELETE':
          $this->setMethod(self::DELETE);
          break;

        case 'HEAD':
          $this->setMethod(self::HEAD);
          break;

        default:
          $this->setMethod(self::GET);
      }
    }
    else
    {
      // set the default method
      $this->setMethod(self::GET);
    }

    if (isset($this->options['formats']))
    {
      foreach ($this->options['formats'] as $format => $mimeTypes)
      {
        $this->setFormat($format, $mimeTypes);
      }
    }

    if (!isset($this->options['path_info_key']))
    {
      $this->options['path_info_key'] = 'PATH_INFO';
    }

    if (!isset($this->options['path_info_array']))
    {
      $this->options['path_info_array'] = 'SERVER';
    }

    // additional parameters
    $this->requestParameters = $this->parseRequestParameters();
    $this->parameterHolder->add($this->requestParameters);

    $this->fixParameters();
  }

  /**
   * Retrieves the uniform resource identifier for the current web request.
   *
   * @return string Unified resource identifier
   */
  public function getUri()
  {
    $pathArray = $this->getPathInfoArray();

    // for IIS with rewrite module (IIFR, ISAPI Rewrite, ...)
    if ('HTTP_X_REWRITE_URL' == sfConfig::get('sf_path_info_key'))
    {
      $uri = isset($pathArray['HTTP_X_REWRITE_URL']) ? $pathArray['HTTP_X_REWRITE_URL'] : '';
    }
    else
    {
      $uri = isset($pathArray['REQUEST_URI']) ? $pathArray['REQUEST_URI'] : '';
    }

    return $this->isAbsUri() ? $uri : $this->getUriPrefix().$uri;
  }

  /**
   * See if the client is using absolute uri
   *
   * @return boolean true, if is absolute uri otherwise false
   */
  public function isAbsUri()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['REQUEST_URI']) ? preg_match('/^http/', $pathArray['REQUEST_URI']) : false;
  }

  /**
   * Returns Uri prefix, including protocol, hostname and server port.
   *
   * @return string Uniform resource identifier prefix
   */
  public function getUriPrefix()
  {
    $pathArray = $this->getPathInfoArray();
    if ($this->isSecure())
    {
      $standardPort = '443';
      $protocol = 'https';
    }
    else
    {
      $standardPort = '80';
      $protocol = 'http';
    }

    $host = explode(":", $this->getHost());
    if (count($host) == 1)
    {
      $host[] = isset($pathArray['SERVER_PORT']) ? $pathArray['SERVER_PORT'] : '';
    }

    if ($host[1] == $standardPort || empty($host[1]))
    {
      unset($host[1]);
    }

    return $protocol.'://'.implode(':', $host);;
  }

  /**
   * Retrieves the path info for the current web request.
   *
   * @return string Path info
   */
  public function getPathInfo()
  {
    $pathInfo = '';

    $pathArray = $this->getPathInfoArray();

    // simulate PATH_INFO if needed
    $sf_path_info_key = $this->options['path_info_key'];
    if (!isset($pathArray[$sf_path_info_key]) || !$pathArray[$sf_path_info_key])
    {
      if (isset($pathArray['REQUEST_URI']))
      {
        $script_name = $this->getScriptName();
        $uri_prefix = $this->isAbsUri() ? $this->getUriPrefix() : '';
        $pathInfo = preg_replace('/^'.preg_quote($uri_prefix, '/').'/','',$pathArray['REQUEST_URI']);
        $pathInfo = preg_replace('/^'.preg_quote($script_name, '/').'/', '', $pathInfo);
        $prefix_name = preg_replace('#/[^/]+$#', '', $script_name);
        $pathInfo = preg_replace('/^'.preg_quote($prefix_name, '/').'/', '', $pathInfo);
        $pathInfo = preg_replace('/\??'.preg_quote($pathArray['QUERY_STRING'], '/').'$/', '', $pathInfo);
      }
    }
    else
    {
      $pathInfo = $pathArray[$sf_path_info_key];
      if ($relativeUrlRoot = $this->getRelativeUrlRoot())
      {
        $pathInfo = preg_replace('/^'.str_replace('/', '\\/', $relativeUrlRoot).'\//', '', $pathInfo);
      }
    }

    // for IIS
    if (isset($_SERVER['SERVER_SOFTWARE']) && false !== stripos($_SERVER['SERVER_SOFTWARE'], 'iis') && $pos = stripos($pathInfo, '.php'))
    {
      $pathInfo = substr($pathInfo, $pos + 4);
    }

    if (!$pathInfo)
    {
      $pathInfo = '/';
    }

    return $pathInfo;
  }

  public function getPathInfoPrefix()
  {
    $prefix = $this->getRelativeUrlRoot();

    if (!isset($this->options['no_script_name']) || !$this->options['no_script_name'])
    {
      $scriptName = $this->getScriptName();
      $prefix = is_null($prefix) ? $scriptName : $prefix.'/'.basename($scriptName);
    }

    return $prefix;
  }

  public function getGetParameters()
  {
    return $this->getParameters;
  }

  public function getPostParameters()
  {
    return $this->postParameters;
  }

  public function getRequestParameters()
  {
    return $this->requestParameters;
  }

  public function addRequestParameters($parameters)
  {
    $this->requestParameters = array_merge($this->requestParameters, $parameters);
    $this->getParameterHolder()->add($parameters);

    $this->fixParameters();
  }

  /**
   * Returns referer.
   *
   * @return string
   */
  public function get()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['HTTP_REFERER']) ? $pathArray['HTTP_REFERER'] : '';
  }

  /**
   * Returns current host name.
   *
   * @return string
   */
  public function getHost()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['HTTP_X_FORWARDED_HOST']) ? $pathArray['HTTP_X_FORWARDED_HOST'] : (isset($pathArray['HTTP_HOST']) ? $pathArray['HTTP_HOST'] : '');
  }

  /**
   * Returns current script name.
   *
   * @return string
   */
  public function getScriptName()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['SCRIPT_NAME']) ? $pathArray['SCRIPT_NAME'] : (isset($pathArray['ORIG_SCRIPT_NAME']) ? $pathArray['ORIG_SCRIPT_NAME'] : '');
  }

  /**
   * Checks if the request method is the given one.
   *
   * @param  string $method  The method name
   *
   * @return bool true if the current method is the given one, false otherwise
   */
  public function isMethod($method)
  {
    return strtoupper($method) == $this->getMethod();
  }

  /**
   * Returns request method.
   *
   * @return string
   */
  public function getMethodName()
  {
    if ($this->options['logging'])
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array('The "sfWebRequest::getMethodName()" method is deprecated, please use "getMethod()" instead.', 'priority' => sfLogger::WARNING)));
    }

    return $this->getMethod();
  }

  /**
   * Returns the preferred culture for the current request.
   *
   * @param  array  $cultures  An array of ordered cultures available
   *
   * @return string The preferred culture
   */
  public function getPreferredCulture(array $cultures = null)
  {
    $preferredCultures = $this->getLanguages();

    if (is_null($cultures))
    {
      return isset($preferredCultures[0]) ? $preferredCultures[0] : null;
    }

    if (!$preferredCultures)
    {
      return $cultures[0];
    }

    $preferredCultures = array_values(array_intersect($preferredCultures, $cultures));

    return isset($preferredCultures[0]) ? $preferredCultures[0] : $cultures[0];
  }

  /**
   * Gets a list of languages acceptable by the client browser
   *
   * @return array Languages ordered in the user browser preferences
   */
  public function getLanguages()
  {
    if ($this->languages)
    {
      return $this->languages;
    }

    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    {
      return array();
    }

    $languages = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    foreach ($languages as $lang)
    {
      if (strstr($lang, '-'))
      {
        $codes = explode('-', $lang);
        if ($codes[0] == 'i')
        {
          // Language not listed in ISO 639 that are not variants
          // of any listed language, which can be registerd with the
          // i-prefix, such as i-cherokee
          if (count($codes) > 1)
          {
            $lang = $codes[1];
          }
        }
        else
        {
          for ($i = 0, $max = count($codes); $i < $max; $i++)
          {
            if ($i == 0)
            {
              $lang = strtolower($codes[0]);
            }
            else
            {
              $lang .= '_'.strtoupper($codes[$i]);
            }
          }
        }
      }

      $this->languages[] = $lang;
    }

    return $this->languages;
  }

  /**
   * Gets a list of charsets acceptable by the client browser.
   *
   * @return array List of charsets in preferable order
   */
  public function getCharsets()
  {
    if ($this->charsets)
    {
      return $this->charsets;
    }

    if (!isset($_SERVER['HTTP_ACCEPT_CHARSET']))
    {
      return array();
    }

    $this->charsets = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_CHARSET']);

    return $this->charsets;
  }

  /**
   * Gets a list of content types acceptable by the client browser
   *
   * @return array Languages ordered in the user browser preferences
   */
  public function getAcceptableContentTypes()
  {
    if ($this->acceptableContentTypes)
    {
      return $this->acceptableContentTypes;
    }

    if (!isset($_SERVER['HTTP_ACCEPT']))
    {
      return array();
    }

    $this->acceptableContentTypes = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT']);

    return $this->acceptableContentTypes;
  }

  /**
   * Returns true if the request is a XMLHttpRequest.
   *
   * It works if your JavaScript library set an X-Requested-With HTTP header.
   * Works with Prototype, Mootools, jQuery, and perhaps others.
   *
   * @return bool true if the request is an XMLHttpRequest, false otherwise
   */
  public function isXmlHttpRequest()
  {
    return ($this->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
  }

  public function getHttpHeader($name, $prefix = 'http')
  {
    if ($prefix)
    {
      $prefix = strtoupper($prefix).'_';
    }

    $name = $prefix.strtoupper(strtr($name, '-', '_'));

    $pathArray = $this->getPathInfoArray();

    return isset($pathArray[$name]) ? sfToolkit::stripslashesDeep($pathArray[$name]) : null;
  }

  /**
   * Gets a cookie value.
   *
   * @param  string $name     Cookie name
   * @param  string $default  Default value returned when no cookie with given name is found
   *
   * @return mixed
   */
  public function getCookie($name, $defaultValue = null)
  {
    $retval = $defaultValue;

    if (isset($_COOKIE[$name]))
    {
      $retval = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_COOKIE[$name]) : $_COOKIE[$name];
    }

    return $retval;
  }

  /**
   * Returns true if the current request is secure (HTTPS protocol).
   *
   * @return boolean
   */
  public function isSecure()
  {
    $pathArray = $this->getPathInfoArray();

    return (
      (isset($pathArray['HTTPS']) && (strtolower($pathArray['HTTPS']) == 'on' || $pathArray['HTTPS'] == 1))
      ||
      (isset($pathArray['HTTP_SSL_HTTPS']) && (strtolower($pathArray['HTTP_SSL_HTTPS']) == 'on' || $pathArray['HTTP_SSL_HTTPS'] == 1))
      ||
      (isset($pathArray['HTTP_X_FORWARDED_PROTO']) && strtolower($pathArray['HTTP_X_FORWARDED_PROTO']) == 'https')
    );
  }

  /**
   * Retrieves relative root url.
   *
   * @return string URL
   */
  public function getRelativeUrlRoot()
  {
    if (is_null($this->relativeUrlRoot))
    {
      if (!isset($this->options['relative_url_root']))
      {
        $this->relativeUrlRoot = preg_replace('#/[^/]+\.php5?$#', '', $this->getScriptName());
      }
      else
      {
        $this->relativeUrlRoot = $this->options['relative_url_root'];
      }
    }

    return $this->relativeUrlRoot;
  }

  /**
   * Sets the relative root url for the current web request.
   *
   * @param string $value  Value for the url
   */
  public function setRelativeUrlRoot($value)
  {
    $this->relativeUrlRoot = $value;
  }

  /**
   * Splits an HTTP header for the current web request.
   *
   * @param string $header  Header to split
   */
  public function splitHttpAcceptHeader($header)
  {
    $values = array();
    foreach (array_filter(explode(',', $header)) as $value)
    {
      // Cut off any q-value that might come after a semi-colon
      if ($pos = strpos($value, ';'))
      {
        $q     = (float) trim(substr($value, $pos + 3));
        $value = trim(substr($value, 0, $pos));
      }
      else
      {
        $q = 1;
      }

      $values[$value] = $q;
    }

    arsort($values);

    return array_keys($values);
  }

  /**
   * Returns the array that contains all request information ($_SERVER or $_ENV).
   *
   * This information is stored in the [sf_path_info_array] constant.
   *
   * @return  array Path information
   */
  public function getPathInfoArray()
  {
    if (!$this->pathInfoArray)
    {
      // parse PATH_INFO
      switch ($this->options['path_info_array'])
      {
        case 'SERVER':
          $this->pathInfoArray =& $_SERVER;
          break;

        case 'ENV':
        default:
          $this->pathInfoArray =& $_ENV;
      }
    }

    return $this->pathInfoArray;
  }

  /**
   * Gets the mime type associated with the format.
   *
   * @param  string $format  The format
   *
   * @return string The associated mime type (null if not found)
   */
  public function getMimeType($format)
  {
    return isset($this->formats[$format]) ? $this->formats[$format][0] : null;
  }

  /**
   * Gets the format associated with the mime type.
   *
   * @param  string $mimeType  The associated mime type
   *
   * @return string The format (null if not found)
   */
  public function getFormat($mimeType)
  {
    foreach ($this->formats as $format => $mimeTypes)
    {
      if (in_array($mimeType, $mimeTypes))
      {
        return $format;
      }
    }

    return null;
  }

  /**
   * Associates a format with mime types.
   *
   * @param string       $format     The format
   * @param string|array $mimeTypes  The associated mime types (the preferred one must be the first as it will be used as the content type)
   */
  public function setFormat($format, $mimeTypes)
  {
    $this->formats[$format] = is_array($mimeTypes) ? $mimeTypes : array($mimeTypes);
  }

  /**
   * Sets the request format.
   *
   * @param string $format  The request format
   */
  public function setRequestFormat($format)
  {
    $this->format = $format;
  }

  /**
   * Gets the request format.
   *
   * Here is the process to determine the format:
   *
   *  * format defined by the user (with setRequestFormat())
   *  * sf_format request parameter
   *  * null
   *
   * @return string The request format
   */
  public function getRequestFormat()
  {
    if (is_null($this->format))
    {
      $this->setRequestFormat($this->getParameter('sf_format'));
    }

    return $this->format;
  }

  /**
   * Retrieves an array of files.
   *
   * @param  string $key  A key
   * @return array  An associative array of files
   */
  static public function getFiles($key = null)
  {
    return is_null($key) ? $_FILES : (isset($_FILES[$key]) ? $_FILES[$key] : array());
  }

  /**
   * Returns the value of a GET parameter.
   *
   * @param  string $name     The GET parameter name
   * @param  string $default  The default value
   *
   * @return string The GET parameter value
   */
  public function getGetParameter($name, $default = null)
  {
    if (isset($this->getParameters[$name]))
    {
      return $this->getParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->getParameters, $name, $default);
    }
  }

  /**
   * Returns the value of a POST parameter.
   *
   * @param  string $name     The POST parameter name
   * @param  string $default  The default value
   *
   * @return string The POST parameter value
   */
  public function getPostParameter($name, $default = null)
  {
    if (isset($this->postParameters[$name]))
    {
      return $this->postParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->postParameters, $name, $default);
    }
  }

  /**
   * Returns the value of a parameter passed as a URL segment.
   *
   * @param  string $name     The parameter name
   * @param  string $default  The default value
   *
   * @return string The parameter value
   */
  public function getUrlParameter($name, $default = null)
  {
    if (isset($this->requestParameters[$name]))
    {
      return $this->requestParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->requestParameters, $name, $default);
    }
  }

  /**
   * Returns the remote IP address that made the request.
   *
   * @return string The remote IP address
   */
  public function getRemoteAddress()
  {
    $pathInfo = $this->getPathInfoArray();

    return $pathInfo['REMOTE_ADDR'];
  }

  /**
   * Returns an array containing a list of IPs, the first being the client address
   * and the others the addresses of each proxy that passed the request. The address 
   * for the last proxy can be retrieved via getRemoteAddress().
   *
   * This method returns null if no proxy passed this request. Note that some proxies
   * do not use this header, and act as if they were the client.
   *
   * @return string|null An array of IP from the client and the proxies that passed
   * the request, or null if no proxy was used.
   */
  public function getForwardedFor()
  {
    $pathInfo = $this->getPathInfoArray();

    if (empty($pathInfo['HTTP_X_FORWARDED_FOR']))
    {
      return null;
    }

    return explode(', ', $pathInfo['HTTP_X_FORWARDED_FOR']);
  }

  public function checkCSRFProtection()
  {
    $form = new sfForm();
    $form->bind($form->isCSRFProtected() ? array($form->getCSRFFieldName() => $this->getParameter($form->getCSRFFieldName())) : array());

    if (!$form->isValid())
    {
      throw $form->getErrorSchema();
    }
  }

  /**
   * Parses the request parameters.
   *
   * This method notifies the request.filter_parameters event.
   *
   * @return array An array of request parameters.
   */
  protected function parseRequestParameters()
  {
    return $this->dispatcher->filter(new sfEvent($this, 'request.filter_parameters', $this->getRequestContext()), array())->getReturnValue();
  }

  /**
   * Returns the request context used.
   *
   * @param array An array of values representing the current request
   */
  public function getRequestContext()
  {
    return array(
      'path_info'   => $this->getPathInfo(),
      'prefix'      => $this->getPathInfoPrefix(),
      'method'      => $this->getMethod(),
      'format'      => $this->getRequestFormat(),
      'host'        => $this->getHost(),
      'is_secure'   => $this->isSecure(),
      'request_uri' => $this->getUri(),
    );
  }

  protected function fixParameters()
  {
    // move symfony parameters to attributes (parameters prefixed with _sf_)
    foreach ($this->parameterHolder->getAll() as $key => $value)
    {
      if (0 === stripos($key, '_sf_'))
      {
        $this->parameterHolder->remove($key);
        $this->setAttribute(substr($key, 1), $value);
      }
    }
  }
}
