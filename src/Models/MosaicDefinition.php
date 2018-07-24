<?php
/**
 * Part of the evias/nem-php package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @author     Robin Pedersen (https://github.com/RobertoSnap)
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php
 */
namespace NEM\Models;

/**
 * This is the MosaicDefinition class
 *
 * This class extends the NEM\Models\Model class
 * to provide with an integration of NEM's mosaic 
 * definition objects (not transaction).
 * 
 * @link https://nemproject.github.io/#mosaicDefinition
 */
class MosaicDefinition
    extends Model
{
    /**
     * List of fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        "creator",
        "id",
        "description",
        "properties",
        "levy",
    ];

    /**
     * The model instance's relations configuration
     *
     * @var array
     */
    protected $relations = [
        "id",
        "creator",
        "properties",
        "levy",
        "description",
    ];

    /**
     * Address DTO automatically cleans address representation.
     *
     * @param   boolean     $filterByKey    When set to `true`, the method will return the description field in hexadecimal format.
     * @return  array       Associative array with key `address` containing a NIS *compliable* address representation.
     */
    public function toDTO($filterByKey = null)
    {
        $isHexDescription = $filterByKey === true ? true : false;
        return [
            "creator" => $this->creator()->publicKey,
            "id" => $this->id()->toDTO(),
            "description" => $isHexDescription ? $this->description()->toHex()
                                                 : $this->description()->getPlain(),
            "properties" => $this->properties()->toDTO(),
            "levy" => $this->levy()->toDTO(),
        ];
    }

    /**
     * Overload of the \NEM\Core\Model::serialize() method to provide
     * with a specialization for *Mosaic Definition* serialization.
     *
     * @see \NEM\Contracts\Serializable
     * @param   null|string $parameters    non-null will return only the named sub-dtos.
     * @return  array   Returns a byte-array with values in UInt8 representation.
     */
    public function serialize($parameters = null)
    {
        $nisData = $this->toDTO(true); // true=hexadecimal description

        // shortcuts
        $serializer = $this->getSerializer();
        $publicKey  = hex2bin($nisData["creator"]);

        // bundle with length of pub key and public key in UInt8
        $publicKey  = $serializer->serializeString($publicKey);

        // serialize content
        $desc   = $serializer->serializeString(hex2bin($nisData["description"]));
        $mosaic = $this->id()->serialize();
        $props  = $this->properties()->serialize();
        $levy   = $this->levy()->serialize();

        // concatenate UInt8
        $output = array_merge($publicKey, $mosaic, $desc, $props, $levy);

        // do not use aggregator because MosaicDefinition's first byte
        // contains a public key size, not a DTO size.
        return $output;
    }

    /**
     * Mutator for `mosaic` relation.
     *
     * This will return a NIS compliant [MosaicId](https://bob.nem.ninja/docs/#mosaicId) object. 
     *
     * @param   array   $mosaidId       Array should contain offsets `namespaceId` and `name`.
     * @return  \NEM\Models\Mosaic
     */
    public function id(array $mosaicId = null)
    {
        return new Mosaic($mosaicId ?: $this->getAttribute("id"));
    }

    /**
     * Mutator for the recipient Account object.
     *
     * @return \NEM\Models\Account
     */
    public function creator($publicKey = null)
    {
        return new Account(["publicKey" => $publicKey ?: $this->getAttribute("creator")]);
    }

    /**
     * Mutator for `levy` relation.
     *
     * This will return a NIS compliant [MosaicLevy](https://bob.nem.ninja/docs/#mosaicLevy) object. 
     *
     * @param   array   $mosaidId       Array should contain offsets `type`, `recipient`, `mosaicId` and `fee`.
     * @return  \NEM\Models\MosaicLevy
     */
    public function levy(array $levy = null)
    {
        return new MosaicLevy($levy ?: $this->getAttribute("levy"));
    }

    /**
     * Mutator for `properties` relation.
     *
     * This will return a NIS compliant collection of [MosaicProperties](https://bob.nem.ninja/docs/#mosaicProperties) object. 
     *
     * @param   array   $properties       Array of MosaicProperty instances
     * @return  \NEM\Models\MosaicProperties
     */
    public function properties(array $properties = null)
    {
        return new MosaicProperties($properties ?: $this->getAttribute("properties"));
    }

    /**
     * Mutator for `description` relation.
     *
     * @param   array   $mosaidId       Array should contain offsets `namespaceId` and `name`.
     * @return  \NEM\Models\Mosaic
     */
    public function description($description = null)
    {
        return new Message(["plain" => $this->getAttribute("description") ?: ""]);
    }

    /**
     * Helper to read a given `name` mosaic property name.
     * 
     * This is just a proxy method for MosaicProperties::getProperty().
     * 
     * @param   string  $name       Mosaic property name.
     * @return  integer|boolean
     */
    public function getProperty($name)
    {
        return $this->properties()->getProperty($name);
    }

    /**
     * Getter for the Mosaic's Total Supply.
     * 
     * Mosaics with mutable supply must provide with a specialization
     * of this method in order to provide with the correct total supply.
     * 
     * In case no class is defined in the Mosaics Registry, the NIS Infrastructur
     * class will be integrated to provide with Mosaic Supply Requests. (to be implemented)
     * 
     * @return integer
     */
    public function getTotalSupply()
    {
        $initial = (int) $this->getProperty("initialSupply");
        return $initial;
    }
}
