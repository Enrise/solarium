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
 */

class Solarium_Query_SelectTest extends PHPUnit_Framework_TestCase
{

    protected $_query;

    public function setUp()
    {
        $this->_query = new Solarium_Query_Select;
    }

    public function testSetAndGetStart()
    {
        $this->_query->setStart(234);
        $this->assertEquals(234, $this->_query->getStart());
    }

    public function testSetAndGetQueryWithTrim()
    {
        $this->_query->setQuery(' *:* ');
        $this->assertEquals('*:*', $this->_query->getQuery());
    }

    public function testSetAndGetResultClass()
    {
        $this->_query->setResultClass('MyResult');
        $this->assertEquals('MyResult', $this->_query->getResultClass());
    }

    public function testSetAndGetDocumentClass()
    {
        $this->_query->setDocumentClass('MyDocument');
        $this->assertEquals('MyDocument', $this->_query->getDocumentClass());
    }

    public function testSetAndGetRows()
    {
        $this->_query->setRows(100);
        $this->assertEquals(100, $this->_query->getRows());
    }

    public function testAddField()
    {
        $expectedFields = $this->_query->getFields();
        $expectedFields[] = 'newfield';
        $this->_query->addField('newfield');
        $this->assertEquals($expectedFields, $this->_query->getFields());
    }

    public function testClearFields()
    {
        $this->_query->addField('newfield');
        $this->_query->clearFields();
        $this->assertEquals(array(), $this->_query->getFields());
    }

    public function testAddFields()
    {
        $fields = array('field1','field2');

        $this->_query->clearFields();
        $this->_query->addFields($fields);
        $this->assertEquals($fields, $this->_query->getFields());
    }

    public function testAddFieldsAsStringWithTrim()
    {
        $this->_query->clearFields();
        $this->_query->addFields('field1, field2');
        $this->assertEquals(array('field1','field2'), $this->_query->getFields());
    }

    public function testRemoveField()
    {
        $this->_query->clearFields();
        $this->_query->addFields(array('field1','field2'));
        $this->_query->removeField('field1');
        $this->assertEquals(array('field2'), $this->_query->getFields());
    }

    public function testSetFields()
    {
        $this->_query->clearFields();
        $this->_query->addFields(array('field1','field2'));
        $this->_query->setFields(array('field3','field4'));
        $this->assertEquals(array('field3','field4'), $this->_query->getFields());
    }

    public function testAddSortField()
    {
        $this->_query->addSortField('field1', Solarium_Query_Select::SORT_DESC);
        $this->assertEquals(
            array('field1' => Solarium_Query_Select::SORT_DESC),
            $this->_query->getSortFields()
        );
    }

