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
 * Base class for all adapters
 */
abstract class Solarium_Client_Adapter extends Solarium_Configurable
{

     /**
     * Default options
     *
     * @var array
     */
    protected $_options = array(
        'adapteroptions' => array(
            'timeout' => 5
        ),
    );

    /**
     * Set options (overrides any existing values)
     *
     * @param array $options
     * @return void
     */
    public function setOptions($options)
    {
        $this->_setOptions($options, true);
    }

    /**
     * Abstract method to require an implementation inside all adapters.
     * If the adapter cannot support this method it should implement a method
     * that throws an exception.
     *
     * @abstract
     * @param Solarium_Query_Select $query
     * @return Solarium_Result_Select
     */
    abstract public function select($query);

    /**
     * Abstract method to require an implementation inside all adapters.
     * If the adapter cannot support this method it should implement a method
     * that throws an exception.
     *
     * @abstract
     * @param Solarium_Query_Ping $query
     * @return boolean
     */
    abstract public function ping($query);

    /**
     * Abstract method to require an implementation inside all adapters.
     * If the adapter cannot support this method it should implement a method
     * that throws an exception.
     *
     * @abstract
     * @param Solarium_Query_Update $query
     * @return Solarium_Result_Update
     */
    abstract public function update($query);

    
}