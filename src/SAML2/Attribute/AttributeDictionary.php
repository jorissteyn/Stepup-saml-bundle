<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\SamlBundle\SAML2\Attribute;

use SAML2_Assertion;
use Surfnet\SamlBundle\Exception\LogicException;
use Surfnet\SamlBundle\SAML2\Response\AssertionAdapter;

class AttributeDictionary
{
    /**
     * @var AttributeDefinition[]
     */
    private $definitions = [];

    /**
     * @param AttributeDefinition $definition
     */
    public function addAttributeDefinition(AttributeDefinition $definition)
    {
        if (isset($this->definitions[$definition->getName()])) {
            throw new LogicException(sprintf(
                'Cannot add attribute "%s" as it has already been added'
            ));
        }

        $this->definitions[$definition->getName()] = $definition;
    }

    /**
     * @param string $attributeName
     * @return bool
     */
    public function hasAttributeDefinition($attributeName)
    {
        return isset($this->definitions[$attributeName]);
    }

    /**
     * @param string $attributeName
     * @return AttributeDefinition
     */
    public function getAttributeDefinition($attributeName)
    {
        if (!$this->hasAttributeDefinition($attributeName)) {
            throw new LogicException(sprintf(
                'Cannot get AttributeDefinition "%s" as it has not been added to the collection',
                $attributeName
            ));
        }

        return $this->definitions[$attributeName];
    }

    /**
     * @param $urn
     * @return AttributeDefinition
     */
    public function findAttributeDefinitionByUrn($urn)
    {
        foreach ($this->definitions as $definition) {
            if ($definition->getUrnMace() === $urn || $definition->getUrnOid() === $urn) {
                return $definition;
            }
        }

        return null;
    }

    /**
     * @param SAML2_Assertion $assertion
     * @return AssertionAdapter
     */
    public function translate(SAML2_Assertion $assertion)
    {
        return new AssertionAdapter($assertion, $this);
    }
}
