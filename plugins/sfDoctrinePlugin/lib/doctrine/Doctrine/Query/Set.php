<?php
/*
 *  $Id: Set.php 5184 2008-11-18 15:32:31Z guilhermeblanco $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Query
 *
 * @package     Doctrine
 * @subpackage  Query
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 5184 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Query_Set extends Doctrine_Query_Part
{
    public function parse($dql)
    {
	    $terms = $this->_tokenizer->sqlExplode($dql, ' ');
    	
        foreach ($terms as $term) {
	        // We need to check for agg functions here
            $matches = array();
            $hasAggExpression = $this->_processPossibleAggExpression($term, $matches);
	
	        if ( ! $hasAggExpression) {
                preg_match_all("/[a-zA-Z0-9_]+[\.[a-zA-Z0-9]+]*/i", $term, $m);
            
                if (isset($m[0])) {
                    foreach ($m[0] as $part) {
	                    $part = trim($part);
	                    $lcpart = strtolower($part);
	
                        // we need to escape single valued possibilities (numeric, true, false, null)
	                    if ( ! (is_numeric($part) || $lcpart == 'true' || $lcpart == 'false' || $lcpart == 'null')) {
                            $e = explode('.', $part);

                            $fieldName  = array_pop($e);
                            $reference  = (count($e) > 0) ? implode('.', $e) : $this->query->getRootAlias();
                            $aliasMap   = $this->query->getAliasDeclaration($reference);
                            $columnName = $aliasMap['table']->getConnection()->quoteIdentifier($aliasMap['table']->getColumnName($fieldName));

                            $dql = str_replace($part, $columnName, $dql);
                        }
                    }
                }
            }            
        }

        return $dql;
    }


    protected function _processPossibleAggExpression(& $expr, & $matches = array())
    {
        $hasAggExpr = preg_match('/(.*[^\s\(\=])\(([^\)]*)\)(.*)/', $expr, $matches);
        
        if ($hasAggExpr) {
            $expr = $matches[2];

            // We need to process possible comma separated items
            if (substr(trim($matches[3]), 0, 1) == ',') {
                $xplod = $this->_tokenizer->sqlExplode(trim($matches[3], ' )'), ',');
                
                $matches[3] = array();
                    
                foreach ($xplod as $part) {
                    if ($part != '') {
                        $matches[3][] = $this->parseLiteralValue($part);
                    }
                }

                $matches[3] = '), ' . implode(', ', $matches[3]);
            }
        }
        
        return $hasAggExpr;
    }
}