    public function testAddSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->assertEquals(
            $sortFields,
            $this->_query->getSortFields()
        );
    }

    public function testRemoveSortField()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->removeSortField('field1');
        $this->assertEquals(
            array('field2' => Solarium_Query_Select::SORT_ASC),
            $this->_query->getSortFields()
        );
    }

    public function testRemoveInvalidSortField()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->removeSortField('invalidfield'); //continue silently
        $this->assertEquals(
            $sortFields,
            $this->_query->getSortFields()
        );
    }

    public function testClearSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->clearSortFields();
        $this->assertEquals(
            array(),
            $this->_query->getSortFields()
        );
    }

    public function testSetSortFields()
    {
        $sortFields = array(
            'field1' => Solarium_Query_Select::SORT_DESC,
            'field2' => Solarium_Query_Select::SORT_ASC
        );

        $this->_query->addSortFields($sortFields);
        $this->_query->setSortFields(array('field3' => Solarium_Query_Select::SORT_ASC));
        $this->assertEquals(
            array('field3' => Solarium_Query_Select::SORT_ASC),
            $this->_query->getSortFields()
        );
    }
    
    public function testAddAndGetFilterQuery()
    {
        $fq = new Solarium_Query_Select_FilterQuery;
        $fq->setKey('fq1')->setQuery('category:1');
        $this->_query->addFilterQuery($fq);

        $this->assertEquals(
            $fq,
            $this->_query->getFilterQuery('fq1')
        );
    }

    public function testAddFilterQueryWithoutKey()
    {
        $fq = new Solarium_Query_Select_FilterQuery;
        $fq->setQuery('category:1');

        $this->setExpectedException('Solarium_Exception');
        $this->_query->addFilterQuery($fq);
    }

    public function testAddFilterQueryWithUsedKey()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq1')->setQuery('category:2');

        $this->_query->addFilterQuery($fq1);
        $this->setExpectedException('Solarium_Exception');
        $this->_query->addFilterQuery($fq2);
    }

    public function testGetInvalidFilterQuery()
    {
        $this->assertEquals(
            null,
            $this->_query->getFilterQuery('invalidtag')
        );
    }

    public function testAddFilterQueries()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq2')->setQuery('category:2');

        $filterQueries = array('fq1' => $fq1, 'fq2' => $fq2);

        $this->_query->addFilterQueries($filterQueries);
        $this->assertEquals(
            $filterQueries,
            $this->_query->getFilterQueries()
        );
    }

    public function testRemoveFilterQuery()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq2')->setQuery('category:2');

        $filterQueries = array($fq1, $fq2);

        $this->_query->addFilterQueries($filterQueries);
        $this->_query->removeFilterQuery('fq1');
        $this->assertEquals(
            array('fq2' => $fq2),
            $this->_query->getFilterQueries()
        );
    }

    public function testRemoveInvalidFilterQuery()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq2')->setQuery('category:2');

        $filterQueries = array('fq1' => $fq1, 'fq2' => $fq2);

        $this->_query->addFilterQueries($filterQueries);
        $this->_query->removeFilterQuery('fq3'); //continue silently
        $this->assertEquals(
            $filterQueries,
            $this->_query->getFilterQueries()
        );
    }

    public function testClearFilterQueries()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq2')->setQuery('category:2');

        $filterQueries = array($fq1, $fq2);

        $this->_query->addFilterQueries($filterQueries);
        $this->_query->clearFilterQueries();
        $this->assertEquals(
            array(),
            $this->_query->getFilterQueries()
        );
    }

    public function testSetFilterQueries()
    {
        $fq1 = new Solarium_Query_Select_FilterQuery;
        $fq1->setKey('fq1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_FilterQuery;
        $fq2->setKey('fq2')->setQuery('category:2');

        $filterQueries1 = array('fq1' => $fq1, 'fq2' => $fq2);

        $this->_query->addFilterQueries($filterQueries1);

        $fq3 = new Solarium_Query_Select_FilterQuery;
        $fq3->setKey('fq3')->setQuery('category:3');

        $fq4 = new Solarium_Query_Select_FilterQuery;
        $fq4->setKey('fq4')->setQuery('category:4');

        $filterQueries2 = array('fq3' => $fq3, 'fq4' => $fq4);

        $this->_query->setFilterQueries($filterQueries2);

        $this->assertEquals(
            $filterQueries2,
            $this->_query->getFilterQueries()
        );
    }

    public function testAddAndGetFacet()
    {
        $fq = new Solarium_Query_Select_Facet_Query;
        $fq->setKey('f1')->setQuery('category:1');
        $this->_query->addFacet($fq);

        $this->assertEquals(
            $fq,
            $this->_query->getFacet('f1')
        );
    }

    public function testAddFacetWithoutKey()
    {
        $fq = new Solarium_Query_Select_Facet_Query;
        $fq->setQuery('category:1');

        $this->setExpectedException('Solarium_Exception');
        $this->_query->addFacet($fq);
    }

    public function testAddFacetWithUsedKey()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f1')->setQuery('category:2');

        $this->_query->addFacet($fq1);
        $this->setExpectedException('Solarium_Exception');
        $this->_query->addFacet($fq2);
    }

    public function testGetInvalidFacet()
    {
        $this->assertEquals(
            null,
            $this->_query->getFacet('invalidtag')
        );
    }

    public function testAddFacets()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f2')->setQuery('category:2');

        $facets = array('f1' => $fq1, 'f2' => $fq2);

        $this->_query->addFacets($facets);
        $this->assertEquals(
            $facets,
            $this->_query->getFacets()
        );
    }

    public function testRemoveFacet()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f2')->setQuery('category:2');

        $facets = array('f1' => $fq1, 'f2' => $fq2);

        $this->_query->addFacets($facets);
        $this->_query->removeFacet('f1');
        $this->assertEquals(
            array('f2' => $fq2),
            $this->_query->getFacets()
        );
    }

    public function testRemoveInvalidFacet()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f2')->setQuery('category:2');

        $facets = array('f1' => $fq1, 'f2' => $fq2);

        $this->_query->addFacets($facets);
        $this->_query->removeFacet('f3'); //continue silently
        $this->assertEquals(
            $facets,
            $this->_query->getFacets()
        );
    }

    public function testClearFacets()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f2')->setQuery('category:2');

        $facets = array('f1' => $fq1, 'f2' => $fq2);

        $this->_query->addFacets($facets);
        $this->_query->clearFacets();
        $this->assertEquals(
            array(),
            $this->_query->getFacets()
        );
    }

    public function testSetFacets()
    {
        $fq1 = new Solarium_Query_Select_Facet_Query;
        $fq1->setKey('f1')->setQuery('category:1');

        $fq2 = new Solarium_Query_Select_Facet_Query;
        $fq2->setKey('f2')->setQuery('category:2');

        $facets = array('f1' => $fq1, 'f2' => $fq2);

        $this->_query->addFacets($facets);

        $fq3 = new Solarium_Query_Select_Facet_Query;
        $fq3->setKey('f3')->setQuery('category:3');

        $fq4 = new Solarium_Query_Select_Facet_Query;
        $fq4->setKey('f4')->setQuery('category:4');

        $facets = array('f3' => $fq3, 'f4' => $fq4);

        $this->_query->setFacets($facets);

        $this->assertEquals(
            $facets,
            $this->_query->getFacets()
        );
    }

    public function testConstructorWithConfig()
    {
        $config = array(
            'query'  => 'text:mykeyword',
            'sort'   => array('score' => 'asc'),
            'fields' => array('id','title','category'),
            'rows'   => 100,
            'start'  => 200,
            'filterquery' => array(
                array('key' => 'pub', 'tag' => array('pub'),'query' => 'published:true')
            ),
            'facet' => array(
                array('type' => 'field', 'key' => 'categories', 'field' => 'category'),
                array('type' => 'query', 'key' => 'category13', 'query' => 'category:13')
            ),
        );
        $query = new Solarium_Query_Select($config);

        $this->assertEquals(
            $config['query'],
            $query->getQuery()
        );

        $this->assertEquals(
            $config['sort'],
            $query->getSortFields()
        );

        $this->assertEquals(
            $config['fields'],
            $query->getFields()
        );

        $this->assertEquals(
            $config['rows'],
            $query->getRows()
        );

        $this->assertEquals(
            $config['start'],
            $query->getStart()
        );
        
        $fq = $query->getFilterQuery('pub');
        $this->assertEquals(
            'published:true',
            $fq->getQuery()
        );

        $facets = $query->getFacets();
        $this->assertEquals(
            'category',
            $facets['categories']->getField()
        );
        $this->assertEquals(
            'category:13',
            $facets['category13']->getQuery()
        );
    }
}
