<?php
/**
 * Copyright 2011 Bas de Nooijer. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 *
 * @copyright Copyright 2011 Bas de Nooijer <solarium@raspberry.nl>
 * @license http://github.com/basdenooijer/solarium/raw/master/COPYING
 *
 * @package Solarium
 * @subpackage Client
 */

/**
 *
 */
abstract class Solarium_Client_Request
{
    /**
     * Http request methods
     */
    const GET     = 'GET';
    const POST    = 'POST';
    const HEAD    = 'HEAD';

    /**
     * TODO
     *
     * @var Solarium_Query
     */
    protected $_query;

    /**
     * TODO
     *
     * @var array
     */
    protected $_options;

    /**
     * TODO
     * 
     * @var array
     */
    protected $_params;

    /**
     * TODO
     *
     * @param array|object $options
     * @param Solarium_Query $query
     */
    public function __construct($options, $query)
    {
        $this->_options = $options;
        $this->_query = $query;
    }

    /**
     * TODO
     *
     * @return string
     */
    public function getMethod()
    {
        return self::GET;
    }

    /**
     * TODO
     *
     * @abstract
     * @return void
     */
    abstract public function getUri();

    /**
     * TODO
     * 
     * @return null
     */
    public function getRawData()
    {
        return null;
    }

    /**
     * TODO
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Build a URL for this request
     *
     * @return string
     */
    public function buildUri()
    {
        $queryString = '';
        if (count($this->_params) > 0) {
            $queryString = http_build_query($this->_params, null, '&');
            $queryString = preg_replace(
                '/%5B(?:[0-9]|[1-9][0-9]+)%5D=/',
                '=',
                $queryString
            );
        }

        if (null !== $this->_options['core']) {
            $core = '/' . $this->_options['core'];
        } else {
            $core = '';
        }

        return 'http://' . $this->_options['host'] . ':'
               . $this->_options['port'] . $this->_options['path']
               . $core . $this->_query->getOption('path') . '?'
               . $queryString;
    }

    /**
     * Render a boolean attribute
     *
     * @param string $name
     * @param boolean $value
     * @return string
     */
    public function boolAttrib($name, $value)
    {
        if (null !== $value) {
            $value = (true == $value) ? 'true' : 'false';
            return $this->attrib($name, $value);
        } else {
            return '';
        }
    }

    /**
     * Render an attribute
     *
     * @param string $name
     * @param striung $value
     * @return string
     */
    public function attrib($name, $value)
    {
        if (null !== $value) {
            return ' ' . $name . '="' . $value . '"';
        } else {
            return '';
        }
    }
    
    /**
     * Render a param with localParams
     *
     * @param string $value
     * @param array $localParams in key => value format
     * @return string with Solr localparams syntax
     */
    public function renderLocalParams($value, $localParams = array())
    {
        $params = '';
        foreach ($localParams AS $paramName => $paramValue) {
            if (empty($paramValue)) continue;

            if (is_array($paramValue)) {
                $paramValue = implode($paramValue, ',');
            }

            $params .= $paramName . '=' . $paramValue . ' ';
        }

        if ($params !== '') {
            $value = '{!' . trim($params) . '}' . $value;
        }

        return $value;
    }


    public function addParam($name, $value)
    {
        if (0 === strlen($value)) return;

        if (!isset($this->_params[$name])) {
            $this->_params[$name] = $value;
        } else {
            if (!is_array($this->_params[$name])) {
                $this->_params[$name] = array($this->_params[$name]);
            }
            $this->_params[$name][] = $value;
        }
    }

}